# Blog Feature Implementation

## 요구사항 요약

| 항목 | 내용 |
|------|------|
| 작성자 | 관리자(Admin)만 |
| 기능 범위 | 고급 (카테고리, 태그, 시리즈, 댓글, 좋아요, 조회수, 관련글 추천) |
| 다국어 | 아니오 (한국어 단일) |
| 에디터 | Tiptap WYSIWYG |

---

## 1. Database Migrations

### 1.1 blog_categories
```
src/database/migrations/2026_01_03_000001_create_blog_categories_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| name | string | 카테고리명 |
| slug | string | URL 슬러그 (unique) |
| description | text | 설명 (nullable) |
| sort_order | int | 정렬 순서 |
| is_active | boolean | 활성화 여부 |
| timestamps | - | created_at, updated_at |
| softDeletes | - | deleted_at |

### 1.2 blog_series
```
src/database/migrations/2026_01_03_000002_create_blog_series_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| title | string | 시리즈 제목 |
| slug | string | URL 슬러그 (unique) |
| description | text | 설명 (nullable) |
| thumbnail_path | string | 썸네일 이미지 (nullable) |
| post_count | int | 포스트 수 (캐시) |
| is_active | boolean | 활성화 여부 |
| timestamps | - | created_at, updated_at |
| softDeletes | - | deleted_at |

### 1.3 blog_posts
```
src/database/migrations/2026_01_03_000003_create_blog_posts_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| user_id | bigint | FK → users |
| category_id | bigint | FK → blog_categories (nullable) |
| series_id | bigint | FK → blog_series (nullable) |
| series_order | int | 시리즈 내 순서 (nullable) |
| title | string | 제목 |
| slug | string | URL 슬러그 (unique) |
| excerpt | text | 요약 (nullable) |
| content | longText | 본문 (HTML) |
| thumbnail_path | string | 썸네일 이미지 (nullable) |
| status | string | draft/published/archived |
| published_at | timestamp | 발행일시 (nullable) |
| view_count | int | 조회수 |
| like_count | int | 좋아요 수 (캐시) |
| comment_count | int | 댓글 수 (캐시) |
| meta_title | string | SEO 제목 (nullable) |
| meta_description | text | SEO 설명 (nullable) |
| timestamps | - | created_at, updated_at |
| softDeletes | - | deleted_at |

### 1.4 blog_tags
```
src/database/migrations/2026_01_03_000004_create_blog_tags_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| name | string | 태그명 |
| slug | string | URL 슬러그 (unique) |
| post_count | int | 포스트 수 (캐시) |
| timestamps | - | created_at, updated_at |

### 1.5 blog_post_tag (pivot)
```
src/database/migrations/2026_01_03_000005_create_blog_post_tag_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| blog_post_id | bigint | FK → blog_posts |
| blog_tag_id | bigint | FK → blog_tags |
| timestamps | - | created_at, updated_at |

- Unique constraint: (blog_post_id, blog_tag_id)

### 1.6 blog_comments
```
src/database/migrations/2026_01_03_000006_create_blog_comments_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| blog_post_id | bigint | FK → blog_posts |
| user_id | bigint | FK → users |
| parent_id | bigint | FK → blog_comments (self, nullable) |
| content | text | 댓글 내용 |
| is_approved | boolean | 승인 여부 |
| is_visible | boolean | 표시 여부 |
| timestamps | - | created_at, updated_at |
| softDeletes | - | deleted_at |

### 1.7 blog_likes
```
src/database/migrations/2026_01_03_000007_create_blog_likes_table.php
```
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| blog_post_id | bigint | FK → blog_posts |
| user_id | bigint | FK → users |
| timestamps | - | created_at, updated_at |

- Unique constraint: (blog_post_id, user_id)

---

## 2. Models

### 2.1 BlogPost
```
src/app/Models/BlogPost.php
```
**Relationships:**
- `belongsTo`: User, BlogCategory, BlogSeries
- `belongsToMany`: BlogTag
- `hasMany`: BlogComment, BlogLike

