<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item">RECEIPT & DISPATCH</li>
            <li class="breadcrumb-item active">RECEIPT</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Receipt</h3>
            <a href="{{route('app.receive.add')}}" class="btn btn-primary btn-sm p-2">Add New</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-1 p-0">
                    <div class="form-group">
                        <lable>From Date</lable>
                        <input type="text" class="form-control datepicker filter" id="from_date" name="from_date" value="" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-1 p-0">
                    <div class="form-group">
                        <lable>To Date</lable>
                        <input type="text" class="form-control datepicker filter" id="to_date" name="to_date" value="" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <lable>Reference No</lable>
                        <input type="text" class="form-control filter" id="reference_no" name="reference_no" value="" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <lable>Letter Type</lable>
                        <select class="form-select filter" name="letter_type" id="letter_type">
                            <option value="">Please Select</option>
                            <option value="Bill"> Bill</option>
                            <option value="Leave Application"> Leave Application</option>
                            <option value="Letter"> Letter</option>
                            <option value="Confidential Document"> Confidential Document</option>
                            <option value="Office Order"> Office Order</option>
                            <option value="Email">Email</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <lable>From</lable>
                        <select name="source" id="source" class="form-select source filter">
                            <option value="">Please Select</option>
                            <option value="Secretariat">Secretariat</option>
                            <option value="Directorate">Directorate</option>
                            <option value="Section">Section</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <lable>From Section</lable>
                        <select class="form-select filter" name="section" id="section">
                            <option value="">Please Select</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <lable>To Section</lable>
                        <select class="form-select filter" name="to_section" id="to_section">
                            <option value="">Please Select</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap= w-100">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Subject</th>
                            <th> To</th>
                            <th>Receivers Name</th>
                            <th>Received From</th>
                            <th>Letter Type</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
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
        <script src="{{asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
        <script>
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                calendarWeeks: false,
                zIndexOffset: 9999,
                orientation: "bottom"
            });
            let exportFormatter = {
                format: {
                    body: function (data, row, column, node) {
                        // Strip $ from salary column to make it numeric
                        return column === 5 ? data.replace(/[$,]/g, '') : data;
                    }
                }
            };
 
            function dataList()
            {
                $('#datatableDefault').DataTable().destroy();
                var pilot=$('#pilots').val();
                var from_date=$('#from_date').val();
                var to_date=$('#to_date').val();
                var reference_no=$('#reference_no').val();
                var letter_type=$('#letter_type').val();
                var source=$('#source').val();
                var section=$('#section').val();
                var to_section=$('#to_section').val();
                $('#datatableDefault').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    paging: true,
                    info: false,
                    orderable: false,
                    lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                    responsive: true,
                    order: [[1, 'desc']],
                    // fixedColumns: true,
                    //      "columnDefs": [
                    //   { "orderable": false, "targets": [0,1,2,3,4,5,6,7,8,9,10] }  // Disable order on first columns
                    // ],
                     dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
                     buttons: [{
                            extend: 'print',
                            className: 'btn btn-default btn-sm',
                            exportOptions: {
                              columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8],
                              format: {
                                    body: function (data, row, column, node) {
                                        // Strip $ from salary column to make it numeric
                                        return column === 5 ? data.replace(/[$,]/g, '') : data;
                                    }
                                }
                            },
                        },
                        {
                            extend: 'csv',
                            className: 'btn btn-default btn-sm',
                             exportOptions: {
                              columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8],
                              format: {
                                    body: function (data, row, column, node) {
                                        // Strip $ from salary column to make it numeric
                                        return column === 5 ? data.replace(/[$,]/g, '') : data;
                                    }
                                }
                            },
                        }
                    ],
                    ajax: {
                        url: "{{route('app.receive.list')}}",
                        type: 'POST',
                        data:{"_token": "{{ csrf_token() }}",pilot,from_date,to_date,reference_no,letter_type,to_section,section,source},
                    },
                    fnRowCallback: function( nRow, aData, iDisplayIndex ) {
                            var oSettings = this.fnSettings ();
                            $("td:eq(0)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                        },
                    "initComplete": function(){

                    },
                     drawCallback: function(settings) {
                    },
                });
            }
            dataList();
            
            $('.filter').on('change',function(){
                 dataList();
            });
        </script>
        
        <script>
            function addFile(receive_id,file_type) {
                clearError($('#manageFileForm'));
                $('#manageFileForm')[0].reset();
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
                clearError($('#manageFileForm'));
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            success(response.message);
                            $('#manageFileForm')[0].reset();
                            $('#fileModel').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#manageFileForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageFileForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            })
        </script>
    </x-slot>
</x-app-layout>
