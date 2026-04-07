# 브랜치명 검증 가이드

**실행 시점**: Pull Request 생성/수정 시 (자동)
**워크플로우**: `.github/workflows/branch-name-check.yml`
**형식**: `<type>/<description>` 또는 `<type>/<segment>/<segment>/...` (depth 제한 없음)

## 보호 브랜치 (검사 제외)

`main`, `master`, `develop`, `staging`

## 브랜치명 규칙

- **타입**: 소문자 영문
- **구분자**: `/` (슬래시) 사용
- **허용 문자**: 소문자(a-z), 숫자(0-9), 하이픈(-), 점(.), 언더바(_)

### ✅ 올바른 형식

```bash
feature/user-authentication
fix/404-error
copilot/add-validation
claude/refactor-api
hotfix/security-patch-2024
release/1.0.0
feature/frontend/user-authentication
fix/api/null-response
refactor/backend/auth-logic
docs/api/endpoint-guide
test/integration/payment-flow
dependabot/npm_and_yarn/turbo-2.7.3
dependabot/npm_and_yarn/tailwindcss/postcss-4.1.18
```

### ❌ 잘못된 형식

```bash
❌ Feature/Frontend/User-auth  # 타입, 도메인, 설명에 대문자 사용 금지
❌ my-feature                  # 타입 누락
❌ feature/                    # 설명 누락
```

## 검증 계층

| 계층             | 시점              | 우회 가능 여부                   |
|----------------|-----------------|----------------------------|
| 로컬 Husky       | `git push` 실행 시 | ✅ `--no-verify` 플래그로 우회 가능 |
| GitHub Actions | PR 생성/수정 시      | ❌ 필수 체크 (우회 불가)            |

## Branch Protection 설정 권장

**Settings → Branches → Branch protection rules**

1. `main` 브랜치에 보호 규칙 추가
2. **Require status checks to pass before merging** 활성화
3. **validate-branch-name** 체크 필수로 지정

이렇게 설정하면 브랜치명 규칙을 위반한 PR은 병합할 수 없습니다.
