<?php

namespace App\Http\Livewire\Platform;

use App\Http\Livewire\DataTableComponent;
use App\Services\AppsService;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Platform;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class PlatformIndex extends DataTableComponent
{
    protected $model = Platform::class;

    function builder(): Builder
    {
        return Platform::query()->withTrashed();
    }

    public function bulkActions(): array
    {
        return [
            'activate' => 'Activate',
            'deactivate' => 'Deactivate',
        ];
    }

    public function deactivate()
    {
        try {
            $platforms = $this->getSelected();
            PlatformService::deactivateMultiply($platforms);
            $this->clearSelected();
        } catch (\Exception $exception) {}
    }

    public function activate()
    {
        try {
            $platforms = $this->getSelected();
            PlatformService::activateMultiply($platforms);
            $this->clearSelected();
        } catch (\Exception $exception) {}
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->searchable()
                ->sortable(),
            Column::make("Deposit tether", "deposit_tether")
                ->format(fn($value, $row) => number_format($value)  )
                ->sortable(),
            Column::make("Reserved tether", "reserved_tether")
                ->format(fn($value, $row) => number_format($value)  )
                ->sortable(),
            Column::make("Driver name", "driver_name")
                ->searchable()
                ->format(fn($value, $row) => DriverService::driverName($value)  )
                ->sortable(),
            BooleanColumn::make("Active", "deleted_at")
                ->setSuccessValue(false)
                ->sortable(),
        ];
    }
}
