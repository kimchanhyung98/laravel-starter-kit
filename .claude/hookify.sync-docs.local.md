---
name: sync-docs
enabled: false
event: file
action: warn
conditions:
    - field: file_path
      operator: regex_match
      pattern: \.(php|ts|tsx|js|jsx|py|svelte|cs|rs)$
---

📝 **Documentation sync required**

코드가 변경되었습니다. `app/Domains/{domain}/docs/`의 관련 문서를 **추가하거나 갱신**해야 하는지 확인하세요:

- 새 기능 추가 시 → 문서 **생성** 필요
- 기존 기능 수정/삭제 시 → 문서 **갱신** 필요

[ ] `adr/*.md` - 아키텍처 결정사항 변경 시
[ ] `bpmn.md` - 비즈니스 프로세스 흐름 변경 시
[ ] `planning.md` - 기능 계획/요구사항 변경 시
[ ] `tech-spec.md` - 기술 명세/API 스펙 변경 시
