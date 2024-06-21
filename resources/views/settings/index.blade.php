<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">SETTING</li>
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
            <h3 class="card-title">Setting</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('app.settings.update')}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
               <div class="row ">
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" name="logo" />
                            @if(!empty($setting->app_logo))
                            <img src="{{asset('uploads/'.$setting->app_logo)}}" alt="logo" class="img-fluid thumbnail" width="100"/>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">Favicon</label>
                            <input type="file" class="form-control" name="favicon" />
                            @if(!empty($setting->app_favicon))
                            <img src="{{asset('uploads/'.$setting->app_favicon)}}" alt="favicon" class="img-fluid thumbnail" width="100" />
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">App Name</label>
                            <input type="text" class="form-control" name="app_name" value="{{$setting->app_name}}" />
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">App Phone</label>
                            <input type="text" class="form-control" name="app_phone" value="{{$setting->app_phone}}" />
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">App Email</label>
                            <input type="text" class="form-control" name="app_email" value="{{$setting->app_email}}" />
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">App Address</label>
                            <input type="text" class="form-control" name="app_address" value="{{$setting->app_address}}" />
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">Copyright</label>
                            <input type="text" class="form-control" name="app_copyright" value="{{$setting->app_copyright}}" />
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="form-group">
                            <label class="form-label">App Timezone</label>
                            <select id="app_timezone" class="form-control" name="app_timezone">
                                <option {{ $setting->app_timezone == 'IST' ? 'selected' : '' }} value="IST">IST</option>
                                <option {{ $setting->app_timezone == 'UTC' ? 'selected' : '' }} value="UTC">UTC</option>
                            </select>
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