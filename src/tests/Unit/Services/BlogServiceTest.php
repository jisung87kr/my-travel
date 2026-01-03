<?php

namespace Tests\Unit\Services;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\BlogTag;
use App\Models\User;
use App\Services\BlogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogServiceTest extends TestCase
{
    use RefreshDatabase;

    private BlogService $service;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->service = app(BlogService::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_create_blog_post(): void
    {
        $category = BlogCategory::factory()->create();

        $data = [
            'title' => '테스트 블로그 글',
            'content' => '이것은 테스트 블로그 글의 내용입니다.',
            'excerpt' => '테스트 요약',
            'category_id' => $category->id,
            'status' => BlogPostStatus::DRAFT->value,
        ];

        $post = $this->service->create($data, $this->admin);

        $this->assertDatabaseHas('blog_posts', [
            'id' => $post->id,
            'user_id' => $this->admin->id,
            'title' => '테스트 블로그 글',
            'status' => BlogPostStatus::DRAFT->value,
        ]);
    }

    public function test_create_blog_post_with_tags(): void
    {
        $data = [
            'title' => '태그가 있는 블로그 글',
            'content' => '내용입니다.',
            'tags' => ['여행', '서울', '맛집'],
        ];

        $post = $this->service->create($data, $this->admin);
        $post->load('tags');

        $this->assertCount(3, $post->tags);
        $this->assertDatabaseHas('blog_tags', ['name' => '여행']);
        $this->assertDatabaseHas('blog_tags', ['name' => '서울']);
        $this->assertDatabaseHas('blog_tags', ['name' => '맛집']);
    }

    public function test_update_blog_post(): void
    {
        $post = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
            'title' => 'Original Title',
        ]);

        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ];

        $updatedPost = $this->service->update($post, $data);

        $this->assertEquals('Updated Title', $updatedPost->title);
        $this->assertEquals('Updated content', $updatedPost->content);
    }

    public function test_delete_blog_post(): void
    {
        $post = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $result = $this->service->delete($post);

        $this->assertTrue($result);
        $this->assertSoftDeleted('blog_posts', ['id' => $post->id]);
    }

    public function test_publish_blog_post(): void
    {
        $post = BlogPost::factory()->draft()->create([
            'user_id' => $this->admin->id,
        ]);

        $publishedPost = $this->service->publish($post);

        $this->assertEquals(BlogPostStatus::PUBLISHED, $publishedPost->status);
        $this->assertNotNull($publishedPost->published_at);
    }

    public function test_unpublish_blog_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        $unpublishedPost = $this->service->unpublish($post);

        $this->assertEquals(BlogPostStatus::DRAFT, $unpublishedPost->status);
    }

    public function test_archive_blog_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        $archivedPost = $this->service->archive($post);

        $this->assertEquals(BlogPostStatus::ARCHIVED, $archivedPost->status);
    }

    public function test_sync_tags(): void
    {
        $post = BlogPost::factory()->create([
            'user_id' => $this->admin->id,
        ]);
        $existingTag = BlogTag::factory()->create(['name' => 'existing']);

        $this->service->syncTags($post, ['existing', 'new-tag', 'another']);

        $post->load('tags');
        $this->assertCount(3, $post->tags);
        $this->assertDatabaseHas('blog_tags', ['name' => 'new-tag']);
    }

    public function test_record_view_increments_count(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'view_count' => 0,
        ]);

        $this->service->recordView($post, null, '127.0.0.1');

        $this->assertEquals(1, $post->fresh()->view_count);
    }

    public function test_record_view_respects_session(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'view_count' => 0,
        ]);

        // First view
        $this->service->recordView($post, null, '127.0.0.1');

        // Second view from same session (should not count)
        $this->service->recordView($post, null, '127.0.0.1');

        $this->assertEquals(1, $post->fresh()->view_count);
    }

    public function test_toggle_like_adds_like(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'like_count' => 0,
        ]);
        $user = User::factory()->create();

        $result = $this->service->toggleLike($post, $user);

        $this->assertTrue($result['liked']);
        $this->assertEquals(1, $result['like_count']);
        $this->assertDatabaseHas('blog_likes', [
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_toggle_like_removes_like(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'like_count' => 1,
        ]);
        $user = User::factory()->create();
        BlogLike::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $result = $this->service->toggleLike($post, $user);

        $this->assertFalse($result['liked']);
        $this->assertEquals(0, $result['like_count']);
        $this->assertDatabaseMissing('blog_likes', [
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_create_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'comment_count' => 0,
        ]);
        $user = User::factory()->create();

        $comment = $this->service->createComment($post, $user, ['content' => '좋은 글이네요!']);

        $this->assertDatabaseHas('blog_comments', [
            'id' => $comment->id,
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
            'content' => '좋은 글이네요!',
        ]);
        $this->assertEquals(1, $post->fresh()->comment_count);
    }

    public function test_create_reply_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);
        $user = User::factory()->create();
        $parentComment = BlogComment::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $reply = $this->service->createComment($post, $user, [
            'content' => '답글입니다.',
            'parent_id' => $parentComment->id,
        ]);

        $this->assertEquals($parentComment->id, $reply->parent_id);
    }

    public function test_delete_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'comment_count' => 1,
        ]);
        $comment = BlogComment::factory()->create([
            'blog_post_id' => $post->id,
        ]);

        $result = $this->service->deleteComment($comment);

        $this->assertTrue($result);
        $this->assertSoftDeleted('blog_comments', ['id' => $comment->id]);
        $this->assertEquals(0, $post->fresh()->comment_count);
    }

    public function test_search_by_category(): void
    {
        $category = BlogCategory::factory()->create();
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->admin->id,
            'category_id' => $category->id,
        ]);
        BlogPost::factory()->published()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);

        $results = $this->service->search(['category' => $category->id]);

        $this->assertEquals(3, $results->total());
    }

    public function test_search_by_tag(): void
    {
        $tag = BlogTag::factory()->create();
        $posts = BlogPost::factory()->published()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);
        foreach ($posts as $post) {
            $post->tags()->attach($tag->id);
        }
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->admin->id,
        ]);

        $results = $this->service->search(['tag' => $tag->id]);

        $this->assertEquals(2, $results->total());
    }

    public function test_search_by_keyword(): void
    {
        BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'title' => '제주도 여행 가이드',
        ]);
        BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'title' => '서울 맛집 투어',
        ]);

        $results = $this->service->search(['keyword' => '제주도']);

        $this->assertEquals(1, $results->total());
    }

    public function test_get_related_posts(): void
    {
        $category = BlogCategory::factory()->create();
        $mainPost = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'category_id' => $category->id,
        ]);
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->admin->id,
            'category_id' => $category->id,
        ]);
        BlogPost::factory()->published()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);

        $relatedPosts = $this->service->getRelatedPosts($mainPost, 5);

        $this->assertCount(3, $relatedPosts);
        $this->assertFalse($relatedPosts->contains($mainPost));
    }

    public function test_get_popular_posts(): void
    {
        BlogPost::factory()->published()->withStats(100, 10, 5)->create([
            'user_id' => $this->admin->id,
        ]);
        BlogPost::factory()->published()->withStats(50, 5, 2)->create([
            'user_id' => $this->admin->id,
        ]);
        BlogPost::factory()->published()->withStats(200, 20, 10)->create([
            'user_id' => $this->admin->id,
        ]);

        $popularPosts = $this->service->getPopularPosts(3);

        $this->assertCount(3, $popularPosts);
        $this->assertEquals(200, $popularPosts->first()->view_count);
    }

    public function test_get_recent_posts(): void
    {
        BlogPost::factory()->published()->count(5)->create([
            'user_id' => $this->admin->id,
        ]);
        BlogPost::factory()->draft()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);

        $recentPosts = $this->service->getRecentPosts(10);

        $this->assertCount(5, $recentPosts);
    }

    public function test_get_active_categories(): void
    {
        BlogCategory::factory()->count(3)->create();
        BlogCategory::factory()->inactive()->count(2)->create();

        $categories = $this->service->getActiveCategories();

        $this->assertCount(3, $categories);
    }

    public function test_get_active_series(): void
    {
        BlogSeries::factory()->withPostCount(1)->count(3)->create();
        BlogSeries::factory()->inactive()->count(2)->create();

        $series = $this->service->getActiveSeries();

        $this->assertCount(3, $series);
    }

    public function test_get_popular_tags(): void
    {
        BlogTag::factory()->withPostCount(10)->create();
        BlogTag::factory()->withPostCount(5)->create();
        BlogTag::factory()->withPostCount(20)->create();

        $tags = $this->service->getPopularTags(10);

        $this->assertCount(3, $tags);
        $this->assertEquals(20, $tags->first()->post_count);
    }

    public function test_reorder_series_posts(): void
    {
        $series = BlogSeries::factory()->create();
        $post1 = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'series_id' => $series->id,
            'series_order' => 1,
        ]);
        $post2 = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'series_id' => $series->id,
            'series_order' => 2,
        ]);
        $post3 = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'series_id' => $series->id,
            'series_order' => 3,
        ]);

        $this->service->reorderSeriesPosts($series, [$post3->id, $post1->id, $post2->id]);

        $this->assertEquals(1, $post3->fresh()->series_order);
        $this->assertEquals(2, $post1->fresh()->series_order);
        $this->assertEquals(3, $post2->fresh()->series_order);
    }

    public function test_upload_editor_image(): void
    {
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library is not available');
        }

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $url = $this->service->uploadEditorImage($file);

        $this->assertStringContainsString('/storage/blog/editor/', $url);
    }
}
