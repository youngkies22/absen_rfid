
@extends('master')
@section('titile', 'Tambah Jurusan')
@section('content')
<!-- Content area -->
<div class="content" id="content">

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
			<div class="row">
					<div class="col-md-8"><i style="color: red">* Wajib Di Isi </i>  <i class="icon-info22 ml-1" data-popup="tooltip" title="Guru Juga Bisa Login Untuk Mengisi atau Melengkapi Data Profile" data-placement="bottom"></i></div>
				</div>
				<hr class="mt-1 mb-1"/>
			
				<div class="row">
					<div class="col-xl-3 col-sm-6">
						<div class="card">
							<div class="card-img-actions mx-1 mt-1" id="fotoprofile">
								<img class="card-img img-fluid " src="<?php echo GetFotoProfile($guru->ugrFotoProfile).'?date='.time(); ?>" alt="{{$guru->ugrFullName}}">
								<div class="card-img-actions-overlay card-img">
									<p>{{$guru->ugrFullName}}</p>
								</div>
							</div>
		
							<div class="card-body text-center">
								
								{{-- upload foto --}}
								<span class="text-muted">Upload Foto Guru</span><br>
		
								<form id="upload" data-route="{{ route('upload.foto.guru') }}" enctype="multipart/form-data" >
									<input type="hidden" name="id" value="{{encrypt_url($guru->ugrId)}}">
									<input required name="foto_upload" id="foto_upload" type="file" class="file-input" data-show-preview="false" data-show-remove="false" data-show-upload="false">
									<div class="text-right"><br>
											<button type="submit" class="btn btn-info"><i class="icon-paperplane"></i> Upload</button>
										</div>
								</form>
								
								
								
							</div>
						</div>
					</div>
					{{-- kolom ke 1 --}}
					<form id="insert" data-route="{{ url('crew/update-guru/'.$idguru) }}">
						{{ csrf_field() }}
						<div class="col-xl-12 col-sm-6">
							<fieldset>
								<div class="content-divider text-muted"><span class="px-2">Data Akun Pengguna</span></div>
								
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">Username Guru *</label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrUsername}}" type="hidden" name="ugrUsername2" class="form-control" placeholder="Username Guru">
										<input value="{{ $guru->ugrUsername}}" required type="text" name="ugrUsername" class="form-control" placeholder="Username Guru">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">Gelar Depan </label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrGelarDepan}}" type="text" name="gdepan" class="form-control" placeholder="Nama Depan Guru">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">Gelar Belakang</label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrGelarBelakang}}" type="text" name="gbelakang" class="form-control" placeholder="Nama Depan Guru">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">Nama Lengkap *</label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrFullName}}" type="text" name="fullname" class="form-control" placeholder="Nama Lengkap Guru">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">NIP</label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrNip}}" type="text" name="nip" class="form-control" value="-">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">No HP </label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrHp}}" type="text" name="nohp" class="form-control" placeholder="Nomor Hp">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">No WA </label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrWa}}" type="text" name="nowa" class="form-control" placeholder="Nomor WA">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">Id Telegram</label>
									<div class="col-lg-6">
										<input value="{{ $guru->ugrTelegram}}" type="text" name="telegram" class="form-control" >
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-form-label">Jenis Kelamin <i style="color: red">*</i></label>
									<div class="col-lg-6">
										<select required name="jsk" class="form-control select-fixed-single" data-fouc data-placeholder="Pilih Jenis Kelamin">
											<optgroup label="Pilih">
												<option></option>
												<option @if($guru->ugrJsk == "L") selected  @endif value="L" >Laki-Laki</option>
												<option  @if($guru->ugrJsk == "P") selected  @endif value="P" value="P">Perempuan</option>
											</optgroup>
										</select>
									</div>
								</div>
								
								
							</fieldset>
							<div class="text-left">
								@if(AksiUpdate())
								<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
								<a href="{{ route('lihat.guru') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
								@else
								<a class="btn bg-danger-600 btn-ladda btn-ladda-progress ladda-button legitRipple "><i class="icon-cross2"></i> NO AKSES</a>
								@endif
							</div>
						</div>
					</form>

				</div>
			
			
			
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
			type:'PUT',
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
	$('#upload').submit(function(e){
		var route = $(this).data('route');
		var data = new FormData(this);
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:route,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data:data,
			processData: false,  // Important!
      contentType: false,
      cache: false,
      beforeSend: function() {
      	$("#pesanku").text("Proses Upload ...!");
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
					//setInterval(function(){ location.reload(true); }, 1000);
					document.getElementById("foto_upload").form.reset();
					$("#fotoprofile").load(location.href + " #fotoprofile");
					$("#fotoprofilemenu").load(location.href + " #fotoprofilemenu");
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
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}

		});
	});
</script>
@endpush

