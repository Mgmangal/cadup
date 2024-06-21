<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">SFA </li>
            <li class="breadcrumb-item active">Reports </li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"
            rel="stylesheet">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}"
            rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">SFA Reports </h3>
        <div>
        <a href="{{route('app.sfa.download',encrypter('encrypt',$sfa_id))}}" class="btn btn-primary btn-md">Download</a>
        <a href="{{route('app.sfa')}}" class="btn btn-info btn-md">Back</a>
        </div>
    </div>
    <div class="card-body">
        <!-- first form -->
        <?php
        if(!empty($all_flying))
        {
            $total_time = 0;
            $total_amount = 0;
            $is_first_tbl=1;
            $page_break=1;
            $total_page = count($all_flying);
            $page_break_size = 1000;
            foreach($all_flying as $key=>$v)
            {
                if($is_first_tbl > 17){
                    $page_break_size = 1000;
                }
                ?>
                <?php
                    if($is_first_tbl==1)
                    {
                ?>
                <table class="table table-sm" style="width:100%">
                    <tr>
                        <td style="border-left: 1px solid;border-right: 1px solid;border-bottom: 1px solid;border-top: 1px solid;">
                            <p style="display: flex; font-size: 15px;padding-left: 33px;color: #000;margin:0; padding: 5px;">
                                <span style="font-weight: 900;">Name : <?php echo $pilot_name;?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span style="margin-left: 300px;font-weight: 900;">Designation : <?php echo $designation;?></span>
                            </p>
                        </td>
                    </tr>
                </table>
                <br>
                <?php
                    }
                ?>
                <?php
                if($page_break==1)
                {
                ?>
                <table class="table table-sm" style="width:100%;">
                <!--page-break-after: always;-->
                <?php
                }
                        
                if($is_first_tbl==1)
                {
                    $page_break=0;
                ?>
                    <tr>
                        <th style="height:1px;">S.No</th>
                        <th class="th-head">Date</th>
                        <th class="th-head">Aircraft</th>
                        <th class="th-head">A/C Type</th>
                        <th class="th-head">From</th>
                        <th class="th-head">To</th>
                        <th class="th-head">Block Time</th>
                        <th class="th-head">Flying in<br>Capacity<br>of A/C</th>
                        <th class="th-head">Rate/Hours<br>as in<br>G.O. <span>(₹)</span></th>
                        <th class="th-head">Amount <span>(₹)</span></th>
                        <th class="th-head">Remarks</th>
                    </tr>
                <?php
                } 
                if($page_break==1)
                {
                ?>
                    <tr>
                        <td style="height:17px; font-size: 13px; text-align: center;"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <!--<td class="td-body" style="font-weight: bold;"><?=date("H:i", $total_time-19800)?></td>-->
                        <td class="td-body" style="font-weight: bold;"><?=date("H:i", $total_time)?></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body" style="font-weight: bold;"> <?=number_format($total_amount,2)?></td>
                        <td class="td-body"></td>
                    </tr>
                <?php
                }
                $total_amount += round($v[9],2);
                $time_array = explode(":", $v[6]);
                $total_time += $time_array[0]*60*60 + $time_array[1]*60;
                ?>
                <tr>
                    <td class="td-body"><?=$v[0]?></td>
                    <td class="td-body"><?=$v[1]?></td>
                    <td class="td-body"><?=$v[2]?></td>
                    <td class="td-body"><?=$v[3]?></td>
                    <td class="td-body"><?=$v[4]?></td>
                    <td class="td-body"><?=$v[5]?></td>
                    <td class="td-body"><?=$v[6]?></td>
                    <td class="td-body"><?=$v[7]?></td>
                    <td class="td-body"><?=$v[8]?></td>
                    <td class="td-body"><?= number_format($v[9], 2)?></td>
                    <td class="td-body"><?=$v[10]?></td>
                </tr>
                <?php
                            
                if($is_first_tbl % $page_break_size==0 || $is_first_tbl == $total_page)
                {
                    $page_break=1;
                ?>
                <tr>
                    <td style="height:17px; font-size: 13px; text-align: center;"></td>
                    <td class="td-body"></td>
                    <td class="td-body"></td>
                    <td class="td-body"></td>
                    <td class="td-body"></td>
                    <td class="td-body" style="font-weight: bold;">Total</td>
                    <!--<td class="td-body" style="font-weight: bold;"><?=date("H:i", $total_time-19800)?></td>-->
                    <td class="td-body" style="font-weight: bold;"><?=date("H:i", $total_time)?></td>
                    <td class="td-body"></td>
                    <td class="td-body"></td>
                    <td class="td-body" style="font-weight: bold;"> <?=number_format(round($total_amount),2)?></td>
                    <td class="td-body"></td>
                </tr>
                </table>
                <?php echo $is_first_tbl<$total_page ? '<pagebreak />' : '' ; ?>
                <?php
                }else{
                    $page_break=0;
                }
                ?>
                <!-- end -->
                <?php        
                $is_first_tbl++;
            }
        }
        ?>
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
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}">
        </script>
        <script>
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            calendarWeeks: false,
            zIndexOffset: 9999,
            orientation: "bottom"
        });

        function dataList() {
            $('#datatableDefault').DataTable().destroy();
            var pilot = $('#pilots').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $('#datatableDefault').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                info: false,
                order: [
                    [2, 'desc']
                ],
                orderable: false,
                lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                responsive: true,
                fixedColumns: true,
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 5]
                    } // Disable order on first columns
                ],
                ajax: {
                    url: "{{route('app.sfa.list')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        pilot,
                        from_date,
                        to_date
                    },
                },
                fnRowCallback: function(nRow, aData, iDisplayIndex) {
                    var oSettings = this.fnSettings();
                    $("td:eq(0)", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
                },
                "initComplete": function() {},
                drawCallback: function(settings) {},
            });
        }
        $('.filter').on('change', function() {
            dataList();
        });
        dataList();
        </script>
    </x-slot>
</x-app-layout>