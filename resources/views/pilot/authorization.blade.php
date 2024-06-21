<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">PILOT </li>
            <li class="breadcrumb-item active">AUTHORIZATION</li>
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
            <h3 class="card-title">Authorization</h3>
            <div>
            <a href="{{route('app.pilot')}}" class="btn btn-success btn-sm p-2">Issue Authorization</a>
            <a href="{{route('app.pilot')}}" class="btn btn-success btn-sm p-2">Print Authorization</a>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Renewed On</th>
                        <th>Applicability</th>  <!---Aircraft Type,Engine Type,Component Type--->
                        <th>Planned Renuwal Date</th>
                        <th>Issued On</th>
                        <th>Extended Date</th>
                        <th>Issued On</th>
                        <th>Next Due</th>
                        <th>Status</th>  <!---active,Suspended,Revoked,Expired---->
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
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
                responsive: true,
            }); 
        }
        dataList();
        </script>
    </x-slot>
</x-app-layout>