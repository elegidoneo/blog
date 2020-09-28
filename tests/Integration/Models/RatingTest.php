<?php


namespace Tests\Integration\Models;


use App\Models\Post;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user->rate($post, 5);

        $rating = Rating::first();

        $this->assertInstanceOf(Post::class, $rating->rateable);
        $this->assertInstanceOf(User::class, $rating->qualifier);
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user->rate($post, 5);

        $this->assertInstanceOf(Collection::class, $user->ratings(Post::class)->get());
        $this->assertInstanceOf(Collection::class, $post->qualifiers(User::class)->get());
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user->rate($post, 5);
        $user2->rate($post, 10);

        $this->assertEquals(7.5, $post->averageRating(User::class));
    }
}
