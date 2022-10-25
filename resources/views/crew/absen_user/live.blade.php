
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="{{ env('AKADEMIK_KEYWORDS') }}" />
	<meta name="description" content="{{ env('AKADEMIK_DESKRIPSI') }}" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>ABSENSIS</title>

	<!-- Global stylesheets -->
	<link rel="shortcut icon" rel="icon" type="image/gif/png" href="{{ asset('image/budut.png') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/material/icons.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href="{{ asset('global_assets/js/plugins/datetimepicker/jquery.datetimepicker.css')}}" />
	<!-- /global stylesheets -->
	


	<!-- Core JS files -->
	<script src="{{ asset('global_assets/js/main/jquery.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/ripple.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/sticky.min.js')}}"></script>
	

	<!-- /core JS files -->

	<!-- Theme JS files -->
	@stack('js_atas')
	<!-- pluginnya datatables-->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.js')}}"></script>
	<style type="text/css">
		.color_mryes{
			background-color: #05405a; 
			color: white;
		}
		.center-table-mryes{
			text-align: center;
		}
		.loading {
	  position: absolute;
	  left: 50%;
	  top: 70%;
	  transform: translate(-50%,-50%);
	  font: 14px arial;
	  }
	  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('{{ asset('global_assets/images/ajax-loader.gif')}}') 50% 50% no-repeat rgb(249, 249, 249);
    opacity: .8;
}
	</style>
	<!-- /theme JS files -->

	{{-- pusher --}}
	<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
      cluster: 'ap1'
    });
  </script>
	{{-- pusher --}}

</head>

<body>
	<div id='pesan'></div>
	<div class='loader'>
		<div class="loading">
   	 <p id="pesanku" >Proses...</p>
  	</div>
	</div>

	
	<!-- Page content -->
	<div class="page-content">
	
		<!-- /main sidebar -->
		<!-- Main content -->
		<div class="content-wrapper">
			<div class="content">
				<!-- 2 columns form -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">ABSEN GURU JURNAL : {{ strtoupper(hariIndo(date("l",strtotime(now())))) }}, {{ tgl_indo(date("Y-m-d",strtotime(now()))) }}</h5>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
								<a class="list-icons-item" data-action="reload"></a>
								<a class="list-icons-item" data-action="remove"></a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<!--<span class="text-muted">Setiap 1 menit data akan di reload automatis</span>-->
						<!--<br>-->
						
						<button class="btn btn-danger" id="buttonrefres"><i class="icon-spinner9 spinner"></i> REFRESH</button> <span class="text-muted">Di Perbarui :</span>
						<span id="time" class="text-muted"></span>
					
						<div class="row">
							<div class="col-md-12">
								<table id="tabel" class="table table-striped table-bordered  ">
									<thead style="background-color: #05405a; color: white;">
										<tr>
											<th>TIMESTAMP</th>
											<th>NAMA GURU</th>
											{{-- <th>TANGGAL</th>
											<th>HARI</th> --}}
											
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
						
					</div>
			
				<!-- /2 columns form -->
				<!-- /dashboard content -->
			</div>
			
		</div>
		
		<!-- /main content -->
	</div>
	<!-- /page content -->
	{{-- js --}}
	<script src="{{ asset('global_assets/js/demo_pages/navbar_multiple_sticky.js')}}"></script>
	<script type="text/javascript">
	$(document).ready(function(){
            $("#buttonrefres").click(function(){
                location.reload(true);
            });
        });
	$(document).ready(function(){
		$('.loader').fadeOut('slow');
	});
	
	var tabel = $('#tabel').DataTable({
		processing: true,
		serverSide: true,
		searching: false,
		lengthMenu: [
            [20, 40, 60, -1],
            [20, 40, 60, 'All'],
    ],
		responsive: true,
		autoWidth: false,
		scrollX: false,
		ajax: '{{ url("json/json-live-absen-guru-monitor/jsonSmkBudiUtomo") }}',
		buttons: 
			{            
				buttons: 
				[
					{
						extend: 'excelHtml5',
						className: 'btn btn-light',
						autoFilter: true,
						sheetName: 'DATA AKTIFASI JURNAL GURU',
						title: 'DATA JURNAL GURU MAPEL HARI INI {{ date("d-m-Y",strtotime(now())) }}',
						//messageTop: 'ROMBEL x MATA PELAJARAN x',
						//messageBottom: '123',
						// exportOptions: {
						// 	columns: [ 3, ':visible' ]
						// }
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i>',
						className: 'btn bg-blue btn-icon dropdown-toggle'
					},
					        
			 ]
			},
		columns: [
		
			// { "data": "no" },
			{ "data": "tanggalWaktu" },
			{ "data": "hgNamaFull" },
			// { "data": "tanggal" },
			// { "data": "hari" },
			
			
		],
		responsive: { details: { type: 'column' } },
		
		order: [0, 'desc'],
	});

	//get data hari ini 
	setInterval( function () {
    tabel.ajax.reload( null, false ); // user paging is not reset on reload
    
	}, 10000 ); //setiap 1 menit reload 60000
	// 10 detik 10000
	
    function startTimer(duration, display) {
    var start = Date.now(),
        diff,
        minutes,
        seconds;
    function timer() {
        // get the number of seconds that have elapsed since 
        // startTimer() was called
        diff = duration - (((Date.now() - start) / 1000) | 0);

        // does the same job as parseInt truncates the float
        minutes = (diff / 60) | 0;
        seconds = (diff % 60) | 0;

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds; 

        if (diff <= 0) {
            // add one second so that the count down starts at the full duration
            // example 05:00 not 04:59
            start = Date.now() + 1000;
        }
    };
    // we don't want to wait a full second before the timer starts
    timer();
    setInterval(timer, 1000);
    }
    
    window.onload = function () {
        //var fiveMinutes = 10 * 5,
        var fiveMinutes = 2 * 5,
            display = document.querySelector('#time');
        startTimer(fiveMinutes, display);
    };
	
	</script>
	
	
</body>
</html>
