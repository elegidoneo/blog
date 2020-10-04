<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Notifications\UpdateUserNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @testdox Check that only administrators can see the list of users without paging
     */
    public function caseOne()
    {
        Passport::actingAs(
            $user = factory(User::class)->states("administrator")->create()
        );
        $user->createToken("test");
        $response = $this->getJson("/api/user");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
    }

    /**
     * @test
     * @testdox Check to show the paginated user list
     */
    public function caseTwo()
    {
        Passport::actingAs(
            $user = factory(User::class)->states("administrator")->create()
        );
        factory(User::class, 20)->create();
        $user->createToken("test");
        $response = $this->getJson("/api/user?pages=20");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
        $this->assertCount(20, $response->json('data'));
    }

    /**
     * @test
     * @testdox Check that the filters work
     */
    public function caseThree()
    {
        Passport::actingAs(
            $user = factory(User::class)->states("administrator")->create()
        );
        factory(User::class, 20)->create();
        $user->createToken("test");
        $response = $this->getJson("/api/user?pages=20&name=admin");
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json("data"));
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * @test
     * @testdox Check that the user is saved successfully
     */
    public function caseFour()
    {
        Notification::fake();
        $response = $this->postJson("/api/user", [
            "name" => "test",
            "email" => "test@example.com",
            "password" => "secret1234",
            "password_confirmation" => "secret1234"
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users", [
            "id" => $response->json("data.id"),
            "name" => "test",
            "email" => "test@example.com",
        ]);
        Notification::assertSentTo(User::find($response->json("data.id")), VerifyEmail::class);
    }

    /**
     * @test
     * @testdox Check that the validation works
     */
    public function caseFive()
    {
        Notification::fake();
        $response = $this->postJson("/api/user", [
            "name" => "test",
            "email" => "test@example.com",
            "password" => "secret1234",
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors("password");
        $response->assertJsonFragment(["password" => ["The password confirmation does not match."]]);
        Notification::assertNothingSent();
    }

    /**
     * @test
     * @testdox Check that user information can be seen
     */
    public function caseSix()
    {
        Passport::actingAs(
            $user = factory(User::class)->create()
        );
        $response = $this->getJson("/api/user/" . $user->id);
        $response->assertSuccessful();
        $response->assertJsonFragment(["name" => $user->name]);
    }

    /**
     * @test
     * @testdox Check that a user can be edited correctly
     */
    public function caseSeven()
    {
        Notification::fake();
        Passport::actingAs(
            $user = factory(User::class)->create()
        );
        $response = $this->patchJson("/api/user/" . $user->id, [
            "name" => "test"
        ]);
        $response->assertSuccessful();
        $response->assertJsonFragment(["name" => "test"]);
        Notification::assertSentTo($user, UpdateUserNotification::class);
    }

    /**
     * @test
     * @testdox
     */
    public function caseEight()
    {
        Passport::actingAs(
            $user = factory(User::class)->create()
        );
        $response = $this->deleteJson("/api/user/" . $user->id);
        $response->assertJsonFragment(["message" => __("user.delete", ["name" => $user->name])]);
        $this->assertSoftDeleted("users", [
            "id" => $user->id,
        ]);
    }

    /**
     * @test
     * @testdox Check that another user cannot edit another user's information
     */
    public function caseNine()
    {
        Passport::actingAs(
            $user = factory(User::class)->create()
        );
        $user->createToken("test");
        $response = $this->getJson("/api/user");
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(["error" => __("auth.unauthorized")]);
    }

    /**
     * @test
     * @testdox Check that your information cannot be seen between users
     */
    public function caseTen()
    {
        Passport::actingAs(
            factory(User::class)->create()
        );
        $user = factory(User::class)->create();
        $response = $this->getJson("/api/user/" . $user->id);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(["error" => __("auth.unauthorized")]);
    }

    /**
     * @test
     * @testdox Check that between users cannot edit information
     */
    public function caseEleven()
    {
        Notification::fake();
        Passport::actingAs(
            factory(User::class)->create()
        );
        $user = factory(User::class)->create();
        $response = $this->patchJson("/api/user/" . $user->id, [
            "name" => "test"
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(["error" => __("auth.unauthorized")]);
        Notification::assertNothingSent();
    }

    /**
     * @test
     * @testdox Check that other users cannot see the user list
     */
    public function caseTwelve()
    {
        Passport::actingAs(
            factory(User::class)->create()
        );
        $user = factory(User::class)->create();
        $response = $this->deleteJson("/api/user/" . $user->id);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(["error" => __("auth.unauthorized")]);
    }
}
