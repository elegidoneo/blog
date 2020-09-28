<?php


namespace Tests\Integration\Models\Filters;

use App\Models\Filters\UserFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserFilterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        $requestParams = ['email' => 'admin@example.com',];
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['only'])
            ->getMock();
        $request->expects($this->any())
            ->method('only')
            ->willReturn($requestParams);
        $user = User::query();
        $filter = (new UserFilter($request))->apply($user);
        $this->assertInstanceOf(Builder::class, $filter);
        $array = $filter->get()->toArray();
        $this->assertArrayHasKey("name", $array[0]);
        $this->assertArrayHasKey("email", $array[0]);
        $this->assertArrayHasKey("admin", $array[0]);
        $this->assertArrayHasKey("active", $array[0]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $requestParams = ['name' => 'test',];
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['only'])
            ->getMock();
        $request->expects($this->any())
            ->method('only')
            ->willReturn($requestParams);
        $user = User::query();
        $filter = (new UserFilter($request))->apply($user);
        $this->assertInstanceOf(Builder::class, $filter);
        $array = $filter->get()->toArray();
        $this->assertEmpty($array);
    }
}
