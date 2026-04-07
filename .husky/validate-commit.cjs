#!/usr/bin/env node

const fs = require('fs');

// 허용 패턴
const validPattern = '<type>(scope): <subject> or <type>: <subject>';

// 커밋 메시지 파일 확인
const commitMsgFile = process.argv[2];
if (!commitMsgFile) {
    console.error('커밋 메시지 파일 오류');
    process.exit(1);
}

// 커밋 메시지 확인
let commitMsg;
try {
    commitMsg = fs.readFileSync(commitMsgFile, 'utf8').trim();
} catch (err) {
    console.error(`커밋 메시지 확인 오류: ${err.message}`);
    process.exit(1);
}

// merge/revert/initial 커밋은 제외
if (/^Merge /.test(commitMsg) || /^Revert "/.test(commitMsg) || commitMsg === 'Initial commit') {
    process.exit(0);
}

// 패턴: `type(scope): subject` or `type: subject` (scope는 선택 사항, 소문자만 허용)
const commitPattern = /^[a-z]+(\([a-z0-9_-]+\))?:\s+\S.*/;

if (!commitPattern.test(commitMsg)) {
    console.error(`* 잘못된 형식 : ${commitMsg}`);
    console.error(`* 올바른 형식 : ${validPattern}`);
    console.error('');
    process.exit(1);
}

process.exit(0);
