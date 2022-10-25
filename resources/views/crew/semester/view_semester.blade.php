@extends('master')
@section('titile', 'Lihat Semester')
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
			<ul class="nav nav-tabs nav-tabs-highlight">
				<li class="nav-item"><a href="#left-icon-tab1" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-1"></i> Lihat Data Semester</a></li>
				@if(AksiInsert())
				<li class="nav-item"><a href="#left-icon-tab2" class="nav-link" data-toggle="tab"><i class="icon-plus3 mr-1"></i> Tambah Semester</a></li>
				</li>
				@endif
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="left-icon-tab1">
					<div class="row">
						<div class="col-md-12">
							<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled">
								<thead style="background-color: #05405a; color: white;">
									<tr>
										<th>Kode Semester</th>
										<th>Nama Semester</th>
										<th>Tahun Ajaran</th>
										
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($getSemester as $sm)
										<tr>
											<td>{{$sm->smKode}}</td>
											<td>{{$sm->smNama}}</td>
											<td>{{$sm->tahun_ajaran->tajrNama}}</td>
											
											<td>
												@if ($sm->smIsActive==1)
												<span class="badge badge-primary">Aktif</span>
												@else
												<span class="badge badge-danger">Tidak Aktif</span>
												@endif
											</td>
											<td>
												@if(AksiInsert())
												<button data-idd="{{ encrypt_url($sm->smId) }}" 
													title="Edit Data" data-kode="{{$sm->smKode}}" 
													data-ta="{{ $sm->smTajrId }}"
													data-nama="{{$sm->smNama}}"  
													data-status="{{$sm->smIsActive}}" 
													type="button" 
													class="btnmodal btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" 
													data-toggle="modal" data-target="#modal_form_vertical"><i class="icon-pencil7"></i>
												</button>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="left-icon-tab2">
					@if(AksiInsert())
					<div class="row">
						<div class="col-md-12">
						
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Tahun Ajaran</label>
								<div class="col-lg-9">
								<select required data-placeholder="Pilih Tahun Ajaran"  name="ta" id="ta"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getTa as $ta)
									<option value="{{ $ta->tajrId}}">{{ $ta->tajrNama}}</option>
									@endforeach
								</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Kode Semester</label>
								<div class="col-lg-9">
									<input required type="text" name="smkode" id="smkode" class="form-control" placeholder="Misal SMGE20">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Nama Semester</label>
								<div class="col-lg-9">
									<input required type="text" name="smnama" id="smnama" class="form-control" placeholder="Nama Semester">
								</div>
							</div>

							<button id="simpandata" data-id="{{ route('insert.semester') }}" class="btn btn-primary"><i class="icon-paperplane"></i> Simpan</button>
						</div>
					</div>
					@endif
				</div>
					<!-- Vertical form modal -->
				<div id="modal_form_vertical" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

								<div class="modal-body">
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label>Kode Semster</label>
												<input type="hidden" id="eta_kode_id"  class="form-control">
												<input required type="text" id="eta_kode"  class="form-control">
											</div>

											<div class="col-sm-6">
												<label>Nama Semester</label>
												<input required type="text" id="eta_nama" class="form-control">
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label>Status</label>
												<select data-placeholder="Pilih Status"  name="eta_status" id="eta_status"  class="form-control select-fixed-single" data-fouc>
													<option value="0">Tidak Aktif</option>
													<option value="1">Aktif</option>
												</select>
											</div>
										</div>
									</div>

									
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
									<button data-id="{{ route('update.semester') }}" id="esimpandata" class="btn bg-primary">Update Data</button>
								</div>
						</div>
					</div>
				</div>
				<!-- /vertical form modal -->
			</div>
		</div>	
	</div>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
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

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
{{-- js datables agar ada button copy excel --}}
<script src="{{ asset('global_assets/js/demo_pages/datatables_extension_buttons_html5.js') }}"></script>
{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
	$(document).on('click','#simpandata',function(e){
		var urlid = $(this).data("id");
		var ta = $("#ta").val();
		var smkode = $("#smkode").val();
		var smnama = $("#smnama").val();
		var token = $("meta[name='csrf-token']").attr("content");
		$.ajax({
			type:'POST',
			url:urlid,
			data: { _token: token,ta:ta,smkode:smkode,smnama:smnama},
			success:function(respon){
				console.log(respon);
				if(respon.success){
					new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					location.reload();
					
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
	$(document).on('click','.btnmodal',function(e){
		var eta_id = $(this).data("idd");
		var eta_kode = $(this).data("kode");
		var eta_nama = $(this).data("nama");
		
		var eta_status = $(this).data("status");
		$("#eta_kode_id").val(eta_id);
		$("#eta_kode").val(eta_kode);
		$("#eta_nama").val(eta_nama);
		$("#eta_status").val(eta_status).change();
	
	});
	$(document).on('click','#esimpandata',function(e){
		
		var urlid = $(this).data("id");
		var id = $("#eta_kode_id").val();
		var smkode = $("#eta_kode").val();
		var smnama = $("#eta_nama").val();
		var status = $("#eta_status").val();
		var token = $("meta[name='csrf-token']").attr("content");
		$.ajax({
			type:'POST',
			url:urlid,
			data: { _token: token,kode:smkode,nama:smnama,id:id,status:status},
			success:function(respon){
				console.log(respon);
				if(respon.success){
					new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					location.reload();
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

	var tabel = $('#tabel').DataTable({
		processing: true,
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		responsive: true,
		autoWidth: false,
		buttons: 
			{            
				buttons: 
				[
					{
						extend: 'copyHtml5',
						className: 'btn btn-light',
						exportOptions: {
							columns: [ 0, ':visible' ]
						}
					},
					{
						extend: 'excelHtml5',
						className: 'btn btn-light',
						exportOptions: {
							columns: [ 0, ':visible' ]
						}
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i>',
						className: 'btn bg-blue btn-icon dropdown-toggle'
					},
					{
						extend: 'print',
						className: 'btn btn-light',
					},
					// {
					//     extend: 'csv',
					 //     className: 'btn btn-light',
					// }            
			 ]
			},
		
	
});

</script>
@endpush

