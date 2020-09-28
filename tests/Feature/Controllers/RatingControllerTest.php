<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox Check when the post is rated
     */
   public function caseOne()
   {
       Sanctum::actingAs(
           $user = factory(User::class)->states("administrator")->create()
       );
       $post = factory(Post::class)->create();
       $response = $this->postJson("/api/qualify/".$post->id, [
            "qualify"  => 5
       ]);
       $this->assertEquals($post->id, $response->json("rating.id"));
       $this->assertEquals($user->id, $response->json("qualifier.id"));
   }

    /**
     * @test
     * @testdox Check the average data of the post
     */
   public function caseTwo()
   {
       Sanctum::actingAs(
           $user = factory(User::class)->states("administrator")->create()
       );
       $user2 = factory(User::class)->create();
       $post = factory(Post::class)->create();
       $user2->rate($post, 10);
       $user->rate($post, 5);
       $response = $this->getJson("/api/average-rating/".$post->id);
       $this->assertEquals(7.5, $response->json("average"));
   }

    /**
     * @test
     * @testdox Check when they do not send error evaluation
     */
    public function caseThree()
    {
        Sanctum::actingAs(
            $user = factory(User::class)->states("administrator")->create()
        );
        $post = factory(Post::class)->create();
        $response = $this->postJson("/api/qualify/".$post->id);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors("qualify");
        $response->assertJsonFragment(["qualify" => ["The qualify field is required."]]);
    }
}
