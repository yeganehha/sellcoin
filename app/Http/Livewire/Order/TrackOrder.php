<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use App\Services\Order\PurchaseService;
use Livewire\Component;

class TrackOrder extends Component
{
    public $message ;

    public Order $order ;
    public $error = false ;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function confirm()
    {
        $this->error = false;
        try {
            $this->order = PurchaseService::confirm($this->order);
        } catch (\Exception $exception){
            $this->error = $exception->getMessage();
        }
    }
    public function cancel()
    {
        $this->error = false;
        try {
            $this->order = PurchaseService::cancel($this->order);
        } catch (\Exception $exception){
            $this->error = $exception->getMessage();
        }
    }
    public function render()
    {
        return view('livewire.order.track-order')->layout('layouts.app');
    }
}
