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
			<div id="info"></div>
			<form id="insert" method="post" data-route="{{ route('update.akun.admin') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<input type="hidden" value="{{ $id }}" name="id" id="id" />
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">FULL NAME</label>
								<div class="col-lg-9">
									<input  type="text" name="name" value="{{ $dataAdmin->admFullName }}" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">USERNAME</label>
								<div class="col-lg-9">
									<input  type="text" name="username" value="{{ $dataAdmin->admUsername }}" class="form-control" placeholder="Masukan Username ">
								</div>
							</div>
						
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Password</label>
								<div class="col-lg-9">
									<input  type="text" name="password" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Hak Akses</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih "  name="akses" id="Guru"  class="form-control select" data-fouc>
										<option></option>
										<option @if($dataAdmin->admKode == "ADMIN") selected @endif value="ADMIN">ADMIN</option>
										<option @if($dataAdmin->admKode == "KEPSEK") selected @endif value="KEPSEK">KEPSEK</option>
										</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">INSERT</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih "  name="insert"  class="form-control select" data-fouc>
										<option></option>
										<option @if($dataAdmin->admInsert == 1) selected @endif value="1">AKTIF</option>
										<option @if($dataAdmin->admInsert == 0) selected @endif value="0">TIDAK</option>
										</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">UPDATE</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih "  name="update"  class="form-control select" data-fouc>
										<option></option>
										<option @if($dataAdmin->admUpdate == 1) selected @endif value="1">AKTIF</option>
										<option @if($dataAdmin->admUpdate == 0) selected @endif value="0">TIDAK</option>
										</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">DELETE</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih "  name="delete" class="form-control select" data-fouc>
										<option></option>
										<option @if($dataAdmin->admDelete == 1) selected @endif value="1">AKTIF</option>
										<option @if($dataAdmin->admDelete == 0) selected @endif value="0">TIDAK</option>
										</select>
								</div>
							</div>

						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('list.akun.admin') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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
@endpush
@push('jsku')
<script type="text/javascript">
	$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'PUT',
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

