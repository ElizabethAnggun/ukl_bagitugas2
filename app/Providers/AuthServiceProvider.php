<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * AuthServiceProvider - Provider untuk registrasi Policy
 * 
 * Policy digunakan untuk otorisasi/permission
 * Menentukan apa yang boleh dilakukan user terhadap resource
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapping model ke policy-nya
     * 
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Registrasi policy untuk setiap model
        Project::class => ProjectPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Boot method - dipanggil saat aplikasi dijalankan
        $this->registerPolicies();
    }
}
