<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox Check that you log in correctly
     */
    public function caseOne()
    {
        $response = $this->postJson('/api/login', [
            "email" => "admin@example.com",
            "password" => "test1234",
        ]);
        $response->assertSuccessful();
        $response->assertJsonFragment([
            "message" => __("auth.success"),
        ]);
        $this->assertAuthenticatedAs(\auth()->user());
    }

    /**
     * @test
     * @testdox Check that you log out correctly
     */
    public function caseTwo()
    {
        $user = factory(User::class)->states("active")->create();
        $user->createToken("test");
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/logout');
        $response->assertSuccessful();
        $response->assertJson(["message" => __("auth.logout")]);
    }

    /**
     * @test
     * @testdox Check when correct data is entered do not log in
     */
    public function caseThree()
    {
        $user = factory(User::class)->states("inactive")->create();
        $response = $this->postJson('/api/login', [
            "email" => $user->email,
            "password" => "password",
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors("email");
        $response->assertJsonFragment([
            "message" => "The given data was invalid.",
            "email" => [__("auth.failed")]
        ]);
    }
}
