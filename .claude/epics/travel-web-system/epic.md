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

## Coding Standards (코드 작성 규칙)

### 1. 서비스 레이어 분리 (Service Layer Pattern)

**컨트롤러에 비즈니스 로직을 담지 않는다.** 컨트롤러는 요청 검증과 응답 반환만 담당.

```php
// ❌ Bad - 컨트롤러에 비즈니스 로직
class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 비즈니스 로직이 컨트롤러에...
        $schedule = ProductSchedule::where('product_id', $request->product_id)
            ->where('date', $request->date)
            ->lockForUpdate()
            ->first();

        if ($schedule->available_count < $request->persons) {
            return response()->json(['error' => '재고 부족'], 400);
        }
        // ... 더 많은 로직
    }
}

// ✅ Good - 서비스 레이어로 분리
class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->create(
            $request->validated(),
            $request->user()
        );

        return response()->success($booking, '예약이 완료되었습니다.', 201);
    }
}
```

**서비스 레이어 구조:**

```
app/Services/
├── BookingService.php       # 예약 생성, 취소, 상태 변경
├── InventoryService.php     # 재고 확인, 차감, 복구
├── ProductService.php       # 상품 CRUD, 검색
├── NotificationService.php  # 알림 발송
├── ImageService.php         # 이미지 업로드, 리사이징
└── MessageService.php       # 메시지 송수신
```

### 2. Enum 클래스로 상수 관리

**상수는 문자열/숫자 대신 Enum 클래스로 관리한다.**

```php
// app/Enums/UserRole.php
enum UserRole: string
{
    case TRAVELER = 'traveler';
    case VENDOR = 'vendor';
    case GUIDE = 'guide';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::TRAVELER => '관광객',
            self::VENDOR => '제공자',
            self::GUIDE => '가이드',
            self::ADMIN => '관리자',
        };
    }
}

// app/Enums/BookingStatus.php
enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case NO_SHOW = 'no_show';

    public function label(): string
    {
        return match($this) {
            self::PENDING => '대기중',
            self::CONFIRMED => '확정',
            self::CANCELLED => '취소됨',
            self::COMPLETED => '완료',
            self::NO_SHOW => '노쇼',
        };
    }

    public function canTransitionTo(BookingStatus $status): bool
    {
        return match($this) {
            self::PENDING => in_array($status, [self::CONFIRMED, self::CANCELLED]),
            self::CONFIRMED => in_array($status, [self::COMPLETED, self::CANCELLED, self::NO_SHOW]),
            default => false,
        };
    }
}

// app/Enums/ProductType.php
enum ProductType: string
{
    case DAY_TOUR = 'day_tour';
    case PACKAGE = 'package';
    case ACTIVITY = 'activity';
}

// app/Enums/BookingType.php
enum BookingType: string
{
    case INSTANT = 'instant';    // 자동 확정
    case REQUEST = 'request';    // 승인 필요
}
```

**Enum 디렉토리 구조:**

```
app/Enums/
├── UserRole.php
├── BookingStatus.php
├── BookingType.php
├── ProductType.php
├── ProductStatus.php
├── VendorStatus.php
├── ReportStatus.php
└── Locale.php
```

### 3. API 응답 클래스 (일관된 응답 포맷)

**모든 API 응답은 일관된 구조를 따른다. 별도의 ApiResponse 클래스를 생성하여 매크로를 등록한다.**

#### ApiResponse 클래스

```php
// app/Http/Responses/ApiResponse.php
<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * 성공 응답
     */
    public static function success(
        mixed $data = null,
        ?string $message = null,
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * 생성 성공 응답 (201)
     */
    public static function created(
        mixed $data = null,
        ?string $message = '리소스가 생성되었습니다.'
    ): JsonResponse {
        return self::success($data, $message, 201);
    }

    /**
     * 에러 응답
     */
    public static function error(
        string $message,
        int $code = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * 유효성 검사 에러 응답 (422)
     */
    public static function validationError(
        mixed $errors,
        string $message = '입력값을 확인해주세요.'
    ): JsonResponse {
        return self::error($message, 422, $errors);
    }

    /**
     * 권한 없음 응답 (403)
     */
    public static function forbidden(
        string $message = '권한이 없습니다.'
    ): JsonResponse {
        return self::error($message, 403);
    }

    /**
     * 리소스 없음 응답 (404)
     */
    public static function notFound(
        string $message = '리소스를 찾을 수 없습니다.'
    ): JsonResponse {
        return self::error($message, 404);
    }

    /**
     * 서버 에러 응답 (500)
     */
    public static function serverError(
        string $message = '서버 오류가 발생했습니다.'
    ): JsonResponse {
        return self::error($message, 500);
    }

    /**
     * 페이지네이션 응답
     */
    public static function paginated(
        LengthAwarePaginator $paginator,
        ?string $message = null
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }

    /**
     * 삭제 성공 응답 (204 No Content 또는 200)
     */
    public static function deleted(
        ?string $message = '삭제되었습니다.'
    ): JsonResponse {
        return self::success(null, $message);
    }
}
```

