@extends('master')
@section('content')
<!-- Content area -->
<div class="content">
	<?php $fullnama = strtoupper(Auth::user()->admFirstName.' '.Auth::user()->admLastName) ?>
		<div class="row">
			<span id="result"></span>
			<div class="col-xl-3 col-sm-6">
				<div class="card">
					<div class="card-img-actions mx-1 mt-1" id="fotoprofile">
						<img class="card-img img-fluid " src="<?php echo GetFotoProfile().'?date='.time(); ?>" alt="{{$fullnama}}">
						<div class="card-img-actions-overlay card-img">
							<p>{{$fullnama}}</p>
						</div>
					</div>

					<div class="card-body text-center">
						
						{{-- upload foto --}}
						<span class="text-muted">Upload Foto Profile</span><br>

						<form id="upload" data-route="{{ route('upload.foto.admin') }}" enctype="multipart/form-data" >
							<input type="hidden" name="id" value="{{$idUser}}">
							<input required name="foto_upload" id="foto_upload" type="file" class="file-input" data-show-preview="false" data-show-remove="false" data-show-upload="false">
							<div class="text-right"><br>
									<button type="submit" class="btn btn-info"><i class="icon-paperplane"></i> Upload</button>
								</div>
						</form>
						
						
						
					</div>
				</div>
			</div>
			<div class="col-xl-9 col-sm-6">
				<!-- Account settings -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title"><b>Account settings</b></h5>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
								<a class="list-icons-item" data-action="reload"></a>
								<a class="list-icons-item" data-action="remove"></a>
							</div>
						</div>
					</div>
					<form id="form" data-route="{{ url('crew/update-profile/'.$idUser) }}" >
					<div class="card-body">
						
							<div class="form-group">
								<div class="row">
									
									<div class="col-md-6">
										<label>Username</label>
										<input disabled="disabled" required  value="{{Auth::user()->admUsername}}" type="text" name="username" class="form-control">
									</div>

									<div class="col-md-6">
										<label>Status User</label>
										<select disabled="disabled" data-placeholder="Pilih Status"  name="status" id="status"  class="form-control select" data-fouc>
										<option {{selectAktif(Auth::user()->admIsActive,1)}} >Aktif</option>
										<option {{selectAktif(Auth::user()->admIsActive,0)}}>Tidak Aktif</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label>Nama </label>
										<input name="nama" required value="{{Auth::user()->admFullName}}" type="text" class="form-control">
									</div>

									
								</div>
							</div>
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label>New password</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="icon-lock"></i></span>
											</div>
											<input name="newpassword" type="text" class="form-control"  placeholder="Masukan Password Baru">
										</div>
										<span class="mb-3 text-muted">Jika ingin mengganti Passwordnya</span>
									</div>
								</div>
							</div>

							{{-- <div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label>Profile visibility</label>

										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" name="visibility" class="form-input-styled" checked data-fouc>
												Visible to everyone
											</label>
										</div>

										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" name="visibility" class="form-input-styled" data-fouc>
												Visible to friends only
											</label>
										</div>

										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" name="visibility" class="form-input-styled" data-fouc>
												Visible to my connections only
											</label>
										</div>

										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" name="visibility" class="form-input-styled" data-fouc>
												Visible to my colleagues only
											</label>
										</div>
									</div>

									<div class="col-md-6">
										<label>Notifications</label>

										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-input-styled" checked data-fouc>
												Password expiration notification
											</label>
										</div>

										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-input-styled" checked data-fouc>
												New message notification
											</label>
										</div>

										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-input-styled" checked data-fouc>
												New task notification
											</label>
										</div>

										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-input-styled">
												New contact request notification
											</label>
										</div>
									</div>
								</div>
							</div> --}}

							<div class="text-right">
								<button type="submit" class="btn btn-primary"><i class="icon-paperplane"></i> Simpan</button>
							</div>
					</div>
					</form>
				</div>
				<!-- /account settings -->
			</div>
		</div>
	

</div>
<!-- /content area -->
@endsection
@push('js_atas')

<!-- pluginnya datatables-->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

<!-- pluginnya form select-->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<!-- pluginnya buat export -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

{{-- Upload --}}
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
	// {{-- Input upload --}}
	$('.file-input').fileinput({
		browseLabel: 'Cari',
		browseIcon: '<i class="icon-file-plus mr-2"></i>',
		//uploadIcon: '<i class="icon-file-upload2 "></i>',
		//removeIcon: '<i class="icon-cross2 font-size-base"></i>',
		initialCaption: "Tidak Ada File",
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
	$('#form').submit(function(e){
		var route = $(this).data('route');
		var data_form = $(this);
		e.preventDefault();
		$.ajax({
			type:'PUT',
			url:route,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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

