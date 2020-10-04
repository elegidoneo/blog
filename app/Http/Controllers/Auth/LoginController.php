<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * @var array
     */
    private $response;

    /**
     * @var User
     */
    private $user;

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return false|\Psr\Http\Message\ResponseInterface
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function attemptLogin(Request $request)
    {
        try {
            $this->user = User::query()->email($request->input("email"))->active(true);
            if (! $this->user->exists()) {
                return false;
            }

            $http = new Client();
            $response = $http->post(env("APP_URL") . '/oauth/token', [
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'password',
                    'client_id' => config("passport.personal_access_token.id"),
                    'client_secret' => config("passport.personal_access_token.secret"),
                    'username' => $request->input("email"),
                    'password' => $request->input("password"),
                    'scope' => '*',
                ],
                RequestOptions::VERIFY => false,
            ]);

            $this->response = $response->getBody()->getContents();
            return true;
        } catch (ClientException $exception) {
            logger()->error(__METHOD__, compact("exception"));
            throw \Illuminate\Validation\ValidationException::withMessages(["email" => __("auth.failed")]);
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact("exception"));
            return false;
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return response()->json([
            "message" => __("auth.success"),
            "auth" => $this->response,
            "user" => new UserResource($this->user->first())
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        event(new Logout("api", $request->user()));
        return response()->json(["message" => __("auth.logout")]);
    }
}
