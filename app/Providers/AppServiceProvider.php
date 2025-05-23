<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the shared components
        Blade::componentNamespace('App\\View\\Components\\Shared', 'shared');
        
        // Load the components from shared/components directory 
        $this->loadViewComponentsAs('', glob(resource_path('views/shared/components/*/*.blade.php')));
    }
    
    /**
     * Load view components
     *
     * @param string $prefix
     * @param array $components
     * @return void
     */
    protected function loadViewComponentsAs($prefix, $components)
    {
        foreach ($components as $componentPath) {
            $componentName = basename($componentPath, '.blade.php');
            $componentDir = basename(dirname($componentPath));
            
            // Register the component with its directory as namespace
            Blade::component("shared.components.{$componentDir}.{$componentName}", "{$componentDir}.{$componentName}");
        }
    }
}
