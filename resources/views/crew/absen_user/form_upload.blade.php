@extends('master')
@section('titile', 'Tambah Jurusan')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- 2 columns form -->
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
			@if ($message = Session::get('save'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
      </div>
    	@endif
    	 @if ($message = Session::get('errorr'))
      <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ $message }}</strong>
      </div>
	    @endif

	    @if ($message = Session::get('error'))
	      <div class="alert alert-warning alert-block">
	        <button type="button" class="close" data-dismiss="alert">×</button> 
	        <strong>{{ $message }}</strong>
	    </div>
	    @endif

			<div class="row">
					<div class="col-md-8">Format File Upload Absen .csv<i class="icon-info22 ml-1" data-popup="tooltip" title="Pastikan Format File .csv agar proses upload data lebih ringan" data-placement="bottom"></i></div>
				</div>
				<hr class="mt-1 mb-1"/>
			{{-- <form id="insert" data-route="{{ route('upload.absen.finger') }}"> --}}
			<form action="{{ route('import.absen.finger') }}" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Pilih File</label>
								<div class="col-lg-9">
									<input required type="file" name="absen_finger" class="form-control" >
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihat.guru') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
				</div>
			</form>
		</div>
	</div>
	<!-- /2 columns form -->
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
	$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'POST',
				url:route,
				data:data_form.serialize(),
				success:function(respon){
					console.log(respon);
					if(respon.ugrUsername){
						new Noty({
						theme: 'alert alert-danger alert-styled-left p-0',
						text: respon.ugrUsername,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
					if(respon.save){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					setInterval(function(){ location.reload(true); }, 1000);
					}
					if(respon.error){
						new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
				}

			});

		});	
</script>
@endpush

