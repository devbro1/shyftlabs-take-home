<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // $url is controled from verification.verify in web.php
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage())->markdown('mail.user.new_user', ['verify_email_url' => $url]);
        });

        Passport::routes();

        Gate::before(function ($user, $ability) {
            if (!Permission::where('name', $ability)->exists()) {
                return null;
            }

            return $user->hasPermissionTo($ability) ? true : false;
        });

        Gate::after(function ($user, $ability, $result, $arguments) {
            if (!is_bool($result) || !$result) {
                return $result;
            }

            if (!Permission::where('name', $ability)->exists()) {
                return $result;
            }

            if (Gate::has($ability) && $user->hasPermissionTo($ability)) {
                $a = Gate::abilities()[$ability];
                $rc = $a($user, ...$arguments);
                if (!$rc) {
                    throw new \Illuminate\Auth\Access\AuthorizationException();
                }

                return $rc;
            }

            return $result;
        });

        Gate::define('impersonate', function (User $user, User $u) {
            return $user->isAllowed('impersonate');
        });
    }
}
