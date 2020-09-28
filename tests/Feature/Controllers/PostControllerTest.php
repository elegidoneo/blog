<?php

namespace Tests\Feature\Controllers;

use App\Lib\Uploads\PostImageUpload;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        Sanctum::actingAs(
            factory(User::class)->make()
        );
        $response = $this->getJson("/api/post");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        Sanctum::actingAs(
            factory(User::class)->make()
        );
        $response = $this->getJson("/api/post?pages=20");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
        $this->assertCount(20, $response->json('data'));
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        Sanctum::actingAs(
            factory(User::class)->create()
        );
        $response = $this->getJson("/api/post?pages=20&title=dolor");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
        $response->assertSeeText("dolor");
    }

    /**
     * @test
     * @testdox
     */
    public function caseFour()
    {
        Notification::fake();
        Sanctum::actingAs(
            $user = factory(User::class)->create()
        );
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $response = $this->postJson("/api/post", [
            "title" => "test feature",
            "body" => "Hello World",
            "image" => $file,
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("posts", [
            "id" => $response->json("data.id"),
            "title" => "test feature",
            "user_id" => $user->id,
        ]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseFive()
    {
        Notification::fake();
        Sanctum::actingAs(
            factory(User::class)->make()
        );
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $response = $this->postJson("/api/post", [
            "title" => 123,
            "body" => "Hello World",
            "image" => $file,
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors("title");
        $response->assertJsonFragment(["title" => ["The title must be a string.", "The title must be at least 8 characters."]]);
        Notification::assertNothingSent();
    }

    /**
     * @test
     * @testdox
     */
    public function caseSix()
    {
        Sanctum::actingAs(
            factory(User::class)->create()
        );
        $post = factory(Post::class)->create();
        $response = $this->getJson("/api/post/" . $post->id);
        $response->assertSuccessful();
        $response->assertJsonFragment(["title" => $post->title]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseSeven()
    {
        Notification::fake();
        Sanctum::actingAs(
            factory(User::class)->make()
        );
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        \Mockery::mock(Request::class);
        $this->mock(PostImageUpload::class, function (MockInterface $mock) {
            $mock->shouldReceive("upload")->andReturnFalse();
        });
        $response = $this->postJson("/api/post", [
            "title" => "test feature",
            "body" => "Hello World",
            "image" => $file,
        ]);
        $response->assertStatus(500);
        $response->assertJsonFragment(["error" => __("post.file.error")]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseEight()
    {
        Notification::fake();
        Sanctum::actingAs(
            $user = factory(User::class)->create()
        );
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $post = factory(Post::class)->create([
            "user_id" => $user->id
        ]);
        $response = $this->patchJson("/api/post/" . $post->id, [
            "title" => "test feature",
            "body" => "Hello World",
            "image" => $file,
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("posts", [
            "id" => $post->id,
            "title" => "test feature",
            "image_url" => "posts/" . $file->getClientOriginalName()
        ]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseNine()
    {
        Notification::fake();
        Sanctum::actingAs(
            $user = factory(User::class)->create()
        );
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $post = factory(Post::class)->create([
            "user_id" => factory(User::class)->create()->id
        ]);
        $response = $this->patchJson("/api/post/" . $post->id, [
            "title" => "test feature",
            "body" => "Hello World",
            "image" => $file,
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(["error" => __("post.unauthorized")]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseTen()
    {
        Notification::fake();
        Sanctum::actingAs(
            factory(User::class)->make()
        );
        $post = factory(Post::class)->create();
        $response = $this->deleteJson("/api/post/" . $post->id);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(["error" => __("post.unauthorized")]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseEleven()
    {
        Notification::fake();
        $user = factory(User::class)->states(["administrator", "active"])->create();
        $user->createToken("test");
        Sanctum::actingAs($user);
        $post = factory(Post::class)->create([
            "user_id" => $user->id,
        ]);
        $response = $this->deleteJson("/api/post/" . $post->id);
        $response->assertSuccessful();
        $response->assertJsonFragment(["message" => __("post.delete")]);
        $this->assertSoftDeleted("posts", [
            "id" => $post->id,
        ]);
    }
}
