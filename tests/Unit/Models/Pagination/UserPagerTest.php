<?php


namespace Tests\Unit\Models\Pagination;


use App\Contracts\Models\PaginationInterface;
use App\Models\Pagination\UserPager;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserPagerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        $user = User::query();
        $pagination = (new UserPager)->paged($user);
        $this->assertInstanceOf(LengthAwarePaginator::class, $pagination);
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $this->assertInstanceOf(PaginationInterface::class, (new UserPager)->setPages(10));
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $this->assertInstanceOf(PaginationInterface::class, (new UserPager)->setOrderBy("test"));
    }

    /**
     * @test
     * @testdox
     */
    public function caseFour()
    {
        $this->assertInstanceOf(PaginationInterface::class, (new UserPager)->setOrderDir("test"));
    }
}
