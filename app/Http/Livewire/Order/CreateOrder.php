<?php

namespace App\Http\Livewire\Order;

use App\Models\Platform;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use Livewire\Component;

class CreateOrder extends Component
{
    public $platforms;
    public $platformId;
    public $platform;
    public $coins = [];
    public $coinId;
    public $amount;
    public $message ;

    protected $rules = [
        'platformId' => ['required' , 'numeric' , 'exists:platforms,id' ],
        'coinId' => ['required', 'string'],
    ];

    public function updated($peroperty , $value )
    {
        $this->message = null;
        $this->validateOnly($peroperty);
        if ( $peroperty == "platformId" ) {
            $this->platform = PlatformService::find($value);
            $this->coins = DriverService::coins($this->platform)->toArray();
        }
    }

    public function mount()
    {
        $this->platforms = PlatformService::listPlatforms();
    }

    public function render()
    {
        return view('livewire.order.create-order')->layout('layouts.app');
    }
}
