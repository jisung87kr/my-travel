<?php

namespace Tests\Feature\Blog;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBlogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $traveler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->traveler = User::factory()->create();
        $this->traveler->assignRole('traveler');
    }

    // ===== Post Tests =====

    public function test_admin_can_view_posts_list(): void
    {
        BlogPost::factory()->count(5)->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/blog/posts');

        $response->assertStatus(200)
            ->assertViewIs('admin.blog.posts.index');
    }

    public function test_admin_can_view_create_post_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/blog/posts/create');

        $response->assertStatus(200)
            ->assertViewIs('admin.blog.posts.create');
    }

    public function test_admin_can_create_post(): void
    {
        $category = BlogCategory::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/blog/posts', [
            'title' => '새 블로그 글',
            'content' => '블로그 글 내용입니다. 충분히 긴 내용이어야 합니다.',
            'excerpt' => '요약입니다.',
            'category_id' => $category->id,
            'status' => BlogPostStatus::DRAFT->value,
            'tags' => ['여행', '서울', '맛집'],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('blog_posts', [
            'user_id' => $this->admin->id,
            'title' => '새 블로그 글',
            'status' => BlogPostStatus::DRAFT->value,
        ]);
    }

    public function test_admin_can_create_post_with_thumbnail(): void
    {
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library is not available');
        }

        Storage::fake('public');
        $category = BlogCategory::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/blog/posts', [
            'title' => '썸네일이 있는 글',
            'content' => '블로그 글 내용입니다.',
            'category_id' => $category->id,
            'status' => BlogPostStatus::DRAFT->value,
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg', 800, 600),
        ]);

        $response->assertRedirect();

        $post = BlogPost::where('title', '썸네일이 있는 글')->first();
        $this->assertNotNull($post->thumbnail_path);
    }

    public function test_admin_can_view_edit_post_form(): void
    {
        $post = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/blog/posts/{$post->id}/edit");

        $response->assertStatus(200)
            ->assertViewIs('admin.blog.posts.edit');
    }

    public function test_admin_can_update_post(): void
    {
        $post = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
            'title' => 'Original Title',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/blog/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => $post->content,
            'status' => $post->status->value,
        ]);

        $response->assertRedirect();

        $this->assertEquals('Updated Title', $post->fresh()->title);
    }

    public function test_admin_can_delete_post(): void
    {
        $post = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/blog/posts/{$post->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('blog_posts', ['id' => $post->id]);
    }

    public function test_admin_can_publish_post(): void
    {
        $post = BlogPost::factory()->draft()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/blog/posts/{$post->id}/publish");

        $response->assertRedirect();
        $this->assertEquals(BlogPostStatus::PUBLISHED, $post->fresh()->status);
    }

    public function test_admin_can_unpublish_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/blog/posts/{$post->id}/unpublish");

        $response->assertRedirect();
        $this->assertEquals(BlogPostStatus::DRAFT, $post->fresh()->status);
    }

    public function test_admin_can_archive_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/blog/posts/{$post->id}/archive");

        $response->assertRedirect();
        $this->assertEquals(BlogPostStatus::ARCHIVED, $post->fresh()->status);
    }

    public function test_traveler_cannot_access_admin_blog(): void
    {
        $response = $this->actingAs($this->traveler)->get('/admin/blog/posts');

        $response->assertStatus(403);
    }

    // ===== Category Tests =====

    public function test_admin_can_view_categories_list(): void
    {
        BlogCategory::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get('/admin/blog/categories');

        $response->assertStatus(200)
            ->assertViewIs('admin.blog.categories.index');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blog/categories', [
            'name' => '새 카테고리',
            'description' => '카테고리 설명입니다.',
            'is_active' => true,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('blog_categories', [
            'name' => '새 카테고리',
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $category = BlogCategory::factory()->create([
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/blog/categories/{$category->id}", [
            'name' => 'Updated Name',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Updated Name', $category->fresh()->name);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = BlogCategory::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/blog/categories/{$category->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('blog_categories', ['id' => $category->id]);
    }

    public function test_admin_can_reorder_categories(): void
    {
        $cat1 = BlogCategory::factory()->create(['sort_order' => 0]);
        $cat2 = BlogCategory::factory()->create(['sort_order' => 1]);
        $cat3 = BlogCategory::factory()->create(['sort_order' => 2]);

        $response = $this->actingAs($this->admin)->post('/admin/blog/categories/reorder', [
            'order' => [$cat3->id, $cat1->id, $cat2->id],
        ]);

        $response->assertRedirect();

        $this->assertEquals(0, $cat3->fresh()->sort_order);
        $this->assertEquals(1, $cat1->fresh()->sort_order);
        $this->assertEquals(2, $cat2->fresh()->sort_order);
    }

    // ===== Series Tests =====

    public function test_admin_can_view_series_list(): void
    {
        BlogSeries::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get('/admin/blog/series');

        $response->assertStatus(200)
            ->assertViewIs('admin.blog.series.index');
    }

    public function test_admin_can_create_series(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blog/series', [
            'title' => '새 시리즈',
            'description' => '시리즈 설명입니다.',
            'is_active' => true,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('blog_series', [
            'title' => '새 시리즈',
        ]);
    }

    public function test_admin_can_update_series(): void
    {
        $series = BlogSeries::factory()->create([
            'title' => 'Original Title',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/blog/series/{$series->id}", [
            'title' => 'Updated Title',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Updated Title', $series->fresh()->title);
    }

    public function test_admin_can_delete_series(): void
    {
        $series = BlogSeries::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/blog/series/{$series->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('blog_series', ['id' => $series->id]);
    }

    public function test_admin_can_view_series_detail(): void
    {
        $series = BlogSeries::factory()->create();
        BlogPost::factory()->count(3)->create([
            'user_id' => $this->admin->id,
            'series_id' => $series->id,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/blog/series/{$series->id}");

        $response->assertStatus(200)
            ->assertViewIs('admin.blog.series.show');
    }

    public function test_admin_can_reorder_series_posts(): void
    {
        $series = BlogSeries::factory()->create();
        $post1 = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
            'series_id' => $series->id,
            'series_order' => 1,
        ]);
        $post2 = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
            'series_id' => $series->id,
            'series_order' => 2,
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/blog/series/{$series->id}/reorder-posts", [
            'order' => [$post2->id, $post1->id],
        ]);

        $response->assertRedirect();

        $this->assertEquals(1, $post2->fresh()->series_order);
        $this->assertEquals(2, $post1->fresh()->series_order);
    }

    // ===== Validation Tests =====

    public function test_post_title_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blog/posts', [
            'title' => '',
            'content' => '내용입니다.',
            'status' => BlogPostStatus::DRAFT->value,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_post_content_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blog/posts', [
            'title' => '제목입니다',
            'content' => '',
            'status' => BlogPostStatus::DRAFT->value,
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_category_name_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blog/categories', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_series_title_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blog/series', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');
    }
}
