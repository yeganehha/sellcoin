<?php

namespace App\Http\Livewire\Order;


use App\Http\Livewire\DataTableComponent;
use App\Models\Order;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OrderIndex extends DataTableComponent
{
    protected $model = Order::class;
    public Platform $platform ;


    public function mount(Platform $platform )
    {
        $this->platform = $platform;
        if ( $platform->id != null ) {
            $this->backRoute = 'platforms.index';
            $this->title = "Order of " . $this->platform->name;
        } else
            $this->title = "All orders";
    }

    function builder(): Builder
    {
        if ( $this->platform->id != null ) {
            return $this->platform->orders()->getQuery();
        } else
            return parent::builder();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()
                ->sortable(),
            Column::make("Amount", "amount")
                ->sortable(),
            Column::make("Platform id", "platform.name")
                ->hideIf($this->platform->id != null)
                ->sortable(),
            Column::make("Currency", "currency_name")
                ->searchable()
                ->sortable(),
            Column::make("Unit Price", "currency_price")
                ->format(fn($value, $row) => number_format($value,8)  )
                ->sortable(),
            Column::make("Order Price", "price")
                ->format(fn($value, $row) => number_format($value,8)  )
                ->sortable(),
            Column::make("Status", "status")
                ->format(
                    fn($value, $row, Column $column) => view('layouts.status', compact('value'))
                )
                ->searchable()
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
        ];
    }
}