#### Response 매크로 등록

```php
// app/Providers/ResponseServiceProvider.php
<?php

namespace App\Providers;

use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 성공 응답 매크로
        Response::macro('success', function ($data = null, $message = null, $code = 200) {
            return ApiResponse::success($data, $message, $code);
        });

        // 생성 성공 매크로
        Response::macro('created', function ($data = null, $message = '리소스가 생성되었습니다.') {
            return ApiResponse::created($data, $message);
        });

        // 에러 응답 매크로
        Response::macro('error', function ($message, $code = 400, $errors = null) {
            return ApiResponse::error($message, $code, $errors);
        });

        // 유효성 검사 에러 매크로
        Response::macro('validationError', function ($errors, $message = '입력값을 확인해주세요.') {
            return ApiResponse::validationError($errors, $message);
        });

        // 권한 없음 매크로
        Response::macro('forbidden', function ($message = '권한이 없습니다.') {
            return ApiResponse::forbidden($message);
        });

        // 리소스 없음 매크로
        Response::macro('notFound', function ($message = '리소스를 찾을 수 없습니다.') {
            return ApiResponse::notFound($message);
        });

        // 페이지네이션 매크로
        Response::macro('paginated', function ($paginator, $message = null) {
            return ApiResponse::paginated($paginator, $message);
        });

        // 삭제 성공 매크로
        Response::macro('deleted', function ($message = '삭제되었습니다.') {
            return ApiResponse::deleted($message);
        });
    }
}
```

#### 서비스 프로바이더 등록

```php
// bootstrap/providers.php (Laravel 11+)
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ResponseServiceProvider::class,  // 추가
];

// 또는 config/app.php (Laravel 10)
'providers' => [
    // ...
    App\Providers\ResponseServiceProvider::class,
],
```

#### 디렉토리 구조

```
app/
├── Http/
│   └── Responses/
│       └── ApiResponse.php      # API 응답 클래스
└── Providers/
    └── ResponseServiceProvider.php  # 매크로 등록
```

#### 사용 예시

```php
use App\Http\Responses\ApiResponse;

// 방법 1: ApiResponse 클래스 직접 사용 (권장)
return ApiResponse::success($product, '상품이 등록되었습니다.', 201);
return ApiResponse::created($booking, '예약이 완료되었습니다.');
return ApiResponse::error('재고가 부족합니다.', 400);
return ApiResponse::paginated($products);
return ApiResponse::notFound('상품을 찾을 수 없습니다.');
return ApiResponse::forbidden('접근 권한이 없습니다.');

// 방법 2: Response 매크로 사용
return response()->success($product, '상품이 등록되었습니다.');
return response()->created($booking, '예약이 완료되었습니다.');
return response()->error('재고가 부족합니다.', 400);
return response()->paginated($products);
return response()->notFound('상품을 찾을 수 없습니다.');
```

#### 응답 예시

```json
// 성공 응답
{
    "success": true,
    "message": "상품이 등록되었습니다.",
    "data": { "id": 1, "name": "전주 한옥마을 투어" }
}

// 에러 응답
{
    "success": false,
    "message": "재고가 부족합니다.",
    "errors": null
}

// 페이지네이션 응답
{
    "success": true,
    "message": null,
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 100,
        "from": 1,
        "to": 20
    },
    "links": {
        "first": "http://...",
        "last": "http://...",
        "prev": null,
        "next": "http://...?page=2"
    }
}
```

### 4. 디자인 패턴 활용

**유지보수성 향상을 위해 적절한 디자인 패턴을 사용한다.**

#### Repository Pattern (선택적)
복잡한 쿼리가 많은 경우 Repository 패턴 적용.

```php
// app/Repositories/ProductRepository.php
class ProductRepository
{
    public function findWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['translations', 'prices', 'images', 'vendor']);

        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['date'])) {
            $query->whereHas('schedules', fn($q) =>
                $q->where('date', $filters['date'])
                  ->where('available_count', '>', 0)
            );
        }

        return $query->paginate($filters['per_page'] ?? 20);
    }
}
```

#### Strategy Pattern
다양한 알림 채널 처리 등.

```php
// app/Services/Notification/NotificationChannel.php
interface NotificationChannel
{
    public function send(User $user, string $message): void;
}

// app/Services/Notification/EmailChannel.php
class EmailChannel implements NotificationChannel
{
    public function send(User $user, string $message): void
    {
        Mail::to($user)->send(new GenericNotification($message));
    }
}

// app/Services/Notification/KakaoChannel.php
class KakaoChannel implements NotificationChannel
{
    public function send(User $user, string $message): void
    {
        // 카카오 알림톡 발송
    }
}
```

