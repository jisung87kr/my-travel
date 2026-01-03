<?php

namespace Tests\Feature\Blog;

use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BlogApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->user = User::factory()->create();
        $this->user->assignRole('traveler');
    }

    public function test_authenticated_user_can_like_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/blog/{$post->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'liked' => true,
            ]);

        $this->assertDatabaseHas('blog_likes', [
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_authenticated_user_can_unlike_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'like_count' => 1,
        ]);
        BlogLike::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/blog/{$post->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'liked' => false,
            ]);

        $this->assertDatabaseMissing('blog_likes', [
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_like_post(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->postJson("/api/blog/{$post->id}/like");

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/blog/{$post->id}/comments", [
            'content' => '좋은 글이네요! 많이 배웠습니다.',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('blog_comments', [
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
            'content' => '좋은 글이네요! 많이 배웠습니다.',
        ]);
    }

    public function test_authenticated_user_can_create_reply(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);
        $parentComment = BlogComment::factory()->create([
            'blog_post_id' => $post->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/blog/{$post->id}/comments", [
            'content' => '답글입니다!',
            'parent_id' => $parentComment->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('blog_comments', [
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
            'parent_id' => $parentComment->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->postJson("/api/blog/{$post->id}/comments", [
            'content' => '댓글 테스트',
        ]);

        $response->assertStatus(401);
    }

    public function test_comment_content_is_required(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/blog/{$post->id}/comments", [
            'content' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    public function test_user_can_delete_own_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'comment_count' => 1,
        ]);
        $comment = BlogComment::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/blog/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertSoftDeleted('blog_comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_delete_other_users_comment(): void
    {
        $otherUser = User::factory()->create();
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
        ]);
        $comment = BlogComment::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $otherUser->id,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/blog/comments/{$comment->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $post = BlogPost::factory()->published()->create([
            'user_id' => $this->admin->id,
            'comment_count' => 1,
        ]);
        $comment = BlogComment::factory()->create([
            'blog_post_id' => $post->id,
            'user_id' => $this->user->id,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/blog/comments/{$comment->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('blog_comments', ['id' => $comment->id]);
    }

    public function test_admin_can_upload_editor_image(): void
    {
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library is not available');
        }

        Storage::fake('public');

        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/admin/blog/upload-image', [
            'image' => UploadedFile::fake()->image('test.jpg', 800, 600),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['url']);
    }

    public function test_non_admin_cannot_upload_editor_image(): void
    {
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library is not available');
        }

        Storage::fake('public');

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/admin/blog/upload-image', [
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertStatus(403);
    }
}
