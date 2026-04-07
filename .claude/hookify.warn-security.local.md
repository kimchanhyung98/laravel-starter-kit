---
name: warn-security
enabled: true
event: file
action: warn
conditions:
    - field: new_text
      operator: regex_match
      pattern: console\.log\(|debugger;|var_dump\(|dd\(|print_r\(|(?i)(api[_-]?key|secret[_-]?key|password|token|auth[_-]?token)\s*[=:]\s*("[^"]{8,}"|'[^']{8,}')
---

⚠️ **Debug code or hardcoded secret detected**

커밋 전 확인이 필요한 패턴이 감지되었습니다:

- 디버그 코드: `console.log`, `debugger`, `var_dump`, `dd`, `print_r`
- 하드코딩된 시크릿: `api_key`, `password`, `token` 등

감지된 파일 경로와 라인 번호를 사용자에게 알려주세요. 의도된 코드일 수 있으므로 직접 제거하지 마세요.
