---
name: traveler-ui
status: backlog
created: 2025-12-04T12:37:59Z
progress: 0%
prd: .claude/prds/traveler-ui.md
github: https://github.com/jisung87kr/my-travel/issues/1
---

# Epic: traveler-ui

## Overview

한국 방문 외국인 관광객을 위한 프론트엔드 UI를 구현합니다. 에어비앤비 스타일의 모던하고 직관적인 디자인으로, 상품 탐색부터 예약 완료까지 매끄러운 사용자 경험을 제공합니다.

기존 Laravel 백엔드 API를 활용하며, Blade + Vue.js 하이브리드 구조로 구현합니다.

## Architecture Decisions

| 결정 | 선택 | 근거 |
|------|------|------|
| 렌더링 방식 | Blade + Vue.js 하이브리드 | SEO 필요 페이지는 SSR, 인터랙티브 요소는 Vue |
| CSS 프레임워크 | Tailwind CSS 3.x | 유틸리티 기반으로 빠른 개발, 번들 최적화 |
| 상태 관리 | Pinia | Vue 3 공식 권장, 간결한 API |
| HTTP 클라이언트 | Axios | 인터셉터 지원, CSRF 토큰 자동 처리 |
| 빌드 도구 | Vite 5.x | Laravel 기본 통합, HMR 지원 |

## Technical Approach

### Frontend Components

**Blade 컴포넌트 (SSR)**
- 레이아웃: `app.blade.php`, `header.blade.php`, `footer.blade.php`, `mobile-nav.blade.php`
- UI 컴포넌트: `modal`, `dropdown`, `toast`, `alert`, `button`, `input`, `card`, `badge`, `skeleton`, `pagination`

**Vue.js 컴포넌트 (인터랙티브)**
- 검색: `SearchBar.vue` (자동완성, 디바운스)
- 예약: `BookingWidget.vue`, `DatePicker.vue`, `GuestSelector.vue`
- 상품: `ImageGallery.vue`, `ProductCard.vue`
- 공통: `WishlistButton.vue`, `ShareButton.vue`, `Toast.vue`

### Backend Services

기존 API 활용 (신규 개발 최소화):
- `GET /api/products` - 상품 목록 (필터/정렬/페이지네이션)
- `GET /api/products/{id}` - 상품 상세
- `POST /api/bookings` - 예약 생성
- `GET /api/my/bookings` - 내 예약 목록
- `POST /api/wishlist` - 위시리스트 토글

### Infrastructure

- CDN: 정적 자원 (이미지, CSS, JS)
- 캐싱: API 응답 캐싱 (Redis)
- 이미지: WebP 변환, Lazy Loading, Placeholder Blur

## Implementation Strategy

1. **Phase 1**: 기반 설정 (Tailwind, Vite, 디자인 시스템)
2. **Phase 2**: 공통 컴포넌트 (UI/레이아웃)
3. **Phase 3**: 핵심 페이지 (메인, 상품목록, 상품상세)
4. **Phase 4**: 예약 플로우 및 마이페이지
5. **Phase 5**: 반응형 최적화 및 테스트

## Task Breakdown Preview

- [ ] Task 001: 프론트엔드 기반 설정 (Tailwind, Vite, 디자인 시스템)
- [ ] Task 002: 공통 UI 컴포넌트 (Modal, Toast, Dropdown, Button 등)
- [ ] Task 003: 레이아웃 컴포넌트 (Header, Footer, Mobile Nav)
- [ ] Task 004: 메인 페이지 (Hero, 카테고리, 추천 상품)
- [ ] Task 005: 상품 목록 페이지 (필터, 그리드, 무한스크롤)
- [ ] Task 006: 상품 상세 페이지 (갤러리, 예약위젯, 리뷰)
- [ ] Task 007: 예약 플로우 (예약생성, 예약완료)
- [ ] Task 008: 마이페이지 (예약내역, 위시리스트, 프로필)
- [ ] Task 009: 반응형 최적화 및 성능 테스트

## Dependencies

**외부 의존성:**
- NPM: vue@3.4, pinia@2.1, axios@1.6, lodash@4.17, @vueuse/core@10.7
- NPM (dev): tailwindcss@3.4, vite@5.0, @vitejs/plugin-vue@5.0

**내부 의존성:**
- travel-web-system 에픽 완료 (API 엔드포인트)
- 기존 인증 시스템 (Laravel Sanctum)

## Success Criteria (Technical)

| 항목 | 목표 |
|------|------|
| Lighthouse Performance | 80+ |
| First Contentful Paint | < 1.5s |
| Time to Interactive | < 3s |
| 번들 사이즈 (gzip) | < 200KB |
| 모바일 반응형 | 320px ~ 1920px |
| 브라우저 지원 | Chrome, Safari, Firefox (최신 2버전) |

## Estimated Effort

| 태스크 | 규모 | 예상 |
|--------|------|------|
| 기반 설정 | S | 0.5일 |
| 공통 UI 컴포넌트 | M | 1일 |
| 레이아웃 컴포넌트 | M | 0.5일 |
| 메인 페이지 | L | 1일 |
| 상품 목록 | L | 1일 |
| 상품 상세 | L | 1일 |
| 예약 플로우 | M | 1일 |
| 마이페이지 | M | 1일 |
| 반응형/테스트 | M | 1일 |
| **총합** | - | **8일** |

## Tasks Created

- [ ] #2 - 프론트엔드 기반 설정 (parallel: false)
- [ ] #3 - 공통 UI 컴포넌트 (parallel: true)
- [ ] #4 - 레이아웃 컴포넌트 (parallel: true)
- [ ] #5 - 메인 페이지 (parallel: true)
- [ ] #6 - 상품 목록 페이지 (parallel: true)
- [ ] #7 - 상품 상세 페이지 (parallel: true)
- [ ] #8 - 예약 플로우 (parallel: false)
- [ ] #9 - 마이페이지 (parallel: true)
- [ ] #10 - 반응형 최적화 및 테스트 (parallel: false)

**Total tasks:** 9
**Parallel tasks:** 6
**Sequential tasks:** 3
**Estimated total effort:** 64 hours (8일)
