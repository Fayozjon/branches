<?php

namespace Botble\Branches\Forms;

use Botble\Base\Forms\FieldOptions\RepeaterFieldOption;
use Botble\Base\Forms\Fields\RepeaterField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Branches\Models\Branch;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\MetaBox;

class BranchForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['form-validation', 'jquery-ui'])
            ->addScriptsDirectly('vendor/core/core/base/js/tags.js');

        $this
            ->model(Branch::class)
            ->setValidatorClass(\Botble\Branches\Http\Requests\BranchRequest::class)
            ->add('name', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.name'),
                'required' => true,
                'attr' => ['placeholder' => trans('plugins/branches::branches.forms.name')],
            ])
            ->add('city', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.city'),
            ])
            ->add('district', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.district'),
            ])
            ->add('address', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.address'),
            ])
            ->add('restaurant_type', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.restaurant_type'),
            ])
            ->add('description', EditorField::class, [
                'label' => trans('plugins/branches::branches.forms.description'),
            ])
            ->add('history', EditorField::class, [
                'label' => trans('plugins/branches::branches.forms.history'),
            ])
            ->addMetaBoxes([
                'galleryx' => [
                    'title' => trans('plugins/branches::branches.forms.gallery'),
                    'content' => view('plugins/branches::partials.gallery-field', [
                        'gallery' => old('gallery', MetaBox::getMetaData($this->getModel(), 'galleryx', true)),
                    ])->render(),
                ],
            ])
            ->add('latitude', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.latitude'),
                'attr' => ['placeholder' => 'e.g. 39.654'],
            ])
            ->add('longitude', TextField::class, [
                'label' => trans('plugins/branches::branches.forms.longitude'),
                'attr' => ['placeholder' => 'e.g. 66.974'],
            ])
            ->add('status', OnOffField::class, [
                'label' => trans('core/base::tables.status'),
                'default_value' => true,
            ])
            ->add('logo', MediaImageField::class, [
                'label' => trans('plugins/branches::branches.forms.logo'),
            ])
            ->setBreakFieldPoint('status');
    }
}
