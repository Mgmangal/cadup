<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.dashboard') }}">DASHBOARD</a></li>
            <li class="breadcrumb-item">RECEIPT & DISPATCH</li>
            <li class="breadcrumb-item ">RECEIPT</li>
            <li class="breadcrumb-item active">BILL</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
            rel="stylesheet">
        <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"
            rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
            rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
            rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Receipt Bill</h3>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm p-2">Back</a>
                <a href="javascript:void(0);" class="btn btn-success btn-sm p-2" onclick="addNew();">Add</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row m-3">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Flying verification</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Model Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.receive.store.bill') }}" method="POST" id="manageForm" class="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id" />
                    <input type="hidden" name="receives_id" id="receives_id" value="{{ $data->id }}" />
                    <div class="modal-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bill_no" class="form-label">Invoice Number<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bill_no" name="bill_no"
                                        placeholder="Please Enter Invoice No">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receive_from" class="form-label">Company Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="receive_from" name="receive_from"
                                        placeholder="Please Enter Company Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dates" class="form-label">Invoice Date<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker" id="dates" name="dates"
                                        placeholder="Please Enter Date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_amount" class="form-label">Total Amount<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="total_amount" name="total_amount"
                                        placeholder="Please Enter Total Amount">
                                </div>
                            </div>
                            @php
                                $expansesType = \App\Models\Master::where('type', 'expenses_type')->where('status', 'active')->where('is_delete', '0')->get();
                            @endphp
                            <div class="col-md-12">
                                <label for="expanses_type" class="form-label">Expanses Type</label>
                            </div>
                            @foreach ($expansesType as $value)
                            <div class="col-md-3">
                                <div class="form-check pt-3">
                                    <input class="form-check-input" type="checkbox" name="expenses_type[]" value="{{ $value->id }}" id="expanses_type{{ $value->id }}">
                                    <label class="form-check-label" for="expanses_type{{ $value->id }}">{{ $value->name }}</label>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="form-label" for="document">Document</label>
                                    <input type="file" class="form-control" name="document" id="document">
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-check pt-3">
                                    <input class="form-check-input" type="checkbox" name="fly_verify" value="yes"
                                        checked id="fly_verify">
                                    <label class="form-check-label" for="fly_verify">Flying verification
                                        applicable</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="fileModel" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.receive.fileStore')}}" method="POST" id="manageFileForm" class="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="edit_id" id="edit_id" />
                            <input type="hidden" name="receive_id" id="receive_id" />
                            <input type="hidden" name="file_type" id="file_type" />
                            <label class="form-label">File Name</label>
                            <select id="file_id" name="file_id" class="form-control">
                                <option value="">Select File</option>
                                @php
                                    $files = \App\Models\File::where('status','active')->where('is_delete', '0')->get();
                                @endphp
                                @foreach ($files as $file)
                                    <option value="{{$file->id}}">{{$file->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    
    <x-slot name="js">
        <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
        <script src="{{ asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#fly_verify').on('click', function() {
                    if ($(this).is(':checked')) {
                        $(this).val('yes');
                    } else {
                        $(this).val('no');
                    }
                });
            });

            function addNew() {
                clearError($('#manageForm'));
                $('#manageForm')[0].reset();
                $('.modal-title').html('Add Receipt Bill');
                $('#manageModal').modal('show');
                $('#edit_id').val('');
            }

            function receiveBillEdit(url) {
                $('#manageForm')[0].reset();
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('.modal-title').html('Edit Receipt Bill');
                            $('#edit_id').val(response.data.id);
                            $('#receives_id').val(response.data.receives_id);
                            $('#bill_no').val(response.data.bill_no);
                            $('#receive_from').val(response.data.receive_from);
                            $('#dates').val(response.data.dates);
                            $('#total_amount').val(response.data.total_amount);
    
                            // Check checkboxes based on expenses_type array
                            var expensesType = response.data.expenses_type;
                            if (expensesType && expensesType.length > 0) {
                                expensesType.forEach(function(expenseId) {
                                    $('#expanses_type' + expenseId).prop('checked', true);
                                });
                            }
    
                            $('#fly_verify').val(response.data.fly_verify);
                            if (response.data.fly_verify == 'yes') {
                                $('#fly_verify').prop('checked', true);
                            } else {
                                $('#fly_verify').prop('checked', false);
                            }
                            $('#manageModal').modal('show');
                        }
                    }
                });
            }

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                calendarWeeks: false,
                zIndexOffset: 9999,
                orientation: "bottom",
                container: "#manageModal",
            });

            $('#manageForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(form[0]);
                clearError(form);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (response.success) {
                            hideLoader();
                            success(response.message);
                            $('#manageForm')[0].reset();
                            $('#manageModal').modal('hide');
                            dataList();
                        } else {
                            $.each(response.error, function(fieldName, field) {
                                form.find('[name=' + fieldName + ']').addClass(
                                    'is-invalid');
                                form.find('[name=' + fieldName + ']').after(
                                    '<div class="invalid-feedback">' + field + '</div>');
                                    hideLoader();
                            })
                        }
                    }
                });
            });

            function dataList() {
                $('#datatableDefault').DataTable().destroy();
                $('#datatableDefault').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
                    lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                    responsive: true,
                    order: [
                        [0, 'desc']
                    ],
                    // columnDefs: [{
                    //     width: 200,
                    //     targets: 3
                    // }],
                    fixedColumns: true,
                    buttons: [{
                            extend: 'print',
                            className: 'btn btn-default btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn btn-default btn-sm'
                        }
                    ],
                    ajax: {
                        url: "{{ route('app.receive.bill.list') }}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}","receive_id":"{{$data->id}}"
                        },
                    },
                    fnRowCallback: function(nRow, aData, iDisplayIndex) {
                        var oSettings = this.fnSettings();
                        $("td:eq(0)", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
                        //$('td:eq(5)', nRow).css('text-align', 'center');
                    },
                    "initComplete": function() {

                    }
                });
            }
            dataList();
        </script>
        <script>
            function unverifyReceiptBill(receives_id,bill_no) {
                $.ajax({
                    url: "{{route('app.receive.unverify')}}",
                    method: "post",
                    dataType: 'json',
                    data: {"_token": "{{ csrf_token() }}",receives_id,bill_no},
                    success: function(response) {
                        dataList();
                    }
                });
            }
            
            function addFile(receive_id,file_type) {
                clearError($('#manageForm'));
                $('#manageForm')[0].reset();
                $.ajax({
                    url: "{{route('app.receive.check.file')}}",
                    method: "post",
                    dataType: 'json',
                    data: {"_token": "{{ csrf_token() }}",receive_id,file_type},
                    success: function(response) {
                        if(response.success)
                        {
                           $('#edit_id').val(response.data.id); 
                           $('#file_id').val(response.data.file_id); 
                           $('.modal-title').html('Update File');
                        }else{
                            $('#edit_id').val('');
                            $('.modal-title').html('Add File');
                        }
                        $('#receive_id').val(receive_id);
                        $('#file_type').val(file_type);
                        
                        $('#fileModel').modal('show'); 
                    }
                });
            }
            $('#manageFileForm').submit(function(e) {
                e.preventDefault();
                clearError($('#manageForm'));
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            success(response.message);
                            $('#manageForm')[0].reset();
                            $('#fileModel').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            })
        </script>
    </x-slot>
</x-app-layout>
