<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\InvoiceClient;
use App\Policies\InvoiceClientPolicy;
use App\Policies\InvoicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('c', require resource_path('data/content.php'));

        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers());

        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(InvoiceClient::class, InvoiceClientPolicy::class);
    }
}
