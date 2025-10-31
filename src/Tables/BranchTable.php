<?php

namespace Botble\Branches\Tables;

use Botble\Branches\Models\Branch;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;

class BranchTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Branch::class)
            ->addHeaderAction(
                CreateHeaderAction::make()
                    ->route('branches.create')
            )
            ->addActions([
                EditAction::make()->route('branches.edit'),
                DeleteAction::make()->route('branches.destroy'),
            ])
            ->addColumns([
                ImageColumn::make('logo')
                    ->title(trans('plugins/branches::branches.forms.logo'))
                    ->width(80)
                    ->alignCenter(),

                NameColumn::make()
                    ->route('branches.edit')
                    ->title(trans('plugins/branches::branches.forms.name')),

                FormattedColumn::make('city')
                    ->title(trans('plugins/branches::branches.forms.city'))
                    ->getValueUsing(fn (FormattedColumn $column) =>
                    $column->getItem()->city ?: '—'),

                FormattedColumn::make('district')
                    ->title(trans('plugins/branches::branches.forms.district'))
                    ->getValueUsing(fn (FormattedColumn $column) =>
                    $column->getItem()->district ?: '—'),

                FormattedColumn::make('restaurant_type')
                    ->title(trans('plugins/branches::branches.forms.restaurant_type'))
                    ->getValueUsing(fn (FormattedColumn $column) =>
                    $column->getItem()->restaurant_type ?: '—'),


                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()
                    ->permission('branches.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function ($query): void {
                $query->select([
                    'id',
                    'name',
                    'logo',
                    'city',
                    'district',
                    'restaurant_type',
                    'created_at',
                ]);
            });
    }
}
