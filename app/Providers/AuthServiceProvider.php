<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use App\Policies\QueryPolicy;
use App\Policies\AnswerPolicy;
use App\Policies\AdditionPolicy;
use App\Models\Query;
use App\Models\Answer;
use App\Models\Addition;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Query::class => QueryPolicy::class,
        Answer::class => AnswerPolicy::class,
        Addition::class => AdditionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
