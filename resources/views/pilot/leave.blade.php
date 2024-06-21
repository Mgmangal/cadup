<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <!--<li class="breadcrumb-item active">MANAGE OPRATIONS</li>-->
            <li class="breadcrumb-item active">LEAVE</li>
        </ul>
    </x-slot>
    <x-slot name="css">
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
            <h3 class="card-title">Leave List</h3>
            <a href="{{route('app.pilot.leave.create')}}" class="btn btn-primary btn-sm p-2">Add Leave</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Crew</th>
                            <th>Leave Type</th>
                            <th>Dates</th>
                            <th>Doc</th>
                            <th>Status</th>
                            <!-- <th>Registered At</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="leave_details">
                            
                        </div>
                    </div>
                </div>   
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    
    <x-slot name="js">
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
         function dataList()
        {
            $('#datatableDefault').DataTable().destroy();
            $('#datatableDefault').DataTable({
                processing: true,
                serverSide: true,
                dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
                lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                responsive: true,
                 order: [[0, 'desc']],
                columnDefs: [{ width: 200, targets: 3 }],
                fixedColumns: true,
                buttons: [{
                        extend: 'print',
                        className: 'btn btn-default btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-default btn-sm'
                    }
                ],
                ajax: {
                    url: "{{route('app.pilot.leave.list')}}",
                    type: 'POST',
                    data:{"_token": "{{ csrf_token() }}"},
                },
                 fnRowCallback: function( nRow, aData, iDisplayIndex ) { 
                        var oSettings = this.fnSettings ();
                        $("td:eq(0)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                    },
                "initComplete": function(){
                
                }
            }); 
        }
        dataList();
        
        function changeStatus(id,status)
        {
            $.ajax({
                    url: "{{route('app.pilot.leave.status')}}",
                    type: 'post',
                    data: {id,status,'_token':'{{csrf_token()}}'},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            swal("Success!", data.message, "success");
                        } else {
                            swal("Error!", data.message, "error");
                        }
                        dataList();
                    }
                });
        }
        function show(url)
        {
            $.ajax({
                    url: url,
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#leave_details').html(data.data);
                            $('#leaveModal').modal('show');
                        } 
                    }
                });
        }
        </script>
    </x-slot>
</x-app-layout>