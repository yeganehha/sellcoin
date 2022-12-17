<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;

class TrackOrder extends Component
{
    public $message ;

    public Order $order ;
    public function mount(Order $order)
    {
        $this->order = $order;
    }
    public function render()
    {
        return view('livewire.order.track-order')->layout('layouts.app');
    }
}
