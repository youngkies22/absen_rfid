@extends('master')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- from import data siswa -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">{!!$label!!}</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<span id="result">
			</span>

			<div class="row">
					<div class="col-md-8">Format File Excel Upload Data Guru.xlsx{{-- <i class="icon-info22 ml-1" data-popup="tooltip" title="Guru Juga Bisa Login Untuk Mengisi atau Melengkapi Data Profile" data-placement="bottom"></i> --}}</div>
				</div>
				<hr class="mt-1 mb-1"/>
			<form id="upload"  data-route="{{ route('import.data.guru') }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Pilih File Excel </label>
								<div class="col-lg-9">
									<input required type="file" name="import_data_guru" class="form-control" >
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Upload Data <i class="icon-upload4 ml-2"></i></button>
				</div>
				download template Excel
				<br><a href="{{ asset('importdataguru.xlsx'); }}">Klik di Sini</a>
				{{-- <div class="progress progress-striped active ">
					<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
						<span class="sr-only"></span>
					</div>
				</div> --}}
				
			</form>
		<span class="form-text text-muted">
			Catatan : Import data di gunakan untuk menambah data secara masal <br>
			Pastikan data sudah benar dan fix sebelum di import
		</span>
		</div>
	</div>
	<!-- from import insert data siswa -->

	

	<!-- /dashboard content -->
</div>
<!-- /content area -->
@endsection
@push('js_atas')
<!-- pluginnya -->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

<!-- Load Moment.js extension -->
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">

	
	
	$('#upload').submit(function(e){
		var route = $(this).data('route');
		var data = new FormData(this);
		e.preventDefault();
		
		$.ajax({
			type:'POST',
			url:route,
			data:data,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			processData: false,  // Important!
      contentType: false,
      cache: false,
			//async: true,
      beforeSend: function() {
      	$("#pesanku").text("Proses Import ...!");
      	$('.loader').show();
      },
			success:function(respon){
				//console.log(respon);
				if(respon.save){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					$("#result").append(' <div class="alert alert-success alert-block">'
					+'<button type="button" class="close" data-dismiss="alert">×</button>'
					+'<strong>'+respon.save+'</strong></div>');
					//setInterval(function(){ location.reload(true); }, 1000);
				}
				if(respon.error){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
					$("#result").append(' <div class="alert alert-danger alert-block">'
					+'<button type="button" class="close" data-dismiss="alert">×</button>'
					+'<strong>'+respon.error+'</strong></div>');
				}
				if(respon.danger){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-warning alert-styled-left p-0',
						text: respon.danger,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
					$("#result").append(' <div class="alert alert-warning alert-block">'
					+'<button type="button" class="close" data-dismiss="alert">×</button>'
					+'<strong>'+respon.danger+'</strong></div>');
				}
			},
			error: function (e) {
				$('.loader').hide();
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}

		});
	});
</script>

@endpush

