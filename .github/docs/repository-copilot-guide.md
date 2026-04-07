# GitHub Repository, Copilot 설정 가이드

## MCP 서버 설정

레포지토리에서 Copilot, Coding agent의 MCP (Model Context Protocol) 서버 설정입니다.

| 서버                      | 용도          | 설명                     |
|-------------------------|-------------|------------------------|
| **context7**            | 라이브러리 문서 조회 | 최신 프레임워크 및 라이브러리 문서 참조 |
| **playwright**          | 브라우저 자동화    | E2E 테스트 및 브라우저 테스트 지원  |
| **sequential-thinking** | 복잡한 문제 해결   | 단계별 분석 및 체계적 사고 지원     |

### 설정 방법

Coding agent, MCP configuration:

```json
{
  "mcpServers": {
    "context7": {
      "type": "local",
      "command": "npx",
      "args": [
        "-y",
        "@upstash/context7-mcp"
      ],
      "tools": [
        "*"
      ]
    },
    "playwright": {
      "type": "local",
      "command": "npx",
      "args": [
        "-y",
        "@playwright/mcp@latest"
      ],
      "tools": [
        "*"
      ]
    },
    "sequential-thinking": {
      "type": "local",
      "command": "npx",
      "args": [
        "-y",
        "@modelcontextprotocol/server-sequential-thinking"
      ],
      "tools": [
        "*"
      ]
    }
  }
}
```
