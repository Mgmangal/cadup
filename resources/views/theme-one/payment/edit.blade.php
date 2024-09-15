@extends('theme-one.layouts.app', ['title' => 'Manage Library', 'sub_title' => 'Payment Edit'])
@section('css')

@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Payment Edit</h3>
        <a href="{{route('user.payment.history')}}" class="btn btn-primary btn-md">Back</a>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="card-body">
        <form action="{{route('user.payment.update',encrypter('encrypt', $payment->id))}}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_for">Payment For</label>
                        <select name="payment_for" id="payment_for" class="form-control" required>
                            <option value=""></option>
                            <option value="sfa" @if($payment->payment_for=='sfa') selected @endif>SFA</option>
                            <option value="bill" @if($payment->payment_for=='bill') selected @endif>Bill</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="reference_id">Reference ID</label>
                        <input type="text" name="reference_id" id="reference_id" class="form-control" value="{{$payment->reference_id}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <input type="text" name="payment_method" id="payment_method" class="form-control" value="{{$payment->payment_method}}" require>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_details">Payment Details</label>
                        <input type="text" name="payment_details" id="payment_details" class="form-control" value="{{$payment->payment_details}}" require>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" name="amount" id="amount" class="form-control" value="{{$payment->amount}}" require>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending" @if($payment->status=='pending') selected @endif>Pending</option>
                            <option value="paid" @if($payment->status=='paid') selected @endif>Paid</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')


@endsection