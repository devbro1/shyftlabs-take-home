<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * @OA\Get(
     *     path="/api/v1/users/",
     *     summary="get list of all users",
     *     tags={"User Management"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('username'),
                AllowedFilter::partial('full_name'),
                AllowedFilter::partial('email'),
            ])
            ->allowedSorts(['id', 'username', 'full_name', 'email'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Create new user",
     *     tags={"User Management"},
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
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->fill(User::validate($request));
        $user->email_verified_at = Carbon::now();
        $user->save();
        $user->syncRoles($request->get('roles', []));
        $user->syncPermissions($request->get('permissions', []));

        $this->forgotPassword($request);

        return ['message' => 'User was created successfully', 'data' => $user];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     summary="select a user",
     *     tags={"User Management"},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true, @OA\Schema(
     *             type="string"
     *         ),
     *         @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="An int value."
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/default_user",
     *     summary="gets a basic user to be used for creating new users",
     *     tags={"User Management"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function getDefaultUser()
    {
        return new User();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     summary="update user",
     *     tags={"User Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
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
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, User $user)
    {
        $user->fill(User::validate($request, $user));
        $user->save();
        $user->syncRoles($request->get('roles', []));
        $user->syncPermissions($request->get('permissions', []));

        return ['message' => 'User was updated successfully', 'data' => $user];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/me",
     *     summary="Get currently authenticated user",
     *     tags={"User Management"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     * )
     */
    public function getCurrentUser(Request $request)
    {
        return $request->user();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/register",
     *     summary="Register a new user",
     *     tags={"User Management"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     example="",
     *                     type="string",
     *
     *                 ),
     *                 @OA\Property(
     *                     property="full_name",
     *                     example="",
     *                     type="string",
     *
     *                 ),
     *                 @OA\Property(
     *                     property="passsword",
     *                     example="",
     *                     type="string",
     *
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     example="",
     *                     type="string",
     *
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     example="",
     *                     type="string",
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $user = new User();
        $fillable = $user->getFillable();
        $validatedData = User::validate($request);

        foreach ($fillable as $fill) {
            $user->{$fill} = $validatedData[$fill] ?? null;
        }
        $user->password = bcrypt($validatedData['password']);
        $user->active = true;

        $user->save();

        $user->sendEmailVerificationNotification();

        return ['message' => 'User was created successfully'];
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/forgot_password",
     *     summary="request a forget password process to start",
     *     tags={"User Management"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     example="",
     *                     type="string",
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $inputs = $request->input();
        $user = User::where(['email' => $inputs['email']])->first();
        if (!$user) {
            throw new \Exception('User not found');
        }

        $this->validateEmail($request);
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        // return ['received' => true];
        if (Password::RESET_LINK_SENT == $response) {
            return response()->json(['message' => 'Reset link sent to your email.', 'success' => true], 201);
        }

        return response()->json(['message' => 'Unable to send reset link', 'success' => false], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/reset_password",
     *     summary="Reset password for a given user",
     *     tags={"User Management"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="",
     *                     description=""
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="",
     *                     description="token was sent to user email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        $data = $request->only('email', 'password', 'password_confirmation', 'token');
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if (Password::PASSWORD_RESET === $status) {
            return ['success' => true];
        }

        return ['success' => false, 'status' => $status];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/emails/verify/{id}/{token}",
     *     summary="verify user email",
     *     tags={"User Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true, description="token sent to user email",
     *         @OA\Schema(
     *             type="string"
     *         )
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
    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!$user->hasVerifiedEmail()) {
            if (hash_equals($request->route('token'), sha1($user->getEmailForVerification()))) {
                $user->markEmailAsVerified();
                event(new Verified($user));

                return ['message' => 'Email is now verified', 'success' => true];
            }

            return ['message' => 'Email verification failed', 'success' => false];
        }

        return ['message' => 'Email is already verified', 'success' => false];
    }
}
