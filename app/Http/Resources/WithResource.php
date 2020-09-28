<?php


namespace App\Http\Resources;


trait WithResource
{
    /**
     * @param $user
     * @return array|null
     */
    protected function extraUserData($user)
    {
        $objectToken = null;
        if (method_exists($user, "tokens")) {
            $token = $user->tokens()->first();
            if (!empty($token)) {
                $objectToken = [
                    "abilities" => $token->abilities,
                    "access_token" => $token->token,
                ];
            }

        }
        return $objectToken;
    }
}
