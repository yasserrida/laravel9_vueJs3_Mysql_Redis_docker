<?php

namespace App\Providers;

use App\Models\AttributionDossier;
use App\Models\Contrat;
use App\Models\NotificationUser;
use App\Models\Operation;
use App\Models\Personne;
use App\Models\Reclamation;
use App\Models\User;
use App\Policies\AttributionDossierPolicy;
use App\Policies\ContratPolicy;
use App\Policies\NotificationUserPolicy;
use App\Policies\OperationPolicy;
use App\Policies\PersonnePolicy;
use App\Policies\ReclamationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Contrat::class => ContratPolicy::class,
        NotificationUser::class => NotificationUserPolicy::class,
        Personne::class => PersonnePolicy::class,
        User::class => UserPolicy::class,
        Operation::class => OperationPolicy::class,
        AttributionDossier::class => AttributionDossierPolicy::class,
        Reclamation::class => ReclamationPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(options: ['prefix' => 'api/oauth']);
        Passport::tokensExpireIn(now()->addDays(5));
        Passport::refreshTokensExpireIn(now()->addDays(7));
        Passport::personalAccessTokensExpireIn(now()->addMonths(7));

        Gate::define('viewLogViewer',   fn ($user) => in_array($user->email, ['admin@yasser.com']));
        Gate::define('isAdminstrateur', fn ($user) => $user->hasRole('ADMINISTRATEUR'));
        Gate::define('isResponsable',   fn ($user) => $user->hasRole('ADMINISTRATEUR') || $user->hasRole('RESPONSABLE'));
        Gate::define('isGestionnaire',  fn ($user) => $user->hasRole('ADMINISTRATEUR') || $user->hasRole('GESTIONNAIRE'));
    }
}
