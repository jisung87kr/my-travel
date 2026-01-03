<?php

namespace Tests\Unit\Models;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_post_can_be_created(): void
    {
        $post = BlogPost::factory()->create();

        $this->assertDatabaseHas('blog_posts', [
            'id' => $post->id,
        ]);
    }

    public function test_status_is_cast_to_enum(): void
    {
        $post = BlogPost::factory()->published()->create();

        $this->assertInstanceOf(BlogPostStatus::class, $post->status);
        $this->assertEquals(BlogPostStatus::PUBLISHED, $post->status);
    }

    public function test_blog_post_belongs_to_author(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->author);
        $this->assertEquals($user->id, $post->author->id);
    }

    public function test_blog_post_belongs_to_category(): void
    {
        $category = BlogCategory::factory()->create();
        $post = BlogPost::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(BlogCategory::class, $post->category);
        $this->assertEquals($category->id, $post->category->id);
    }

    public function test_blog_post_belongs_to_series(): void
    {
        $series = BlogSeries::factory()->create();
        $post = BlogPost::factory()->create(['series_id' => $series->id]);

        $this->assertInstanceOf(BlogSeries::class, $post->series);
        $this->assertEquals($series->id, $post->series->id);
    }

    public function test_blog_post_has_many_tags(): void
    {
        $post = BlogPost::factory()->create();
        $tags = BlogTag::factory()->count(3)->create();

        $post->tags()->attach($tags->pluck('id'));

        $this->assertCount(3, $post->tags);
    }

    public function test_blog_post_has_many_comments(): void
    {
        $post = BlogPost::factory()->create();
        BlogComment::factory()->count(5)->create(['blog_post_id' => $post->id]);

        $this->assertCount(5, $post->comments);
    }

    public function test_blog_post_has_many_likes(): void
    {
        $post = BlogPost::factory()->create();
        BlogLike::factory()->count(3)->create(['blog_post_id' => $post->id]);

        $this->assertCount(3, $post->likes);
    }

    public function test_is_liked_by_returns_true_for_liked_user(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::factory()->create();
        BlogLike::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($post->isLikedBy($user));
    }

    public function test_is_liked_by_returns_false_for_unlike_user(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::factory()->create();

        $this->assertFalse($post->isLikedBy($user));
    }

    public function test_scope_published(): void
    {
        BlogPost::factory()->published()->count(3)->create();
        BlogPost::factory()->draft()->count(2)->create();

        $publishedPosts = BlogPost::published()->get();

        $this->assertCount(3, $publishedPosts);
    }

    public function test_scope_draft(): void
    {
        BlogPost::factory()->draft()->count(2)->create();
        BlogPost::factory()->published()->count(3)->create();

        $draftPosts = BlogPost::draft()->get();

        $this->assertCount(2, $draftPosts);
    }

    public function test_scope_archived(): void
    {
        BlogPost::factory()->archived()->count(2)->create();
        BlogPost::factory()->published()->count(3)->create();

        $archivedPosts = BlogPost::archived()->get();

        $this->assertCount(2, $archivedPosts);
    }

    public function test_scope_by_category(): void
    {
        $category = BlogCategory::factory()->create();
        BlogPost::factory()->count(3)->create(['category_id' => $category->id]);
        BlogPost::factory()->count(2)->create();

        $posts = BlogPost::byCategory($category->id)->get();

        $this->assertCount(3, $posts);
    }

    public function test_scope_by_series(): void
    {
        $series = BlogSeries::factory()->create();
        BlogPost::factory()->count(3)->create(['series_id' => $series->id]);
        BlogPost::factory()->count(2)->create();

        $posts = BlogPost::bySeries($series->id)->get();

        $this->assertCount(3, $posts);
    }

    public function test_scope_by_tag(): void
    {
        $tag = BlogTag::factory()->create();
        $posts = BlogPost::factory()->count(2)->create();
        foreach ($posts as $post) {
            $post->tags()->attach($tag->id);
        }
        BlogPost::factory()->count(3)->create();

        $taggedPosts = BlogPost::byTag($tag->id)->get();

        $this->assertCount(2, $taggedPosts);
    }

    public function test_get_previous_in_series(): void
    {
        $series = BlogSeries::factory()->create();
        $post1 = BlogPost::factory()->published()->create([
            'series_id' => $series->id,
            'series_order' => 1,
        ]);
        $post2 = BlogPost::factory()->published()->create([
            'series_id' => $series->id,
            'series_order' => 2,
        ]);

        $previous = $post2->getPreviousInSeries();

        $this->assertEquals($post1->id, $previous->id);
    }

    public function test_get_previous_in_series_returns_null_for_first(): void
    {
        $series = BlogSeries::factory()->create();
        $post = BlogPost::factory()->published()->create([
            'series_id' => $series->id,
            'series_order' => 1,
        ]);

        $previous = $post->getPreviousInSeries();

        $this->assertNull($previous);
    }

    public function test_get_next_in_series(): void
    {
        $series = BlogSeries::factory()->create();
        $post1 = BlogPost::factory()->published()->create([
            'series_id' => $series->id,
            'series_order' => 1,
        ]);
        $post2 = BlogPost::factory()->published()->create([
            'series_id' => $series->id,
            'series_order' => 2,
        ]);

        $next = $post1->getNextInSeries();

        $this->assertEquals($post2->id, $next->id);
    }

    public function test_get_next_in_series_returns_null_for_last(): void
    {
        $series = BlogSeries::factory()->create();
        $post = BlogPost::factory()->published()->create([
            'series_id' => $series->id,
            'series_order' => 1,
        ]);

        $next = $post->getNextInSeries();

        $this->assertNull($next);
    }

    public function test_reading_time_accessor(): void
    {
        $post = BlogPost::factory()->create([
            'content' => str_repeat('word ', 500), // 500 words
        ]);

        // Assuming 200 words per minute, 500 words should be ~3 minutes
        $this->assertGreaterThanOrEqual(2, $post->reading_time);
        $this->assertLessThanOrEqual(3, $post->reading_time);
    }

    public function test_thumbnail_url_accessor(): void
    {
        $post = BlogPost::factory()->create([
            'thumbnail_path' => 'blog/thumbnails/test.jpg',
        ]);

        $this->assertStringContainsString('test.jpg', $post->thumbnail_url);
    }

    public function test_thumbnail_url_returns_null_when_no_path(): void
    {
        $post = BlogPost::factory()->create([
            'thumbnail_path' => null,
        ]);

        $this->assertNull($post->thumbnail_url);
    }

    public function test_visible_comments_relation(): void
    {
        $post = BlogPost::factory()->create();
        BlogComment::factory()->count(3)->create([
            'blog_post_id' => $post->id,
            'is_visible' => true,
            'parent_id' => null,
        ]);
        BlogComment::factory()->hidden()->count(2)->create([
            'blog_post_id' => $post->id,
            'parent_id' => null,
        ]);

        $this->assertCount(3, $post->visibleComments);
    }

    public function test_soft_deletes(): void
    {
        $post = BlogPost::factory()->create();
        $postId = $post->id;

        $post->delete();

        $this->assertSoftDeleted('blog_posts', ['id' => $postId]);
    }
}
