#!/usr/bin/env node

const {execSync} = require('child_process');

// 허용 패턴
const validPattern = '<type>/<description> 또는 <type>/<segment>/<segment>/...';

// 현재 브랜치명 확인
let currentBranch;
try {
    currentBranch = execSync('git rev-parse --abbrev-ref HEAD', {encoding: 'utf8'}).trim();
} catch (err) {
    console.error(`브랜치명 확인 오류: ${err.message}`);
    process.exit(1);
}

// 메인 브랜치들은 직접 푸시 허용
const protectedBranches = ['main', 'master', 'develop', 'staging'];
if (protectedBranches.includes(currentBranch)) {
    process.exit(0);
}

// 패턴: `type/description` 또는 `type/segment1/segment2/...` (depth 제한 없음)
// 허용: 소문자(a-z), 숫자(0-9), 하이픈(-), 점(.), 언더바(_)
const branchPattern = /^[a-z]+\/[a-z0-9][a-z0-9._-]*(\/[a-z0-9][a-z0-9._-]*)*$/;
if (!branchPattern.test(currentBranch)) {
    console.error(`* 잘못된 형식 : ${currentBranch}`);
    console.error(`* 올바른 형식 : ${validPattern}`);
    console.error('');
    process.exit(1);
}

process.exit(0);
