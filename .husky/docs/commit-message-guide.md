# 커밋 메시지 검증 가이드

**실행 시점**: `git commit` 시 (커밋 생성 전)
**스크립트**: `.husky/validate-commit.cjs`
**형식**: `<type>(scope): <subject>` 또는 `<type>: <subject>`

## 자동 제외

`Merge`, `Revert`로 시작하는 커밋은 검증하지 않습니다.

## 형식 규칙

### ✅ 올바른 형식

```bash
feat: 사용자 인증 추가
feat(auth): JWT 토큰 검증 추가
fix(api): null 응답 처리
docs: 설치 가이드 업데이트
```

### ❌ 잘못된 형식

```bash
Add feature          # 타입 누락
feat add feature     # 콜론 누락
feat(): 기능 추가    # 빈 scope
FEAT: 기능 추가      # 대문자 사용 금지
```

## Scope (선택사항)

Scope는 변경 범위를 명시합니다:

- `feat(api): REST API 추가`
- `fix(auth): 로그인 버그 수정`
- `refactor(ui): 컴포넌트 구조 개선`

## Subject 작성 규칙

1. **명령형 현재 시제 사용**: "추가했음" ❌ → "추가" ✅
2. **첫 글자 소문자**: "추가" ✅ (영어: "add" ✅)
3. **마침표 사용 안 함**: "기능 추가." ❌ → "기능 추가" ✅
4. **50자 이내 권장**: 간결하고 명확하게

## 검증 우회

```bash
# 긴급 상황에서만 사용
git commit --no-verify -m "message"
```

⚠️ **주의**: 가급적 사용하지 말고, 규칙을 준수하세요.
