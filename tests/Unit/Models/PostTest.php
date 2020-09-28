<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     * @testdox
     */
   public function caseOne()
   {
       $post = new Post();
       $this->assertInstanceOf(BelongsTo::class, $post->user());
   }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $post = new Post();
        $this->assertInstanceOf(HasMany::class, $post->comments());
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $post = Post::query();
        $this->assertInstanceOf(Builder::class, $post->toCreationDate(now()->format("Y-m-d")));
    }

    /**
     * @test
     * @testdox
     */
    public function caseFour()
    {
        $post = Post::query();
        $this->assertInstanceOf(Builder::class, $post->fromCreationDate(now()->format("Y-m-d")));
    }

    /**
     * @test
     * @testdox
     */
    public function caseFive()
    {
        $post = Post::query();
        $this->assertInstanceOf(Builder::class, $post->userId(1));
    }
}
