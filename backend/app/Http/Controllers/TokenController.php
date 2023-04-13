<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client as OauthClient;
use App\Models\User;

class TokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/tokens/login",
     *     tags={"Tokens"},
     *     summary="Login process",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $response = [];
            $response['errors'] = $validator->errors();
            $response['message'] = 'please check username and password.';
            $response['status'] = 'validation_error';

            return response($response, 400);
        }
        $data = $validator->validate();

        $user = (new User())->findForPassport($data['username']);

        if (!$user || !password_verify($data['password'], $user->getAuthPassword())) {
            $response['errors'] = [];
            $response['message'] = 'Invalid username or password, please check username and password.';
            $response['status'] = 'Invalid_credentials';

            return response($response, 400);
        }
        if (!$user->hasVerifiedEmail()) {
            $response = [];
            $response['errors'] = ['username' => ['generic' => ['account is not verified']]];
            $response['message'] = 'Your email is not verified. please check your email for verification email.';
            $response['status'] = 'unauthorized';

            return response($response, 400);
        }

        $accessToken = $user->createToken('authtoken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    /**
     * @OA\Get(
     *     tags={"Tokens"},
     *     path="/api/v1/tokens/ping",
     *     summary="just sends OK, use for verifying connection",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="",
     *                 value={"OK"}
     *             )
     *         )
     *     )
     * )
     */
    public function ping(Request $request)
    {
        return response(['OK']);
    }

    /**
     * @OA\Get(
     *     tags={"Tokens"},
     *     path="/api/v1/users/logout",
     *     summary="ends user session",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="",
     *                 value={"OK"}
     *             )
     *         )
     *     )
     * )
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
        }

        return response(['OK']);
    }

    /**
     * @OA\Get(
     *     tags={"Tokens"},
     *     path="/api/v1/tokens/secret",
     *     summary="get secret for client_id=2",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="",
     *                 value={"secret": "PUzoFK3uGXZJTJi5bsrJdnQ1mpVU6H2OM7YYlRz7"}
     *             )
     *         )
     *     )
     * )
     */
    public function getSecret()
    {
        $token = '';

        $client = OauthClient::where(['id' => 2])->first();
        $token = $client->secret;

        return ['secret' => $token];
    }

    /**
     * @OA\Get(
     *     tags={"User Management"},
     *     path="/api/v1/tokens/impersonate/{user_id}",
     *     summary="Summary",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function impersonate(Request $request, User $user)
    {
        $token = $user->createToken('authtoken')->accessToken;

        return response(['user' => $user, 'access_token' => $token, 'expires_in' => 3600 * 24 * 30])
            ->cookie('Authorization_token', $token, 120)
        ;
    }
}
