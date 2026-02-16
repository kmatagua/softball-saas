<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Game;
use App\Policies\GamePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Game::class => GamePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}