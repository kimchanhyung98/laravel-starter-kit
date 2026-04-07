#!/bin/bash

INPUT=$(cat)

# jq 존재 여부 확인 (fail-closed: jq 없으면 차단)
if ! command -v jq >/dev/null 2>&1; then
  cat <<'EOF'
{"hookSpecificOutput":{"hookEventName":"PreToolUse","permissionDecision":"deny","permissionDecisionReason":"Blocked: jq is required but not installed"}}
EOF
  exit 0
fi

TOOL_NAME=$(echo "$INPUT" | jq -r '.tool_name // empty' 2>/dev/null)

# jq 파싱 실패 시 차단
if [ -z "$TOOL_NAME" ]; then
  cat <<'EOF'
{"hookSpecificOutput":{"hookEventName":"PreToolUse","permissionDecision":"deny","permissionDecisionReason":"Blocked: failed to parse tool input"}}
EOF
  exit 0
fi

PROJECT_ROOT="${CLAUDE_PROJECT_DIR:-$(pwd)}"
RESOLVED_ROOT=$(cd "$PROJECT_ROOT" 2>/dev/null && pwd -P) || RESOLVED_ROOT="$PROJECT_ROOT"

deny() {
  jq -n \
    --arg reason "Blocked: $1" \
    '{
      hookSpecificOutput: {
        hookEventName: "PreToolUse",
        permissionDecision: "deny",
        permissionDecisionReason: $reason
      }
    }'
  exit 0
}

# 경로를 절대 경로로 정규화
resolve_path() {
  local p="${1/#\~/$HOME}"
  [[ "$p" != /* ]] && p="$PROJECT_ROOT/$p"

  local dir
  if dir=$(cd "$(dirname "$p")" 2>/dev/null && pwd -P); then
    # cd 성공: .. 등 심볼릭 링크가 정상 해석됨
    echo "$dir/$(basename "$p")"
  else
    # cd 실패(존재하지 않는 경로): .. 포함 시 traversal 우회 가능하므로 차단
    [[ "$p" == *..* ]] && return 1
    echo "$p"
  fi
}

# 프로젝트 외부 경로 여부 판별
is_outside_project() {
  local resolved
  resolved=$(resolve_path "$1") || return 0  # resolve 실패 시 차단
  # trailing slash 추가로 prefix matching bypass 방지
  [[ "$resolved/" != "$RESOLVED_ROOT/"* ]]
}

# Write|Edit: file_path 검증
if [[ "$TOOL_NAME" == "Write" || "$TOOL_NAME" == "Edit" ]]; then
  FILE_PATH=$(echo "$INPUT" | jq -r '.tool_input.file_path // empty')
  [ -z "$FILE_PATH" ] && exit 0

  if is_outside_project "$FILE_PATH"; then
    deny "$FILE_PATH is outside the project directory ($RESOLVED_ROOT)."
  fi
  exit 0
fi

# Bash: 파괴적 명령어의 대상 경로 검증
# 구분자(&&, ||, ;, |)로 분리 후 대상 명령으로 시작하는 실제 명령만 검증
if [[ "$TOOL_NAME" == "Bash" ]]; then
  COMMAND=$(echo "$INPUT" | jq -r '.tool_input.command // empty')
  [ -z "$COMMAND" ] && exit 0

  # 검사 대상 명령어 패턴 (파괴적 + 데이터 이동)
  DESTRUCTIVE_PATTERN='^(rm|mv|cp|chmod|chown|shred|dd|unlink|rmdir)[[:space:]]'

  while IFS= read -r cmd; do
    cmd="${cmd#"${cmd%%[![:space:]]*}"}"

    # 서브쉘/명령 치환 패턴 감지
    if [[ "$cmd" == *'$('*'rm '* ]] || [[ "$cmd" == *'`'*'rm '* ]]; then
      deny "subshell with destructive command detected."
    fi

    [[ ! "$cmd" =~ $DESTRUCTIVE_PATTERN ]] && continue

    for arg in $cmd; do
      # 명령어 자체와 옵션은 건너뜀
      [[ "$arg" =~ ^(rm|mv|cp|chmod|chown|shred|dd|unlink|rmdir)$ ]] && continue
      [[ "$arg" == -* ]] && continue

      # 따옴표 제거
      arg="${arg%\"}"
      arg="${arg#\"}"
      arg="${arg%\'}"
      arg="${arg#\'}"

      if is_outside_project "$arg"; then
        deny "target '$arg' is outside the project directory ($RESOLVED_ROOT)."
      fi
    done
  done < <(printf '%s' "$COMMAND" | awk '{gsub(/&&/,"\n"); gsub(/\|\|/,"\n"); gsub(/;/,"\n"); gsub(/\|/,"\n"); print}')
  exit 0
fi

exit 0
