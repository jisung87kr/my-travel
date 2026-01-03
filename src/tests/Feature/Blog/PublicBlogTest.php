<?php

namespace Tests\Feature\Blog;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBlogTest extends TestCase
{
    use RefreshDatabase;

    private User $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->author = User::factory()->create();
        $this->author->assignRole('admin');
    }

    public function test_can_view_blog_index(): void
    {
        BlogPost::factory()->published()->count(5)->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200)
            ->assertViewIs('blog.index');
    }

    public function test_blog_index_only_shows_published_posts(): void
    {
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->author->id,
        ]);
        BlogPost::factory()->draft()->count(2)->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200)
            ->assertViewHas('posts', function ($posts) {
                return $posts->total() === 3;
            });
    }

    public function test_can_view_single_blog_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
            'title' => '테스트 블로그 글',
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200)
            ->assertViewIs('blog.show')
            ->assertSee('테스트 블로그 글');
    }

    public function test_viewing_post_increments_view_count(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
            'view_count' => 0,
        ]);

        $this->get("/blog/{$post->slug}");

        $this->assertEquals(1, $post->fresh()->view_count);
    }

    public function test_cannot_view_draft_post(): void
    {
        $post = BlogPost::factory()->draft()->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(404);
    }

    public function test_cannot_view_archived_post(): void
    {
        $post = BlogPost::factory()->archived()->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(404);
    }

    public function test_can_view_posts_by_category(): void
    {
        $category = BlogCategory::factory()->create(['name' => '여행 가이드']);
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->author->id,
            'category_id' => $category->id,
        ]);
        BlogPost::factory()->published()->count(2)->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get("/blog/category/{$category->slug}");

        $response->assertStatus(200)
            ->assertViewIs('blog.category')
            ->assertSee('여행 가이드')
            ->assertViewHas('posts', function ($posts) {
                return $posts->total() === 3;
            });
    }

    public function test_can_view_posts_by_series(): void
    {
        $series = BlogSeries::factory()->create(['title' => '제주도 완전정복']);
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->author->id,
            'series_id' => $series->id,
        ]);

        $response = $this->get("/blog/series/{$series->slug}");

        $response->assertStatus(200)
            ->assertViewIs('blog.series')
            ->assertSee('제주도 완전정복');
    }

    public function test_can_view_posts_by_tag(): void
    {
        $tag = BlogTag::factory()->create(['name' => '맛집']);
        $posts = BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->author->id,
        ]);
        foreach ($posts as $post) {
            $post->tags()->attach($tag->id);
        }

        $response = $this->get("/blog/tag/{$tag->slug}");

        $response->assertStatus(200)
            ->assertViewIs('blog.tag')
            ->assertSee('#맛집')
            ->assertViewHas('posts', function ($posts) {
                return $posts->total() === 3;
            });
    }

    public function test_blog_index_shows_categories_in_sidebar(): void
    {
        BlogCategory::factory()->count(3)->create();

        $response = $this->get('/blog');

        $response->assertStatus(200)
            ->assertViewHas('categories');
    }

    public function test_blog_index_shows_popular_tags_in_sidebar(): void
    {
        $tags = BlogTag::factory()->count(5)->create();

        $response = $this->get('/blog');

        $response->assertStatus(200)
            ->assertViewHas('popularTags');
    }

    public function test_blog_post_shows_related_posts(): void
    {
        $category = BlogCategory::factory()->create();
        $mainPost = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
            'category_id' => $category->id,
        ]);
        BlogPost::factory()->published()->count(3)->create([
            'user_id' => $this->author->id,
            'category_id' => $category->id,
        ]);

        $response = $this->get("/blog/{$mainPost->slug}");

        $response->assertStatus(200)
            ->assertViewHas('relatedPosts');
    }

    public function test_blog_post_shows_series_navigation(): void
    {
        $series = BlogSeries::factory()->create();
        $post1 = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
            'series_id' => $series->id,
            'series_order' => 1,
            'title' => '첫 번째 글',
        ]);
        $post2 = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
            'series_id' => $series->id,
            'series_order' => 2,
            'title' => '두 번째 글',
        ]);
        $post3 = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
            'series_id' => $series->id,
            'series_order' => 3,
            'title' => '세 번째 글',
        ]);

        $response = $this->get("/blog/{$post2->slug}");

        $response->assertStatus(200)
            ->assertViewHas('previousPost')
            ->assertViewHas('nextPost');
    }

    public function test_blog_post_shows_author_info(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200)
            ->assertSee($this->author->name);
    }

    public function test_blog_post_shows_comments(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200)
            ->assertSee('댓글');
    }

    public function test_blog_post_shows_like_button(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200);
    }

    public function test_blog_index_is_paginated(): void
    {
        BlogPost::factory()->published()->count(25)->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200)
            ->assertViewHas('posts', function ($posts) {
                return $posts->hasPages();
            });
    }

    public function test_can_access_second_page(): void
    {
        BlogPost::factory()->published()->count(25)->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->get('/blog?page=2');

        $response->assertStatus(200);
    }

    public function test_inactive_category_returns_404(): void
    {
        $category = BlogCategory::factory()->inactive()->create();

        $response = $this->get("/blog/category/{$category->slug}");

        $response->assertStatus(404);
    }

    public function test_inactive_series_returns_404(): void
    {
        $series = BlogSeries::factory()->inactive()->create();

        $response = $this->get("/blog/series/{$series->slug}");

        $response->assertStatus(404);
    }

    public function test_nonexistent_slug_returns_404(): void
    {
        $response = $this->get('/blog/nonexistent-slug');

        $response->assertStatus(404);
    }
}
