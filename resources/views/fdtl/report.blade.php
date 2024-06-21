<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">FDTL Report </li>
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
        <div class="card-body">
            <form action="{{route('app.get.fdtl.report')}}" method="post" id="reportForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="mt-4"><b>Select Report Duration</b></label>
                            <input type="hidden" name="id" id="user_id" value="{{$id}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{request()->from_date}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{request()->to_date}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-3">
                            <button type="submit" class="btn btn-primary btn-sm btn-block m-2">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">FDTL report : {{$user->salutation.' '.$user->name}}</h3>
            <div>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
            <a href="javascript:void(0);" onclick="printSectedDiv();" class="btn btn-info btn-sm p-2">Print</a>
            </div>
        </div>
        <div class="card-body p-1 table-responsive" id="reportTable">
            
        </div>
    </div>

    <x-slot name="js">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
        <script>
            $('#reportForm').submit(function(e){
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let data = form.serialize();
                $.ajax({
                    url:url,
                    type:method,
                    data:data,
                    beforeSend:function(data){
                         $('#reportTable').html('<div style="text-align: center;width: 100%;height: 100%;">' 
                            +'<img src="' + "{{ asset('assets/img/orange-loader.gif') }}" + '" style="width: 100px;">'
                            +'</div>');
                    }, 
                    success:function(data){
                        $('#reportTable').html(data);
                    },
                    error:function(err){
                        console.log(err);
                    },
                    complete:function(err){
                        console.log(err);
                    }
                })

            });
            
            function printSectedDiv()
            {   
                let user_id = $('#user_id').val();
                if(user_id == '')
                {
                    alert('Please select user');
                    return false;
                }
                let from_date = $('#from_date').val();
                if(from_date == '')
                {
                    alert('Please select from date');
                    return false;
                }
                let to_date = $('#to_date').val();
                if(to_date == '')
                {
                    alert('Please select to date');
                    return false;
                }
                window.open("{{url('admin/fdtl/report/print')}}"+'/'+user_id+'/'+from_date+'/'+to_date,'printDiv','height=900,width=900');

                // window.open("{{route('app.print.fdtl.report')}}?id={{$id}}&from_date={{request()->from_date}}&to_date={{request()->to_date}}");

                // $('.table-bordered').css('font-size','22px');
                // $('.showHodeSection').css('display','block');
                // var HTML_Width = $("#printDiv").width();
                // var HTML_Height = $("#printDiv").height();
                // var top_left_margin = 15;
                // var PDF_Width = HTML_Width + (top_left_margin * 2);
                // var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
                // var canvas_image_width = HTML_Width;
                // var canvas_image_height = HTML_Height;
            
                // var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
            
                // html2canvas($("#printDiv")[0]).then(function (canvas) {
                //     var imgData = canvas.toDataURL("image/jpeg", 1.0);
                //     var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                //     pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                //     for (var i = 1; i <= totalPDFPages; i++) { 
                //         pdf.addPage(PDF_Width, PDF_Height);
                //         pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                //     }
                //     pdf.save("fdtl-report.pdf");
                //     // $('.showHodeSection').css('display','none');
                //     // $('.table-bordered').css('font-size','18px');
                //     // $(".html-content").hide();
                // });
            }
        </script>
    </x-slot>
</x-app-layout>