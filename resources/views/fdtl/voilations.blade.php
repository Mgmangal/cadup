<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">REPORT </li>
            <li class="breadcrumb-item active">VIOLATIONS SUMMARY</li>
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
            <h3 class="card-title">Violation Summary</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Violation Type</th>
                            <th>Last 1 Day</th>
                            <th>Last 7 Days</th>
                            <th>Month-to-Date</th>
                            <th>Last 30 Days</th>
                            <th>Year-to-Date</th>
                            <th>Last 365 Days</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Flight Time</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Flight_Time','Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Flight_Time') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Flight_Time', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Flight_Time') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Flight_Time', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Flight_Time') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Flight_Time', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Flight_Time') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Flight_Time', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Flight_Time') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Flight_Time', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Flight_Time') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Flight_Time', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Flight_Time') }}</a></td>

                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Flight Duty Period</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Flight_Duty_Period') }}</a></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Duty Period</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Flight_Duty_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Flight_Duty_Period', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Flight_Duty_Period') }}</a></td>
                        
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Duty_Period', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Duty_Period') }}</a></td>-->
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Duty_Period', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Duty_Period') }}</a></td>-->
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Duty_Period', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Duty_Period') }}</a></td>-->
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Duty_Period', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Duty_Period') }}</a></td>-->
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Duty_Period', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Duty_Period') }}</a></td>-->
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Duty_Period', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Duty_Period') }}</a></td>-->
                            <!--<td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Duty_Period', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Duty_Period') }}</a></td>-->
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Rest Period</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Rest_Period', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Rest_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Rest_Period', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Rest_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Rest_Period', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Rest_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Rest_Period', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Rest_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Rest_Period', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Rest_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Rest_Period', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Rest_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Rest_Period', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Rest_Period') }}</a></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Landings</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Landings', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Landings') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Landings', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Landings') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Landings', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Landings') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Landings', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Landings') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Landings', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Landings') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Landings', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Landings') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Landings', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Landings') }}</a></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Stand by Duty</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Stand_by_Duty') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Stand_by_Duty') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Stand_by_Duty') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Stand_by_Duty') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Stand_by_Duty') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Stand_by_Duty') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Stand_by_Duty', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Stand_by_Duty') }}</a></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Consecutive Nights</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Consecutive_Nights') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Consecutive_Nights') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Consecutive_Nights') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Consecutive_Nights') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Consecutive_Nights') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Consecutive_Nights') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Consecutive_Nights', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Consecutive_Nights') }}</a></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Break Period</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Break_Period', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Break_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Break_Period', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Break_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Break_Period', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Break_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Break_Period', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Break_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Break_Period', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Break_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Break_Period', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Break_Period') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Break_Period', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Break_Period') }}</a></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Others</td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last1Days }}', '{{ $currentDate }}', 'Others', 'Last 1 Day')">{{ countViolationType($last1Days, $currentDate, 'Others') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last7Days }}', '{{ $currentDate }}', 'Others', 'Last 7 Days')">{{ countViolationType($last7Days, $currentDate, 'Others') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $monthToDate }}', '{{ $currentDate }}', 'Others', 'Month-to-Date')">{{ countViolationType($monthToDate, $currentDate, 'Others') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last30Days }}', '{{ $currentDate }}', 'Others', 'Last 30 Days')">{{ countViolationType($last30Days, $currentDate, 'Others') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $yearToDate }}', '{{ $currentDate }}', 'Others', 'Year-to-Date')">{{ countViolationType($yearToDate, $currentDate, 'Others') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $last365Days }}', '{{ $currentDate }}', 'Others', 'Last 365 Days')">{{ countViolationType($last365Days, $currentDate, 'Others') }}</a></td>
                            <td><a href="javascript:void(0)" class="fw-bold" onclick="showViolationDetails('{{ $currentDate }}', '{{ $currentDate }}', 'Others', 'Total')">{{ countViolationType($currentDate, $currentDate, 'Others') }}</a></td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Total</th>
                            <th>{{ countTotalViolationType($last1Days, $currentDate) }}</th>
                            <th>{{ countTotalViolationType($last7Days, $currentDate) }}</th>
                            <th>{{ countTotalViolationType($monthToDate, $currentDate) }}</th>
                            <th>{{ countTotalViolationType($last30Days, $currentDate) }}</th>
                            <th>{{ countTotalViolationType($yearToDate, $currentDate) }}</th>
                            <th>{{ countTotalViolationType($last365Days, $currentDate) }}</th>
                            <th>{{ countTotalViolationType($currentDate, $currentDate) }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="veiwViolationDetails" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Violation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>S. No</th>
                                <th>Flying Sortie</th>
                                <th>Date</th>
                                <th>Pilot</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody id="results">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="reupdateViolationDetails" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Violation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <lable>Comments</lable>
                                <textarea name="comments" id="comments" class="form-control"></textarea>
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                    <button type="button" onclick="updateData();" class="btn btn-primary">Update</button> 
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
                        url: "{{route('app.fdtl.list')}}",
                        type: 'POST',
                        data:{"_token": "{{ csrf_token() }}"},
                    },
                    "initComplete": function(){

                    }
                });
            }
            dataList();
        </script>
        <script>
            function showViolationDetails(from, to, violation_type,period)
            {
                localStorage.setItem('from', from.toString());
                localStorage.setItem('to', to.toString());
                localStorage.setItem('violation_type', violation_type.toString());
                localStorage.setItem('period', period.toString());
                // console.log('Stored values in local storage:', from, to, violation_type, period);
                $.ajax({
                    url: "{{route('app.fdtl.violation-details')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "from": from,
                        "to": to,
                        "violation_type": violation_type
                    },
                    success: function(response) {
                        if (response.success) {
                            var titleWords = violation_type.split('_').map(function(word) {
                                return word.charAt(0).toUpperCase() + word.slice(1);
                            });
                            var title = titleWords.join(' ');
                            $('#results').html(response.data);
                            $('#veiwViolationDetails').modal('show');
                            $('#veiwViolationDetails').find('.modal-title').text(period + '  ' + title + ' Violation Details');
                        }
                    }
                });

            }
            
            function updateException(id, is_exception)
            {
                $('#veiwViolationDetails').modal('hide');
                swal({
                        title: "Info",
                        text: "Please enter your message:",
                        icon: "info",
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your message here",
                                type: "text",
                            },
                        },
                        buttons: {
                            cancel: {
                                visible: true,
                                text: "Cancel",
                                className: "",  // Optionally, add classes for styling
                                closeModal: true,
                            },
                            confirm: {
                                text: "Submit",
                                value: true,
                                visible: true,
                                className: "",  // Optionally, add classes for styling
                                closeModal: true
                            }
                        }
                    }).then((value) => {
                        if (value !== null) {
                         $.ajax({
                                url: "{{route('app.fdtl.updateException')}}",
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id": id,
                                    "is_exception": is_exception,"comments":value
                                },
                                success: function(response) {
                                    if (response.success) {
                                        success(response.message);
                                        var storedFrom = localStorage.getItem('from');
                                        var storedTo = localStorage.getItem('to');
                                        var storedViolationType = localStorage.getItem('violation_type');
                                        var storedPeriod = localStorage.getItem('period');
                                        $('#veiwViolationDetails').modal('show');
                                        showViolationDetails(storedFrom, storedTo, storedViolationType,storedPeriod);
                                    }
                                }
                            });
                        }else{
                            swal("Cancelled", "Your action has been cancelled!", "error");
                        }
                        // Check if 'value' is not null (meaning the user clicked 'Submit' and not 'Cancel')
                        // if (value !== null) {
                        //     swal({
                        //         title: "Success",
                        //         text: "You entered: " + value,
                        //         icon: "success",
                        //         button: "OK",
                        //     });
                        // } else {
                        //     swal("Cancelled", "Your action has been cancelled!", "error");
                        // }
                    });
            }
             
            function reUpdateException(id)
            {
                $.ajax({
                    url: "{{route('app.fdtl.violation-update')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                       id
                    },
                    success: function(response) {
                        if(response.success) {
                             $('#veiwViolationDetails').modal('hide');
                            $('#comments').html(response.data.comments);
                            $('#id').val(response.data.id);
                            $('#reupdateViolationDetails').modal('show');
                        }
                    }
                });
            }
            function updateData()
            {
                $.ajax({
                    url: "{{route('app.fdtl.update.re-update')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                       id:$('#id').val(),comments:$('#comments').val()
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#reupdateViolationDetails').modal('hide');
                            success(response.message);  
                        }else{
                            success(response.message); 
                        }
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
