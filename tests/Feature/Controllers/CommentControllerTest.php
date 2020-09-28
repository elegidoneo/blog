<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        Sanctum::actingAs(
            factory(User::class)->make()
        );
        $response = $this->getJson("/api/comment");
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
        $response = $this->getJson("/api/comment?pages=20");
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
            $user = factory(User::class)->create()
        );
        factory(Comment::class)->create([
            "user_id" => $user->id
        ]);
        $response = $this->getJson("/api/comment?pages=20&user_id=".$user->id);
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
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
        $response = $this->postJson("/api/comment", [
            "comment" => "Hello World",
            "post_id" => factory(Post::class)->create()->id,
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("comments", [
            "id" => $response->json("data.id"),
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
        $response = $this->postJson("/api/comment", [
            "comment" => "Hello World",
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors("post_id");
        $response->assertJsonFragment(["post_id" => ["The post id field is required."]]);
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
        $comment = factory(Comment::class)->create();
        $response = $this->getJson("/api/comment/" . $comment->id);
        $response->assertSuccessful();
        $response->assertJsonFragment(["post_id" => $comment->post_id]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseSeven()
    {
        Notification::fake();
        Sanctum::actingAs(
            $user = factory(User::class)->create()
        );
        $comment = factory(Comment::class)->create();
        $response = $this->patchJson("/api/comment/" . $comment->id, [
            "comment" => "test"
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment(["comment" => "test"]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseEight()
    {
        Sanctum::actingAs(
            $user = factory(User::class)->create()
        );
        $comment = factory(Comment::class)->create();
        $response = $this->deleteJson("/api/comment/" . $comment->id);
        $response->assertJsonFragment(["error" => __("comment.unauthorized")]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseNine()
    {
        Sanctum::actingAs(
            $user = factory(User::class)->states("administrator")->create()
        );
        $comment = factory(Comment::class)->create([
            "user_id" => $user->id
        ]);
        $response = $this->deleteJson("/api/comment/" . $comment->id);
        $response->assertJsonFragment(["message" => __("comment.delete")]);
    }
}
