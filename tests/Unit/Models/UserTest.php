<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     * @testdox
     */
   public function caseOne()
   {
       $user = factory(User::class)->create();
       $this->assertFalse($user->isAdmin());
   }

    /**
     * @test
     * @testdox
     */
   public function caseTwo()
   {
       $user = factory(User::class)->states("administrator")->create();
       $this->assertTrue($user->isAdmin());
   }

    /**
     * @test
     * @testdox
     */
   public function caseThree()
   {
       $user = new User();
       $this->assertInstanceOf(HasMany::class, $user->posts());
   }

    /**
     * @test
     * @testdox
     */
   public function caseFour()
   {
       $user = factory(User::class)->create();
       $this->assertNull($user->getTokenAttribute());
   }

    /**
     * @test
     * @testdox
     */
    public function caseFive()
    {
        $user = factory(User::class)->create();
        $user->createToken("test");
        $this->assertNotNull($user->getTokenAttribute());
        $this->assertEquals("test", $user->getTokenAttribute()->name);
    }

    /**
     * @test
     * @testdox
     */
    public function caseSix()
    {
        $user = User::query();
        $this->assertInstanceOf(Builder::class, $user->name("test"));
    }

    /**
     * @test
     * @testdox
     */
    public function caseSeven()
    {
        $user = User::query();
        $this->assertInstanceOf(Builder::class, $user->email("test"));
    }

    /**
     * @test
     * @testdox
     */
    public function caseEight()
    {
        $user = User::query();
        $this->assertInstanceOf(Builder::class, $user->active(true));
    }

    /**
     * @test
     * @testdox
     */
    public function caseNine()
    {
        $user = new User();
        $this->assertInstanceOf(HasMany::class, $user->comments());
    }

    /**
     * @test
     * @testdox
     */
    public function caseTen()
    {
        $user = User::query();
        $this->assertInstanceOf(Builder::class, $user->toCreationDate(now()->format("Y-m-d")));
    }

    /**
     * @test
     * @testdox
     */
    public function caseEleven()
    {
        $user = User::query();
        $this->assertInstanceOf(Builder::class, $user->fromCreationDate(now()->format("Y-m-d")));
    }
}
