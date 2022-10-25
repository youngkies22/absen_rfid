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
			<form id="update" method="post" data-route="{{ url('crew/update-sekolah/'.$idskl) }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">NPSN Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklNpsn }}" required type="text" name="npsn_skl" class="form-control" placeholder="NPSN Sekolah">
								</div>
							</div>
						
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kode Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklKode }}" required type="text" name="kode_skl" class="form-control" placeholder="Kode Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklNama }}" required type="text" name="nama_skl" class="form-control" placeholder="Nama Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Kepala Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklKepalaSekolah }}" required type="text" name="kepsek" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">NIP Kepala Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklNipKepsek }}" required type="text" name="nip" class="form-control" >
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Alamat Sekolah</label>
								<div class="col-lg-9">
									<textarea class="form-control" name="alamat_skl" placeholder="Alamat Sekolah">{{ $getSkl->sklAlamat }}</textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Provinsi</label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklProvinsi }}"  type="text" name="provinsi" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kabupaten</label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklKabupaten }}"  type="text" name="kabupaten" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kecamatan</label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklKecamatan }}"  type="text" name="kecamatan" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jam Masuk <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklJamIn }}" required type="text" name="jamin" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jam Pulang <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input value="{{ $getSkl->sklJamOut }}" required type="text" name="jamout" class="form-control" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Cek Jam Absen <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Status"  name="cekjam" id="cekjam"  class="form-control select" data-fouc>
										<option></option>
										<option @if($getSkl->sklCekJamAbsen == 1) selected @endif  value="1">AKTIF</option>
										<option @if($getSkl->sklCekJamAbsen == 0) selected @endif  value="0">TIDAK</option>
										</select>
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihat.sekolah') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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

