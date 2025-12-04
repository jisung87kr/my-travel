---
issue: 2
started: 2025-12-04T12:53:14Z
status: in_progress
---

# Issue #2: 프론트엔드 기반 설정

## Scope
- Tailwind CSS 3.x 설치 및 설정
- Vite 5.x + Vue.js 3.x 플러그인 설정
- Pinia, Axios, Lodash 패키지 설치
- 디자인 시스템 설정 (컬러, 폰트, 그림자)
- Axios 인터셉터 설정
- Vite 빌드 최적화

## Files to Modify
- `src/tailwind.config.js`
- `src/vite.config.js`
- `src/resources/js/app.js`
- `src/resources/js/plugins/axios.js`
- `src/resources/css/app.css`
- `src/package.json`

## Progress
- [x] NPM 패키지 설치 (lodash, @vueuse/core 추가)
- [x] Tailwind CSS 설정 (Airbnb 스타일 컬러, 그림자, 둥근모서리)
- [x] Vite 빌드 설정 (lodash chunk 추가)
- [x] Vue.js 엔트리포인트 설정 (api 플러그인 연동)
- [x] Axios 플러그인 설정 (CSRF, 401/419/422 핸들링)
- [x] 빌드 테스트 - 성공!

## Completed
2025-12-04T12:53:14Z
