@extends('theme-one.layouts.app',['title' => 'SFA','sub_title' => $sub_title])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}"
    rel="stylesheet">

@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $sub_title }}</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-2">
                <div class="form-group">
                    <lable for="pilots">Crew</lable>
                    <select name="pilots" id="pilots" class="form-control filter" form="sfa-form" required>
                        @if($pilots->count() == 1 && $pilots->first()->id == Auth::user()->id)
                            <option value="{{ $pilots->first()->id }}">{{ $pilots->first()->salutation . ' ' . $pilots->first()->name }}</option>
                        @else
                            <option value="">Select Poilot</option>
                            @foreach($pilots as $pilot)
                                <option value="{{ $pilot->id }}">{{ $pilot->salutation . ' ' . $pilot->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <lable for="from_date">From Date</lable>
                    <input type="text" readonly form="sfa-form" name="from_date" class="form-control datepicker filter"
                        id="from_date" required>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <lable for="to_date">To Date</lable>
                    <input type="text" readonly form="sfa-form" name="to_date" class="form-control datepicker filter"
                        id="to_date" required>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Aircraft</th>
                        <th>A/C Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Block Time</th>
                        <th>Flying in<br> Capacity <br>of A/C</th>
                        <th>Rate/Hours<br> as in G.O. (₹)</th>
                        <th>Amount (₹)</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <th><span id="totalBlockTime"></span></th>
                        <th></th>
                        <th></th>
                        <th><span id="total-price">0</span></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box widget-color-blue2">
                    <div class="widget-body">
                        <div class="widget-main padding-8">
                            <div class="table-responsive">
                                <table id="dataTable-ct" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Sn</th>
                                            <th>Certify That</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th id="certified_that"></th>
                                            <th id="certified_that_action"></th>
                                            <input type="hidden" class="certified_that" name="certified_that" form="sfa-form">
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10"></div>
            <div class="col-sm-2">
                <form action="{{route('user.sfa.sfaStore')}}" method="post" id="sfa-form" class="text-right">
                    @csrf
                    <input type="hidden" name="total_price" id="total_price">
                    <input type="submit" name="submit_btn" id="submit_btn" value="Generate SFA Report" class="btn btn-primary d-none">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="manageModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Manage Certify That </h5>
                <button type="button" class="close btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--<h4 id="reamrk_no_show"></h4>-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="textarea_certify_that">Certify That</label>
                            <textarea class="form-control" name="textarea_certify_that" id="textarea_certify_that"
                                placeholder="Enter Certify That" autocomplete="off"> </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="saveCertifiedThat();">Save</button>
                </div>
            </div>
            <div class="modal-footer ">
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script>
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        calendarWeeks: false,
        zIndexOffset: 9999,
        orientation: "bottom"
    });

    function dataList()
    {
        $('#datatableDefault').DataTable().destroy();
        var pilot=$('#pilots').val();
        var from_date=$('#from_date').val();
        var to_date=$('#to_date').val();
        $('#datatableDefault').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            info: false,
            order: [[1, 'desc']],
            orderable: false,
            lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
            responsive: true,
            fixedColumns: true,
                    "columnDefs": [
                { "orderable": false, "targets": [2,3,4,5,6,7,8,9,10] }  // Disable order on first columns
            ],
            ajax: {
                url: "{{route('user.sfa.flyinglist')}}",
                type: 'POST',
                data:{"_token": "{{ csrf_token() }}",pilot,from_date,to_date},
            },
            fnRowCallback: function( nRow, aData, iDisplayIndex ) {
                var oSettings = this.fnSettings ();
                $("td:eq(0)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
            },
            "initComplete": function(){

            },
            drawCallback: function(settings) {
                $('#totalBlockTime').html(settings.json.total_time);
                $("#total-price").html(settings.json.total_payable_amount);
                $("#total_price").val(settings.json.total_payable_amount);
                $("#certified_that").html(settings.json.certified_that);
                $(".certified_that").val(settings.json.certified_that);
                $("#certified_that_action").html('<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="updateCertifiedThat();">Edit</a>');
                if(settings.json.data.length > 0){
                    $('#submit_btn').removeClass('d-none');
                }
            },
        });
    }

    $('.filter').on('change',function(){
        if($('#pilots').val().length>0&&$('#from_date').val().length>0&&$('#to_date').val().length>0)
        {
            dataList();
        }
    });
    function calPrice(e){

        var rate_per_hour = $(e).val();
        var rate_per_minut = rate_per_hour/60; //Math.floor(rate_per_hour/60);
        var time = $(e).parent('td').prev().prev().html();
        var total_amount = $(e).parent('td').parent('tr').parent('tbody').next().find("#total-price");

        var time_array = time.split(":");
        var total_minut = parseInt(time_array[0]*60) + parseInt(time_array[1]);
        var amount = rate_per_minut*total_minut;//Math.round(rate_per_minut*total_minut);
        $(e).parent('td').next().find('.amount').val(amount.toFixed(2));
        var total = 0;
        $('.amount').each(function(){
            var row_amount = $(this).val();
            if(row_amount!="undefined" && row_amount!="" )
                total = parseFloat(total) + parseFloat(row_amount);
        });
        total_amount.html(total.toFixed(2));
    }

    function updateCertifiedThat()
    {
        $('#manageModal').modal('show');
        $('#textarea_certify_that').html($('.certified_that').val());
        CKEDITOR.replace('textarea_certify_that');
    }
    function saveCertifiedThat(input)
    {
        $('#manageModal').modal('hide');
        $('#textarea_certify_that').html();
        var data = CKEDITOR.instances.textarea_certify_that.getData();
        $('#certified_that').html(data);
        $('.certified_that').val(data);
    }
</script>
@endsection