#### Factory Pattern
예약 생성 시 상품 타입별 처리.

```php
// app/Services/Booking/BookingFactory.php
class BookingFactory
{
    public function create(Product $product, array $data): Booking
    {
        return match($product->booking_type) {
            BookingType::INSTANT => $this->createInstantBooking($product, $data),
            BookingType::REQUEST => $this->createRequestBooking($product, $data),
        };
    }
}
```

### 5. 프론트엔드: Blade + Vue.js 하이브리드

**Blade 템플릿과 Vue.js 컴포넌트를 조합하여 사용한다.**

#### 구조 원칙

| 영역 | 기술 | 이유 |
|------|------|------|
| 레이아웃, 메인 페이지 | Blade | SEO, 초기 로드 속도 |
| 상품 목록/상세 | Blade + 부분 Vue | SEO 중요, 일부 인터랙션 |
| 예약 폼, 대시보드 | Vue.js | 복잡한 인터랙션, 실시간 업데이트 |
| 캘린더, 차트 | Vue.js | 외부 라이브러리 통합 |

#### Blade에서 Vue 컴포넌트 사용

```blade
{{-- resources/views/traveler/products/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="product-detail">
    {{-- SEO를 위한 Blade 렌더링 --}}
    <h1>{{ $product->getTranslation()->name }}</h1>
    <p>{{ $product->getTranslation()->description }}</p>

    {{-- 인터랙티브 예약 폼은 Vue 컴포넌트 --}}
    <div id="booking-form">
        <booking-form
            :product="{{ json_encode($product) }}"
            :prices="{{ json_encode($product->prices) }}"
            :schedules="{{ json_encode($product->schedules) }}"
        />
    </div>
</div>
@endsection
```

#### Vue 컴포넌트 등록

```js
// resources/js/app.js
import { createApp } from 'vue'
import { createPinia } from 'pinia'

// 컴포넌트 임포트
import BookingForm from './components/booking/BookingForm.vue'
import ProductCalendar from './components/calendar/ProductCalendar.vue'
import ImageGallery from './components/product/ImageGallery.vue'

const app = createApp({})
const pinia = createPinia()

app.use(pinia)

// 전역 컴포넌트 등록
app.component('booking-form', BookingForm)
app.component('product-calendar', ProductCalendar)
app.component('image-gallery', ImageGallery)

app.mount('#app')
```

### 6. 추가 코딩 규칙

- **Form Request**: 모든 입력 검증은 Form Request 클래스에서 처리
- **Policy**: 권한 검사는 Policy 클래스에서 처리
- **Resource**: API 응답 변환은 API Resource 클래스 사용
- **Event/Listener**: 부수 효과(알림 발송 등)는 이벤트로 분리
- **Exception**: 커스텀 예외 클래스로 에러 상황 명확화

```
app/
├── Enums/              # 상수 Enum 클래스
├── Services/           # 비즈니스 로직
├── Repositories/       # 복잡한 쿼리 (선택적)
├── Http/
│   ├── Controllers/    # 요청/응답만 처리
│   ├── Requests/       # Form Request (입력 검증)
│   └── Resources/      # API Resource (응답 변환)
├── Policies/           # 권한 검사
├── Events/             # 이벤트 정의
├── Listeners/          # 이벤트 리스너
└── Exceptions/         # 커스텀 예외
```

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

---

## Tasks Created

| # | 태스크 | 규모 | 병렬 | 의존성 |
|---|--------|------|------|--------|
| 001 | 프로젝트 초기 설정 | S | ❌ | - |
| 002 | 데이터베이스 설계 및 마이그레이션 | M | ❌ | 001 |
| 003 | 인증 시스템 구현 | M | ❌ | 001, 002 |
| 004 | 상품 관리 시스템 | L | ✅ | 002, 003 |
| 005 | 예약 시스템 구현 | L | ❌ | 002, 003, 004 |
| 006 | 관광객 프론트엔드 | L | ✅ | 003, 004, 005 |
| 007 | 제공자 대시보드 | L | ✅ | 003, 004, 005 |
| 008 | 가이드 & 관리자 대시보드 | M | ✅ | 003, 005 |
| 009 | 리뷰 & 커뮤니케이션 | M | ❌ | 005, 006, 007 |
| 010 | 테스트 & 배포 준비 | M | ❌ | 006, 007, 008, 009 |

**총 태스크**: 10개
**병렬 가능 태스크**: 4개 (004, 006, 007, 008)
**순차 태스크**: 6개