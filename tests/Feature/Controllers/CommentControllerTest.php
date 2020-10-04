<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox Check that all comments are listed without pager
     */
    public function caseOne()
    {
        Passport::actingAs(
            factory(User::class)->make()
        );
        $response = $this->getJson("/api/comment");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
    }

    /**
     * @test
     * @testdox Check that paginated comments are listed
     */
    public function caseTwo()
    {
        Passport::actingAs(
            factory(User::class)->make()
        );
        $response = $this->getJson("/api/comment?pages=20");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
        $this->assertCount(20, $response->json('data'));
    }

    /**
     * @test
     * @testdox Filters are checked to see if they work
     */
    public function caseThree()
    {
        Passport::actingAs(
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
     * @testdox Check that you save the comment successfully
     */
    public function caseFour()
    {
        Notification::fake();
        Passport::actingAs(
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
     * @testdox Check when not all the information is sent to save the error comment
     */
    public function caseFive()
    {
        Notification::fake();
        Passport::actingAs(
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
     * @testdox Check that it shows the comment information
     */
    public function caseSix()
    {
        Passport::actingAs(
            factory(User::class)->create()
        );
        $comment = factory(Comment::class)->create();
        $response = $this->getJson("/api/comment/" . $comment->id);
        $response->assertSuccessful();
        $response->assertJsonFragment(["post_id" => $comment->post_id]);
    }

    /**
     * @test
     * @testdox Check that the comment can be edited
     */
    public function caseSeven()
    {
        Notification::fake();
        Passport::actingAs(
            $user = factory(User::class)->create()
        );
        $comment = factory(Comment::class)->create([
            "user_id" => $user->id
        ]);
        $response = $this->patchJson("/api/comment/" . $comment->id, [
            "comment" => "test"
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment(["comment" => "test"]);
    }

    /**
     * @test
     * @testdox Check that other users cannot delete the comment
     */
    public function caseEight()
    {
        Passport::actingAs(
            $user = factory(User::class)->create()
        );
        $comment = factory(Comment::class)->create();
        $response = $this->deleteJson("/api/comment/" . $comment->id);
        $response->assertJsonFragment(["error" => __("comment.unauthorized")]);
    }

    /**
     * @test
     * @testdox Check that only administrators can delete the comment
     */
    public function caseNine()
    {
        Passport::actingAs(
            $user = factory(User::class)->states("administrator")->create()
        );
        $comment = factory(Comment::class)->create([
            "user_id" => $user->id
        ]);
        $response = $this->deleteJson("/api/comment/" . $comment->id);
        $response->assertJsonFragment(["message" => __("comment.delete")]);
    }

    /**
     * @test
     * @testdox Check that other users cannot edit the comment
     */
    public function caseTen()
    {
        Notification::fake();
        Passport::actingAs(
            factory(User::class)->create()
        );
        $comment = factory(Comment::class)->create();
        $response = $this->patchJson("/api/comment/" . $comment->id, [
            "comment" => "test"
        ]);
        $response->assertJsonFragment(["error" => __("comment.unauthorized")]);
    }
}
