<?php

namespace Botble\Branches;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Plugin extends PluginOperationAbstract
{
    /**
     * Выполняется при активации плагина.
     */
    public static function activate(): void
    {
        // Прогоняем миграции плагина при активации
        Artisan::call('migrate', [
            '--path' => 'platform/plugins/branches/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Выполняется при удалении плагина.
     */
    public static function remove(): void
    {
        Schema::dropIfExists('branches');
    }
}
