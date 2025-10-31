<?php

namespace Botble\Branches\Http\Controllers\Admin;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\Branches\Forms\BranchForm;
use Botble\Branches\Http\Requests\BranchRequest;
use Botble\Branches\Models\Branch;
use Botble\Branches\Tables\BranchTable;
use Exception;
use Illuminate\Http\Request;

class BranchController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/branches::branches.menu_name'), route('branches.index'));
    }

    public function index(BranchTable $table)
    {
        $this->pageTitle(trans('plugins/branches::branches.menu_name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/branches::branches.create'));
        return BranchForm::create()->renderForm();
    }

    public function store(BranchRequest $request, BaseHttpResponse $response)
    {
        $branch = Branch::query()->create($request->validated());
        event(new CreatedContentEvent(BRANCHES_MODULE_SCREEN_NAME, $request, $branch));

        return $response
            ->setPreviousUrl(route('branches.index'))
            ->setNextUrl(route('branches.edit', $branch->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, Request $request)
    {
        $branch = Branch::query()->findOrFail($id);

        event(new BeforeEditContentEvent($request, $branch));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $branch->name]));

        return BranchForm::createFromModel($branch)->renderForm();
    }

    public function update(int|string $id, BranchRequest $request, BaseHttpResponse $response)
    {
        $branch = Branch::query()->findOrFail($id);
        $branch->update($request->validated());

        event(new UpdatedContentEvent(BRANCHES_MODULE_SCREEN_NAME, $request, $branch));

        return $response
            ->setPreviousUrl(route('branches.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $branch = Branch::query()->findOrFail($id);
            $branch->delete();

            event(new DeletedContentEvent(BRANCHES_MODULE_SCREEN_NAME, $request, $branch));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