**Scopes:**
- `published()`: status = published, published_at <= now
- `draft()`: status = draft
- `archived()`: status = archived

### 2.2 BlogCategory
```
src/app/Models/BlogCategory.php
```
**Relationships:**
- `hasMany`: BlogPost

### 2.3 BlogSeries
```
src/app/Models/BlogSeries.php
```
**Relationships:**
- `hasMany`: BlogPost (ordered by series_order)

### 2.4 BlogTag
```
src/app/Models/BlogTag.php
```
**Relationships:**
- `belongsToMany`: BlogPost

### 2.5 BlogComment
```
src/app/Models/BlogComment.php
```
**Relationships:**
- `belongsTo`: BlogPost, User, parent (self)
- `hasMany`: replies (self)

### 2.6 BlogLike
```
src/app/Models/BlogLike.php
```
**Relationships:**
- `belongsTo`: BlogPost, User

---

## 3. Enum

### BlogPostStatus
```
src/app/Enums/BlogPostStatus.php
```
```php
enum BlogPostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => '임시저장',
            self::PUBLISHED => '발행됨',
            self::ARCHIVED => '보관됨',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'green',
            self::ARCHIVED => 'yellow',
        };
    }
}
```

---

## 4. Service

### BlogService
```
src/app/Services/BlogService.php
```

**Methods:**

| Method | Description |
|--------|-------------|
| `create(array $data)` | 포스트 생성 (with thumbnail via ImageService) |
| `update(BlogPost $post, array $data)` | 포스트 수정 |
| `delete(BlogPost $post)` | 포스트 삭제 (soft delete) |
| `publish(BlogPost $post)` | 발행 (status → published, published_at → now) |
| `unpublish(BlogPost $post)` | 발행 취소 (status → draft) |
| `archive(BlogPost $post)` | 보관 (status → archived) |
| `syncTags(BlogPost $post, array $tagNames)` | 태그 동기화 (없으면 생성) |
| `recordView(BlogPost $post, ?User $user, string $ip)` | 조회수 기록 (session 기반 중복 방지) |
| `toggleLike(BlogPost $post, User $user)` | 좋아요 토글 |
| `createComment(BlogPost $post, User $user, array $data)` | 댓글 생성 |
| `deleteComment(BlogComment $comment)` | 댓글 삭제 |
| `search(array $filters)` | 검색 (카테고리, 태그, 시리즈 필터링) |
| `getRelatedPosts(BlogPost $post, int $limit = 4)` | 관련글 추천 (같은 카테고리/태그 기반) |

---

## 5. Controllers

### 5.1 Admin Controllers

#### BlogPostController
```
src/app/Http/Controllers/Admin/BlogPostController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| index | GET /admin/blog/posts | 목록 |
| create | GET /admin/blog/posts/create | 작성 폼 |
| store | POST /admin/blog/posts | 저장 |
| show | GET /admin/blog/posts/{post} | 상세 |
| edit | GET /admin/blog/posts/{post}/edit | 수정 폼 |
| update | PUT /admin/blog/posts/{post} | 수정 |
| destroy | DELETE /admin/blog/posts/{post} | 삭제 |
| publish | PATCH /admin/blog/posts/{post}/publish | 발행 |
| unpublish | PATCH /admin/blog/posts/{post}/unpublish | 발행 취소 |
| archive | PATCH /admin/blog/posts/{post}/archive | 보관 |

#### BlogCategoryController
```
src/app/Http/Controllers/Admin/BlogCategoryController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| index | GET /admin/blog/categories | 목록 |
| create | GET /admin/blog/categories/create | 생성 폼 |
| store | POST /admin/blog/categories | 저장 |
| edit | GET /admin/blog/categories/{category}/edit | 수정 폼 |
| update | PUT /admin/blog/categories/{category} | 수정 |
| destroy | DELETE /admin/blog/categories/{category} | 삭제 |
| reorder | POST /admin/blog/categories/reorder | 순서 변경 |

