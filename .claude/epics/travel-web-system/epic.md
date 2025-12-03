---
name: travel-web-system
status: backlog
created: 2025-12-03T13:41:42Z
progress: 0%
prd: .claude/prds/travel-web-system.md
github: [Will be updated when synced to GitHub]
---

# Epic: travel-web-system

## Overview

한국 방문 외국인을 위한 지역 관광 상품 예약 플랫폼의 기술 구현 에픽입니다. Laravel 10+ 백엔드와 Blade + Vue.js 프론트엔드를 사용하여, 4개의 사용자 역할(관광객, 제공자, 가이드, 관리자)이 각각의 기능을 수행할 수 있는 OTA(Online Travel Agency) 시스템을 구축합니다.

**핵심 기술 목표:**
- 다국어 지원 (한/영/중/일) 아키텍처
- 실시간 재고 관리가 가능한 예약 시스템
- 역할 기반 접근 제어 (RBAC)
- 소셜 로그인 통합 (Google, Kakao, Apple)

---

## Architecture Decisions

### AD1: 모노리틱 아키텍처 선택
- **결정**: Laravel 기반 모노리틱 MVC 구조
- **근거**: 개인 프로젝트로 빠른 개발 속도가 중요, 마이크로서비스는 오버엔지니어링
- **장점**: 단순한 배포, 트랜잭션 관리 용이, 개발 속도

### AD2: Blade + Vue.js 하이브리드
- **결정**: Blade 템플릿 기반에 Vue.js 컴포넌트 통합
- **근거**: 서버 사이드 렌더링(SEO)과 인터랙티브 UI의 균형
- **적용**:
  - Blade: 레이아웃, SEO 중요 페이지 (메인, 상품 목록/상세)
  - Vue.js: 대시보드, 예약 폼, 캘린더 등 인터랙티브 영역

### AD3: 다국어 전략
- **결정**: Laravel Localization + 데이터베이스 번역 테이블 분리
- **근거**: UI 번역과 컨텐츠 번역을 분리하여 유지보수 용이
- **구현**:
  - `lang/` 폴더: UI 문자열 (한/영/중/일)
  - `product_translations` 테이블: 상품 컨텐츠 다국어

### AD4: 인증 전략
- **결정**: Laravel Sanctum + Socialite
- **근거**: Passport보다 단순하며 SPA/웹 모두 지원
- **구현**: Session 기반 웹 인증 + 소셜 로그인 통합

### AD5: 파일 저장소
- **결정**: 로컬 저장소 → S3 마이그레이션 대비
- **근거**: 초기에는 로컬로 빠르게 시작, 추후 S3 전환 용이하도록 설계
- **구현**: Laravel Filesystem abstraction 활용

---

## Technical Approach

### Frontend Architecture

```
resources/
├── views/
│   ├── layouts/          # Blade 레이아웃
│   ├── traveler/         # 관광객 페이지
│   ├── vendor/           # 제공자 대시보드
│   ├── guide/            # 가이드 대시보드
│   └── admin/            # 관리자 대시보드
├── js/
│   ├── app.js            # Vue.js 엔트리
│   ├── components/       # Vue 컴포넌트
│   │   ├── booking/      # 예약 관련
│   │   ├── calendar/     # FullCalendar 래퍼
│   │   ├── product/      # 상품 관련
│   │   └── common/       # 공통 컴포넌트
│   └── stores/           # Pinia 스토어
└── css/
    └── app.css           # Tailwind CSS
```

**주요 Vue 컴포넌트:**
- `BookingForm.vue`: 예약 폼 (날짜, 인원, 옵션 선택)
- `ProductCalendar.vue`: FullCalendar 기반 일정 관리
- `InventoryManager.vue`: 날짜별 재고 관리
- `MessageThread.vue`: 메시지 송수신
- `QRCheckin.vue`: 가이드 QR 체크인

### Backend Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Traveler/     # 관광객 컨트롤러
│   │   ├── Vendor/       # 제공자 컨트롤러
│   │   ├── Guide/        # 가이드 컨트롤러
│   │   ├── Admin/        # 관리자 컨트롤러
│   │   └── Api/          # API 컨트롤러
│   └── Middleware/
│       └── CheckRole.php # 역할 기반 접근 제어
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── ProductTranslation.php
│   ├── Booking.php
│   ├── Review.php
│   └── Message.php
├── Services/
│   ├── BookingService.php    # 예약 비즈니스 로직
│   ├── InventoryService.php  # 재고 관리
│   └── NotificationService.php
└── Notifications/
    ├── BookingConfirmed.php
    └── BookingCancelled.php
