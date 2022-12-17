<?php

namespace App\Http\Livewire\Platform;

use App\Models\Platform;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use Livewire\Component;

class CreateAndEdit extends Component
{
    public $platform;
    public $drivers;
    public $message ;

    protected $rules = [
        'platform.name' => ['required' , 'max:255' , 'string'],
        'platform.driver_name' => ['required', 'string'],
    ];

    public function updated($peroperty , $value )
    {
        $this->message = null;
        $this->validateOnly($peroperty);
    }


    public function mount(Platform $platform)
    {
        $this->platform = $platform;
        $this->drivers = DriverService::listDrivers()->toArray();
        $this->rules['platform.driver_name'] = ['required' , 'string' , 'in:'.implode(',' , array_keys($this->drivers))];
    }

    public function save()
    {
        $this->validate();
        if ( $this->platform->id == null and $this->platform = PlatformService::insert($this->platform->name,$this->platform->driver_name) )
            $this->message = "Saved successfully.";
        elseif ( $this->platform->id != null and $this->platform = PlatformService::edit($this->platform,$this->platform->name,$this->platform->driver_name) )
            $this->message = "Saved successfully.";

    }
    public function render()
    {
        return view('livewire.platform.create-and-edit')->layout('layouts.app');
    }
}
