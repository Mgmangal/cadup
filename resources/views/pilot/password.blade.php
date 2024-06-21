<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">PASSWORD</li>
        </ul>
    </x-slot>
    <x-slot name="css">

    </x-slot>
    <!-- Session Status -->
    <!-- Validation -->
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Change Password</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('pilot.password.update')}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="text" name="current_password" class="form-control" value="" />
                            @error('current_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="text" name="new_password" class="form-control" value="" />
                            @error('new_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="text" name="confirm_password" class="form-control" value="" />
                            @error('confirm_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
            
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <x-slot name="js">

    </x-slot>
</x-app-layout>