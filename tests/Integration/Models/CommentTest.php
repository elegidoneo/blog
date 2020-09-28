<?php

namespace Tests\Integration\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     * @testdox
     */
   public function caseOne()
   {
       $comment = new Comment();
       $this->assertInstanceOf(BelongsTo::class, $comment->user());
   }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $comment = new Comment();
        $this->assertInstanceOf(BelongsTo::class, $comment->post());
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $comment = Comment::query();
        $this->assertInstanceOf(Builder::class, $comment->userId(1));
    }

    /**
     * @test
     * @testdox
     */
    public function caseFour()
    {
        $comment = Comment::query();
        $this->assertInstanceOf(Builder::class, $comment->postId(1));
    }
}
