<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">EMPLOYEE </li>
            <li class="breadcrumb-item active">LICENSE / CERTIFICATE </li>
        </ul>
    </x-slot>
    <x-slot name="css">
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">License / Certificate</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($license as $key => $value)
                <div class="col-md-3 p-2">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">{{ucfirst($value->sub_type)}}</h3>
                            <div>
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm p-2">Add/Edit</a>
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm p-2">Renew</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <img src="{{is_image('uploads/demo.png')}}"  class="img-thumbnail w-100" />
                            <h4> {{$value->name}}</h4>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>




    <x-slot name="js">

    </x-slot>
</x-app-layout>