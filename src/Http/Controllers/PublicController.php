<?php

namespace Botble\Branches\Http\Controllers;

use Botble\Branches\Models\Branch;
use Botble\Base\Http\Controllers\BaseController;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Http\Request;
use Botble\Theme\Facades\Theme;
use Botble\Slug\Models\Slug;

class PublicController extends BaseController
{
    public function index(Request $request)
    {
        SeoHelper::setTitle(__('Наши филиалы'));

        $branches = Branch::query()
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        Theme::breadcrumb()
            ->add(__('Главная'), route('public.index'))
            ->add(__('Филиалы'));

        return Theme::scope('branches.index', compact('branches'))->render();
    }

    public function show(string $slug)
    {
        $slugEntry = Slug::where('key', $slug)
            ->where('reference_type', Branch::class)
            ->firstOrFail();


        $branch = Branch::findOrFail($slugEntry->reference_id);
        abort_if(!$branch->status, 404);

        SeoHelper::setTitle($branch->name)
            ->setDescription(strip_tags($branch->description));

        Theme::breadcrumb()
            ->add(__('Главная'), route('public.index'))
            ->add(__('Филиалы'), route('public.branches'))
            ->add($branch->name);

        return Theme::scope('branches.detail', compact('branch'))->render();
    }
}
