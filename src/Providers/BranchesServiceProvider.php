<?php

namespace Botble\Branches\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Branches\Models\Branch;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Routing\Events\RouteMatched;

class BranchesServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'plugins.branches' => require __DIR__ . '/../../config/permissions.php',
        ]);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/branches')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->publishAssets();


        if (function_exists('is_plugin_active') && is_plugin_active('language-advanced')) {
            LanguageAdvancedManager::registerModule(Branch::class, [
                'name', 'description', 'history', 'city', 'district', 'restaurant_type', 'address',
            ]);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-plugins-branches',
                'priority' => 120,
                'parent_id' => 'cms-plugins-restaurant',
                'name' => 'plugins/branches::branches.menu_name',
                'icon' => 'ti ti-map-pin',
                'url' => route('branches.index'),
                'permissions' => ['branches.index'],
            ]);
        });

        SlugHelper::registering(function () {
            SlugHelper::registerModule(Branch::class, fn () => trans('plugins/branches::branches.menu_name'));
            SlugHelper::setPrefix(Branch::class, 'branches', true);
            SlugHelper::setColumnUsedForSlugGenerator(Branch::class, 'name');
        });



        add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function ($type, $request, $object) {

            if (!($object instanceof Branch)) {
                return;
            }

            $gallery = $request->input('branch_gallery', []);

            if (!is_array($gallery)) {
                $gallery = array_filter((array) $gallery);
            }

            MetaBox::saveMetaBoxData($object, 'galleryx', json_encode($gallery, JSON_UNESCAPED_UNICODE));

            \Log::info('Branch MetaBox saved', [
                'id' => $object->id,
                'gallery' => $gallery,
            ]);
        }, 230, 3);
    }


}