#### BlogSeriesController
```
src/app/Http/Controllers/Admin/BlogSeriesController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| index | GET /admin/blog/series | 목록 |
| create | GET /admin/blog/series/create | 생성 폼 |
| store | POST /admin/blog/series | 저장 |
| show | GET /admin/blog/series/{series} | 상세 (포스트 목록) |
| edit | GET /admin/blog/series/{series}/edit | 수정 폼 |
| update | PUT /admin/blog/series/{series} | 수정 |
| destroy | DELETE /admin/blog/series/{series} | 삭제 |
| reorderPosts | POST /admin/blog/series/{series}/reorder | 포스트 순서 변경 |

### 5.2 Traveler (Public) Controller

#### BlogController
```
src/app/Http/Controllers/Traveler/BlogController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| index | GET /blog | 블로그 목록 |
| show | GET /blog/{slug} | 포스트 상세 |
| category | GET /blog/category/{slug} | 카테고리별 목록 |
| series | GET /blog/series/{slug} | 시리즈별 목록 |
| tag | GET /blog/tag/{slug} | 태그별 목록 |

### 5.3 API Controllers

#### BlogCommentController
```
src/app/Http/Controllers/Api/BlogCommentController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| store | POST /api/blog/{post}/comments | 댓글 작성 |
| destroy | DELETE /api/blog/comments/{comment} | 댓글 삭제 |

#### BlogLikeController
```
src/app/Http/Controllers/Api/BlogLikeController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| toggle | POST /api/blog/{post}/like | 좋아요 토글 |

#### BlogImageController (Admin)
```
src/app/Http/Controllers/Api/Admin/BlogImageController.php
```
| Method | Route | Description |
|--------|-------|-------------|
| store | POST /api/admin/blog/upload-image | Tiptap 이미지 업로드 |

---

## 6. Form Requests

### StoreBlogPostRequest
```
src/app/Http/Requests/Admin/StoreBlogPostRequest.php
```
```php
public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
        'category_id' => 'nullable|exists:blog_categories,id',
        'series_id' => 'nullable|exists:blog_series,id',
        'series_order' => 'nullable|integer|min:1',
        'excerpt' => 'nullable|string|max:500',
        'content' => 'required|string',
        'thumbnail' => 'nullable|image|max:5120',
        'tags' => 'nullable|array',
        'tags.*' => 'string|max:50',
        'status' => 'required|in:draft,published',
        'meta_title' => 'nullable|string|max:60',
        'meta_description' => 'nullable|string|max:160',
    ];
}
```

### UpdateBlogPostRequest
```
src/app/Http/Requests/Admin/UpdateBlogPostRequest.php
```
- Same as StoreBlogPostRequest with `unique:blog_posts,slug,{id}` for slug

### StoreBlogCommentRequest
```
src/app/Http/Requests/StoreBlogCommentRequest.php
```
```php
public function rules(): array
{
    return [
        'content' => 'required|string|max:1000',
        'parent_id' => 'nullable|exists:blog_comments,id',
    ];
}
```

---

## 7. Views

### 7.1 Admin Views
```
src/resources/views/admin/blog/
├── posts/
│   ├── index.blade.php      # 포스트 목록
│   ├── create.blade.php     # 포스트 작성 (Tiptap 에디터)
│   ├── edit.blade.php       # 포스트 수정
│   └── show.blade.php       # 포스트 상세
├── categories/
│   ├── index.blade.php      # 카테고리 목록
│   ├── create.blade.php     # 카테고리 생성
│   └── edit.blade.php       # 카테고리 수정
└── series/
    ├── index.blade.php      # 시리즈 목록
    ├── create.blade.php     # 시리즈 생성
    ├── edit.blade.php       # 시리즈 수정
    └── show.blade.php       # 시리즈 상세 (포스트 순서 관리)
```

