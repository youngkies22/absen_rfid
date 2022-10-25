@extends('master')
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
			<b style="color: red">* Tidak Boleh Kosong</b>
			<div id="info"></div>
			<form id="update" data-route="{{ route('insert.karu.rfid')  }}" data-url="{{ route('view.kartu.not.found') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
						
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">ID KARTU RFID<b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input readonly value="{{ $id  }}" required type="hidden" name="id" >
									<input readonly value="{{ $data->knfKartuId  }}" required type="text" name="idkartu" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">KODE MESIN<b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input readonly value="{{ $data->knfKodeMesin}}" required type="text" name="kode_mesin" class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">NAMA MESIN<b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input readonly value="{{ $data->knfNamaMesin}}" required type="text" name="nama_mesin" class="form-control">
								</div>
							</div>
						
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">NAMA USER<b style="color: red">*</b></label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih User"  name="username" id="username"  class="form-control select-search" data-fouc>
											<option></option>
											@foreach ($data_user as $data)
												<option value="{{ $data['username']}}">{{ $data['namafull'] }} </option>
											@endforeach
											</select>
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('view.kartu.not.found') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
				</div>
					
			</form>
			
		</div>
	</div>
	<!-- /2 columns form -->
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
@endpush
@push('jsku')
<script type="text/javascript">
	$('#update').submit(function(e){
			var route = $(this).data('route');
			var url = $(this).data('url');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'POST',
				url:route,
				data:data_form.serialize(),
				success:function(respon){
					console.log(respon);
					if(respon.save){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					setInterval(function(){ 
						//location.reload(url);
						window.location.href = url;
					}, 1000);
					}
				
				},
				error: function (respon) {
						new Noty({
							theme: ' alert alert-danger alert-styled-left p-0',
							text: respon.responseJSON.message,
							type: 'error',
							progressBar: false,
							closeWith: ['button']
							}).show();
				}
			});

		});	
</script>
@endpush

