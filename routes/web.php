<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Branches\Http\Controllers\Admin\BranchController;
use Botble\Branches\Http\Controllers\PublicController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Branches\Http\Controllers'], function (): void {
    // Admin
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'branches', 'as' => 'branches.'], function (): void {
            Route::resource('', BranchController::class)
                ->parameters(['' => 'branch']);
        });
    });

    // Public
    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function (): void {
            Route::get('branches', [PublicController::class, 'index'])->name('public.branches');
            Route::get('branches/{slug}', [PublicController::class, 'show'])->name('public.branch');
        });
    }
});