### 7.2 Public Views
```
src/resources/views/blog/
├── index.blade.php          # 블로그 메인 (목록)
├── show.blade.php           # 포스트 상세 + 댓글/좋아요
├── category.blade.php       # 카테고리별 목록
├── series.blade.php         # 시리즈별 목록
├── tag.blade.php            # 태그별 목록
└── partials/
    ├── post-card.blade.php  # 포스트 카드 컴포넌트
    ├── sidebar.blade.php    # 사이드바 (카테고리, 태그, 인기글)
    └── comments.blade.php   # 댓글 섹션
```

---

## 8. Routes

### 8.1 Admin Routes (web.php)
```php
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('blog')->name('admin.blog.')->group(function () {
        // Posts
        Route::resource('posts', Admin\BlogPostController::class);
        Route::patch('posts/{post}/publish', [Admin\BlogPostController::class, 'publish'])
            ->name('posts.publish');
        Route::patch('posts/{post}/unpublish', [Admin\BlogPostController::class, 'unpublish'])
            ->name('posts.unpublish');
        Route::patch('posts/{post}/archive', [Admin\BlogPostController::class, 'archive'])
            ->name('posts.archive');

        // Categories
        Route::resource('categories', Admin\BlogCategoryController::class)->except(['show']);
        Route::post('categories/reorder', [Admin\BlogCategoryController::class, 'reorder'])
            ->name('categories.reorder');

        // Series
        Route::resource('series', Admin\BlogSeriesController::class);
        Route::post('series/{series}/reorder', [Admin\BlogSeriesController::class, 'reorderPosts'])
            ->name('series.reorder-posts');
    });
});
```

### 8.2 Public Routes (web.php)
```php
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [Traveler\BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [Traveler\BlogController::class, 'category'])->name('category');
    Route::get('/series/{slug}', [Traveler\BlogController::class, 'series'])->name('series');
    Route::get('/tag/{slug}', [Traveler\BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [Traveler\BlogController::class, 'show'])->name('show');
});
```

### 8.3 API Routes (api.php)
```php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('blog/{post}/comments', [Api\BlogCommentController::class, 'store']);
    Route::delete('blog/comments/{comment}', [Api\BlogCommentController::class, 'destroy']);
    Route::post('blog/{post}/like', [Api\BlogLikeController::class, 'toggle']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/blog')->group(function () {
    Route::post('upload-image', [Api\Admin\BlogImageController::class, 'store']);
});
```

---

## 9. Tiptap Editor

### 9.1 NPM 패키지 설치
```bash
npm install @tiptap/core @tiptap/starter-kit @tiptap/extension-image @tiptap/extension-link @tiptap/extension-placeholder @tiptap/extension-youtube
```

### 9.2 에디터 컴포넌트
```
src/resources/js/components/TiptapEditor.js
```

**지원 기능:**
- Bold, Italic, Underline
- Heading (H1, H2, H3)
- Bullet List, Ordered List
- Blockquote
- Code Block
- Image (with upload)
- Link
- YouTube Embed

### 9.3 이미지 업로드 Flow
1. 사용자가 에디터에서 이미지 삽입 버튼 클릭
2. 파일 선택 다이얼로그
3. `/api/admin/blog/upload-image`로 업로드
4. 서버에서 ImageService로 처리 후 URL 반환
5. 에디터에 이미지 삽입

---

## 10. 구현 순서

| Phase | 작업 | 파일 수 |
|-------|------|---------|
| 1 | Migrations & Models | 13 |
| 2 | Enum (BlogPostStatus) | 1 |
| 3 | Service (BlogService) | 1 |
| 4 | Admin Controllers + Requests | 6 |
| 5 | Admin Views | 10 |
| 6 | Tiptap Editor + Image API | 2 |
| 7 | Public Controller + Views | 8 |
| 8 | API (Comments, Likes) | 2 |
| 9 | Routes 등록 | 1 |
| 10 | 테스트 및 마무리 | - |

---

## Reference Patterns

기존 코드베이스에서 참고할 파일:
- Model: `src/app/Models/Product.php`
- Service: `src/app/Services/ProductService.php`
- Admin Controller: `src/app/Http/Controllers/Admin/ProductController.php`
- Enum: `src/app/Enums/ProductStatus.php`
- Image: `src/app/Services/ImageService.php`