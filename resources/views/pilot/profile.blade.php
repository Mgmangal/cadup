<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">PROFILE</li>
        </ul>
    </x-slot>
    <x-slot name="css">

    </x-slot>
   
    <!-- Validation -->
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Profile</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('pilot.profile.update')}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Emp ID</label>
                            <input type="text" name="emp_id" class="form-control" value="{{$user->emp_id}}" />
                            @error('emp_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Salutation</label>
                            <input type="text" name="salutation" class="form-control" value="{{$user->salutation}}" />
                            @error('salutation')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{$user->name}}" />
                            @error('name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" value="{{$user->email}}" />
                            @error('email')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{$user->phone}}" />
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="profile" class="form-control" />
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