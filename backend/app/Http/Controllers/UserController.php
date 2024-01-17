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

/**
 * @group User management
 */
class UserController extends Controller
{
    use SendsPasswordResetEmails;

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

    public function show(User $user)
    {
        return $user;
    }

    public function getDefaultUser()
    {
        return new User();
    }

    public function update(Request $request, User $user)
    {
        $user->fill(User::validate($request, $user));
        $user->save();
        $user->syncRoles($request->get('roles', []));
        $user->syncPermissions($request->get('permissions', []));

        return ['message' => 'User was updated successfully', 'data' => $user];
    }

    public function destroy($id)
    {
    }

    public function getCurrentUser(Request $request)
    {
        return $request->user();
    }

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
        if (Password::RESET_LINK_SENT == $response) {
            return response()->json(['message' => 'Reset link sent to your email.', 'success' => true], 201);
        }

        return response()->json(['message' => 'Unable to send reset link', 'success' => false], 401);
    }

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

        return response(['success' => false, 'status' => $status], 400);
    }

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
