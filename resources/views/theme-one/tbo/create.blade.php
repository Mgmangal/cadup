@extends('theme-one.layouts.app',['title' => 'TBO','sub_title'=>'ADD'])
@section('css')
<link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Add TBO</h3>
        <a href="{{route('user.tbo')}}" class="btn btn-primary btn-sm p-2">Back</a>
    </div>
    <div class="card-body">
        <form action="{{route('user.tbo.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row m-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required" for="aircraft_call_sign">Aircraft </label>
                        <select name="aircraft_call_sign" id="aircraft_call_sign" class="form-control">
                            <option value="">Select Aircraft </option>
                            @foreach($aircrafts as $aircraft)
                            <option {{ old('aircraft_call_sign') == $aircraft->call_sign ? 'selected' : '' }} value="{{$aircraft->call_sign}}">{{$aircraft->call_sign}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('aircraft_call_sign'))
                            <span class="text-danger">{{ $errors->first('aircraft_call_sign') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required" for="name">TBO Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter TBO Name" />
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required" for="tbo_type">TBO Type </label>
                        <select name="tbo_type" id="tbo_type" class="form-control">
                            <option value="">Select TBO Type </option>
                            <option {{ old('tbo_type') == 'system' ? 'selected' : '' }} value="system">System</option>
                            <option {{ old('tbo_type') == 'part' ? 'selected' : '' }} value="part">Part</option>
                        </select>
                        @if ($errors->has('tbo_type'))
                            <span class="text-danger">{{ $errors->first('tbo_type') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required" for="ata_code">ATA Code </label>
                        <select name="ata_code" id="ata_code" class="form-control">
                            <option value="">Select ATA Code </option>
                            @foreach($atas as $ata)
                                <option {{ old('ata_code') == $ata->code ? 'selected' : '' }} value="{{$ata->code}}">{{$ata->code}} - {{$ata->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('ata_code'))
                            <span class="text-danger">{{ $errors->first('ata_code') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="part_number">Part Number</label>
                        <input type="text" value="{{ old('part_number') }}" class="form-control" name="part_number" placeholder="Enter Part Number" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="serial_number">Serial Number</label>
                        <input type="text" value="{{ old('serial_number') }}" class="form-control" name="serial_number" placeholder="Enter Serial Number" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required" for="tbo_requirement">TBO Requirement</label>
                        <input type="text" value="{{ old('tbo_requirement') }}" class="form-control" name="tbo_requirement" placeholder="Enter TBO Requirement" />
                        @if ($errors->has('tbo_requirement'))
                            <span class="text-danger">{{ $errors->first('tbo_requirement') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fitting_date" class="form-label">Date of Fitting</label>
                        <input type="text" value="{{ old('fitting_date') }}" class="form-control datepicker" id="fitting_date" name="fitting_date"
                            placeholder="DD-MM-YYYY" autocomplete="off">
                        @if ($errors->has('fitting_date'))
                            <span class="text-danger">{{ $errors->first('fitting_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option {{ old('status') == 'active' ? 'selected' : '' }} value="active">Active</option>
                            <option {{ old('status') == 'inactive' ? 'selected' : '' }} value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-md-12 ">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
{{-- <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
    });
</script>
@endsection