```

### Database Schema (핵심 테이블)

```sql
-- 사용자
users (id, email, password, name, phone, role, language_preference, no_show_count, is_blocked)
social_accounts (id, user_id, provider, provider_id)

-- 제공자 추가 정보
vendors (id, user_id, business_name, business_number, status, approved_at)

-- 상품
products (id, vendor_id, type, region, duration, min_persons, max_persons,
          booking_type, status, created_at)
product_translations (id, product_id, locale, name, description, includes, excludes, notes)
product_prices (id, product_id, type, amount)  -- adult, child, option
product_images (id, product_id, path, order)

-- 재고/일정
product_schedules (id, product_id, date, available_count, is_active)

-- 예약
bookings (id, product_id, user_id, guide_id, schedule_id,
          adult_count, child_count, total_price, status,
          checked_in_at, completed_at)
booking_options (id, booking_id, option_name, price)

-- 리뷰
reviews (id, booking_id, user_id, rating, content, vendor_reply, created_at)
review_images (id, review_id, path)

-- 메시지
messages (id, booking_id, sender_id, receiver_id, content, read_at)

-- 신고
reports (id, reporter_id, reportable_type, reportable_id, reason, status, handled_at)
```

### API Endpoints (주요)

```
# 인증
POST   /auth/register
POST   /auth/login
GET    /auth/{provider}/redirect    # 소셜 로그인
GET    /auth/{provider}/callback

# 관광객
GET    /products                    # 상품 목록 (필터링)
GET    /products/{id}               # 상품 상세
POST   /bookings                    # 예약 생성
GET    /my/bookings                 # 내 예약 목록
DELETE /bookings/{id}               # 예약 취소
POST   /wishlist/{productId}        # 위시리스트 추가
POST   /reviews                     # 리뷰 작성

# 제공자
GET    /vendor/products             # 내 상품 목록
POST   /vendor/products             # 상품 등록
PUT    /vendor/products/{id}        # 상품 수정
GET    /vendor/bookings             # 예약 목록
PATCH  /vendor/bookings/{id}/approve
PATCH  /vendor/bookings/{id}/reject
PUT    /vendor/schedules/{productId}  # 일정/재고 관리
GET    /vendor/messages             # 메시지

# 가이드
GET    /guide/schedules             # 배정 일정
POST   /guide/checkin/{bookingId}   # QR 체크인
PATCH  /guide/bookings/{id}/complete

