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
			<form id="insert" method="post" data-route="{{ route('insert.absen.finger') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							
						
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">User</label>
								<div class="col-lg-7">
									<select required data-placeholder="Pilih User"  name="user" id="user"  class="form-control select-search" data-fouc>
										<option></option>
										@foreach ($guru as $data)
										<option value="{{ $data['username'] }}"> {{ $data['namafull'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tanggal</label>
								<div class="col-lg-5">
									<input required type="text" name="tgl" class="form-control daterange-single" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jam Masuk</label>
								<div class="col-lg-5">
									<input value="{{ $jam_masuk }}" type='text' name='jamin' class='timer form-control' autocomplete='off' required='true' placeholder="08:00" />
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jam Pulang</label>
								<div class="col-lg-5">
									<input value="{{ $jam_pulang }}" type='text' name='jamout' class='timer form-control' autocomplete='off' required='true' placeholder="13:00" />
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Status Kehadiran</label>
								<div class="col-lg-5">
									<select required data-placeholder="Pilih Status"  name="status" id="status"  class="form-control select" data-fouc>
									<option></option>
									<option value="H" selected>HADIR</option>
									<option value="A">ALPHA</option>
									<option value="I">IZIN</option>
									<option value="S">SAKIT</option>
									<option value="T">TERLAMBAT</option>

									
									</select>
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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

{{-- Load Datetime --}}
<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>



{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/selectku.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush

@push('jsku')

<script type="text/javascript">
	$(document).ready(function(){
		$('.timer').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});
		
		$('#insert').submit(function(e){
				var route = $(this).data('route');
				var data_form = $(this);
				e.preventDefault();
				$.ajax({
					type:'POST',
					url:route,
					data:data_form.serialize(),
					success:function(respon){
						//console.log(respon);
						if(respon.save){
							new Noty({
							theme: ' alert alert-success alert-styled-left p-0 bg-white',
							text: respon.save,
							type: 'success',
							progressBar: false,
							closeWith: ['button']
						}).show();
							setInterval(function(){ location.reload(true); }, 500);

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

	}); 
</script>
@endpush

