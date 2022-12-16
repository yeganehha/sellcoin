<div>
    <x-slot name="title">{{ 'Add new Platform'}}</x-slot>
    <x-slot name="backRoute">{{ route('platforms.index')}}</x-slot>
    @if($message)
        <div class="alert alert-success">
            {{$message}}
        </div>
    @endif
    <div class="row">
        <div class="col-md-6 row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('platform.name') is-invalid @enderror" wire:model.lazy="platform.name"  value="{{ $platform['name'] }}" autocomplete="off" autofocus>
                @error('platform.name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6 row mb-3">
            <label for="version_id" class="col-md-4 col-form-label text-md-end">Driver</label>

            <div class="col-md-6">
                <select id="version_id" class="form-control @error('platform.driver_name') is-invalid @enderror" wire:model.lazy="platform.driver_name">
                    <option>Select Driver</option>
                    @foreach($drivers as $driver_name => $driver)
                        <option value="{{ $driver_name }}" @selected($platform['driver_name'] == $driver_name)>{{ $driver['label']  }}</option>
                    @endforeach
                </select>
                @error('platform.driver_name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12 mt-4 justify-content-center align-items-center d-flex border-1">
            <button type="button" class="btn btn-success mt-4" wire:loading.attr="disabled"
                    wire:click.prevent="save">
                Submit
            </button>
        </div>
    </div>
</div>