# 관리자
GET    /admin/users
PATCH  /admin/vendors/{id}/approve
GET    /admin/bookings
GET    /admin/reports
GET    /admin/statistics
```

### Infrastructure

**초기 배포 (단일 서버):**
```
┌─────────────────────────────────────┐
│           Nginx (Reverse Proxy)      │
│              + SSL (Let's Encrypt)   │
├─────────────────────────────────────┤
│           Laravel Application        │
│           (PHP-FPM 8.2+)            │
├─────────────────────────────────────┤
│    MySQL 8.0    │    Redis          │
└─────────────────────────────────────┘
         │
         ▼
   Local Storage → (추후 S3)
```

**외부 서비스:**
- SendGrid: 이메일 발송
- Google/Kakao/Apple: 소셜 로그인
- Google Analytics: 트래픽 분석

---

## Implementation Strategy

### 개발 순서 전략

**Phase 1: 기반 구축**
- Laravel 프로젝트 설정
- 데이터베이스 마이그레이션
- 인증 시스템 (기본 + 소셜)
- 역할 기반 접근 제어

**Phase 2: 핵심 기능**
- 상품 CRUD + 다국어
- 예약 시스템 (자동확정/승인)
- 실시간 재고 관리

**Phase 3: 사용자별 대시보드**
- 관광객 마이페이지
- 제공자 대시보드
- 가이드 대시보드
- 관리자 대시보드

**Phase 4: 부가 기능**
- 리뷰 시스템
- 메시지 시스템
- 노쇼 관리
- 통계

### 테스트 전략
- Feature Tests: 주요 플로우 (회원가입 → 예약 → 완료)
- Unit Tests: 재고 계산, 예약 상태 변경 로직
- Browser Tests (Dusk): 예약 폼 인터랙션

---

## Task Breakdown Preview

총 **10개 태스크**로 구성 (커맨드 제한 준수):

- [ ] **Task 1: 프로젝트 초기 설정** - Laravel 설치, 기본 설정, 패키지 설치 (Sanctum, Socialite, FullCalendar 등)
- [ ] **Task 2: 데이터베이스 설계 및 마이그레이션** - 전체 테이블 마이그레이션, 시더, 모델 관계 설정
- [ ] **Task 3: 인증 시스템 구현** - 회원가입/로그인, 소셜 로그인 (Google, Kakao, Apple), RBAC
- [ ] **Task 4: 상품 관리 시스템** - 상품 CRUD, 다국어 지원, 이미지 업로드, 검색/필터링
- [ ] **Task 5: 예약 시스템 구현** - 예약 생성, 자동확정/승인 로직, 재고 관리, 예약 상태 관리
- [ ] **Task 6: 관광객 프론트엔드** - 메인 페이지, 상품 목록/상세, 예약 폼, 마이페이지, 위시리스트
- [ ] **Task 7: 제공자 대시보드** - 상품 관리 UI, 예약 관리, 캘린더 일정 관리, 메시지
- [ ] **Task 8: 가이드 & 관리자 대시보드** - 가이드 일정/체크인, 관리자 사용자/상품/예약 관리, 통계
- [ ] **Task 9: 리뷰 & 커뮤니케이션** - 리뷰 CRUD, 메시지 시스템, 알림 (이메일)
- [ ] **Task 10: 테스트 & 배포 준비** - Feature/Unit 테스트, 성능 최적화, 배포 스크립트

---

## Dependencies

### 외부 의존성

| 서비스 | 용도 | 필요 시점 |
|--------|------|----------|
| Google OAuth | 소셜 로그인 | Task 3 |
| Kakao OAuth | 소셜 로그인 | Task 3 |
| Apple OAuth | 소셜 로그인 | Task 3 |
| SendGrid | 이메일 발송 | Task 9 |
| Google Analytics | 트래픽 분석 | Task 10 |

### 패키지 의존성

| 패키지 | 용도 |
|--------|------|
| laravel/sanctum | API 인증 |
| laravel/socialite | 소셜 로그인 |
| spatie/laravel-permission | RBAC |
| intervention/image | 이미지 처리 |
| fullcalendar | 캘린더 UI |
| simplesoftwareio/simple-qrcode | QR 코드 생성 |

### 태스크 간 의존성

```
Task 1 (초기 설정)
    ↓
Task 2 (DB 설계)
    ↓
Task 3 (인증) ─────────────────┐
    ↓                          │
Task 4 (상품) ←────────────────┤
    ↓                          │
Task 5 (예약) ←────────────────┤
    ↓                          │
Task 6 (관광객 FE) ←───────────┤
    ↓                          │
Task 7 (제공자) ←──────────────┤
    ↓                          │
Task 8 (가이드/관리자) ←───────┘
    ↓
Task 9 (리뷰/메시지)
    ↓
Task 10 (테스트/배포)
```

---

## Success Criteria (Technical)

### 성능 기준
| 항목 | 목표 |
|------|------|
| 페이지 로드 | < 3초 |
| API 응답 | < 500ms |
| 동시 접속 | 1,000명 |

### 품질 기준
- [ ] 테스트 커버리지 60% 이상
- [ ] 주요 플로우 Feature Test 통과
- [ ] Laravel Pint 코드 스타일 준수
- [ ] 보안 취약점 없음 (SQL Injection, XSS, CSRF)

### 완료 기준
- [ ] 4개 역할 모두 로그인/권한 동작
- [ ] 상품 등록 → 예약 → 완료 플로우 정상 동작
- [ ] 다국어 전환 (한/영) 정상 동작
- [ ] 반응형 레이아웃 (모바일/데스크톱)
- [ ] 배포 가능한 상태

---

## Estimated Effort

### 태스크별 규모

| 태스크 | 규모 | 복잡도 |
|--------|------|--------|
| Task 1: 프로젝트 초기 설정 | S | 낮음 |
| Task 2: DB 설계 | M | 중간 |
| Task 3: 인증 시스템 | M | 중간 |
| Task 4: 상품 관리 | L | 높음 |
| Task 5: 예약 시스템 | L | 높음 |
| Task 6: 관광객 FE | L | 중간 |
| Task 7: 제공자 대시보드 | L | 중간 |
| Task 8: 가이드/관리자 | M | 중간 |
| Task 9: 리뷰/메시지 | M | 낮음 |
| Task 10: 테스트/배포 | M | 중간 |

### Critical Path
1. Task 1 → 2 → 3 (기반 필수)
2. Task 4 → 5 (핵심 비즈니스 로직)
3. Task 6, 7, 8 (병렬 진행 가능)

### 리스크 요소
- 소셜 로그인 연동 (Apple이 가장 복잡)
- 실시간 재고 동시성 처리
- 다국어 컨텐츠 관리 복잡도