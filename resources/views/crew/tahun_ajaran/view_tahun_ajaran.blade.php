@extends('master')
@section('titile', 'Data Tahun Ajaran')
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
				<li class="nav-item"><a href="#left-icon-tab1" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-1"></i> Lihat Data Tahun Ajaran</a></li>
				@if(AksiInsert())
				<li class="nav-item"><a href="#left-icon-tab2" class="nav-link" data-toggle="tab"><i class="icon-plus3 mr-1"></i> Tambah Data Tahun Ajaran</a></li>
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
                <th>Kode Tahun Ajaran</th>
                <th>Nama Tahun Ajaran</th>
                <th>Nama Keterangan </th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($getTa as $ta)
                <tr>
                  <td>{{$ta->tajrKode}}</td>
                  <td>{{$ta->tajrNama}}</td>
                  <td>{{$ta->tajrDescription}}</td>
                  <td>
                    @if ($ta->tajrIsActive==1)
                    <span class="badge badge-primary">Aktif</span>
                    @else
                    <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                  </td>
                  <td>
										@if(AksiInsert())
										<button data-idd="{{ encrypt_url($ta->tajrId) }}" 
											title="Edit Data" data-takode="{{$ta->tajrKode}}" 
											data-tanama="{{$ta->tajrNama}}" data-taktr="{{$ta->tajrDescription}}" 
											data-tastatus="{{$ta->tajrIsActive}}" type="button" 
											class="btnmodal btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" 
											data-toggle="modal" data-target="#modal_form_vertical"><i class="icon-pencil7"></i>
										</button>
										@endif
									</td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
												<label>Kode Tahun Ajaran</label>
												<input type="hidden" id="eta_kode_id"  class="form-control">
												<input type="text" id="eta_kode"  class="form-control">
											</div>

											<div class="col-sm-6">
												<label>Nama Tahun Ajaran</label>
												<input type="text" id="eta_nama" class="form-control">
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label>Keterangan</label>
												<textarea class="form-control" id="eta_ktr">
													
												</textarea>
											</div>

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
									<button data-id="{{ route('update.tahun.ajaran') }}" id="esimpandata" class="btn bg-primary">Update Data</button>
								</div>
						</div>
					</div>
				</div>
				<!-- /vertical form modal -->

						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="left-icon-tab2">
					@if(AksiInsert())
					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Kode Tahun Ajaran</label>
								<div class="col-lg-9">
									<input required type="text" name="ta_kode" id="ta_kode" class="form-control" placeholder="2021">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Nama Tahun Ajaran</label>
								<div class="col-lg-9">
									<input required type="text" name="ta_nama" id="ta_nama" class="form-control" placeholder="2020/2021">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Keterangan Tahun Ajaran</label>
								<div class="col-lg-9">
									<input required type="text" name="ta_ktr" id="ta_ktr" class="form-control" placeholder="Boleh Di Kosongkan">
								</div>
							</div>
							
							<button id="simpandata" data-id="{{ route('insert.tahun.ajaran') }}" class="btn btn-primary"><i class="icon-paperplane"></i> Simpan</button>
						</div>
					</div>
					@endif
				</div>

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
<script src="{{ asset('global_assets/js/selectku.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
{{-- js datables agar ada button copy excel --}}
<script src="{{ asset('global_assets/js/demo_pages/datatables_extension_buttons_html5.js') }}"></script>
{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">

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
	$(document).on('click','.btnmodal',function(e){
		var eta_id = $(this).data("idd");
		var eta_kode = $(this).data("takode");
		var eta_nama = $(this).data("tanama");
		var eta_ktr = $(this).data("taktr");
		var eta_status = $(this).data("tastatus");
		$("#eta_kode_id").val(eta_id);
		$("#eta_kode").val(eta_kode);
		$("#eta_nama").val(eta_nama);
		$("#eta_ktr").val(eta_ktr);
		$("#eta_status").val(eta_status).change();
	
	});

	$(document).on('click','#simpandata',function(e){
		var urlid = $(this).data("id");
		var ta_kode = $("#ta_kode").val();
		var ta_nama = $("#ta_nama").val();
		var ta_ktr = $("#ta_ktr").val();
		var token = $("meta[name='csrf-token']").attr("content");
		$.ajax({
			type:'POST',
			url:urlid,
			data: { _token: token,taKode:ta_kode,taNama:ta_nama,taKtr:ta_ktr},
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

	$(document).on('click','#esimpandata',function(e){
		
		var urlid = $(this).data("id");
		var ta_kode2 = $("#eta_kode_id").val();
		var ta_kode = $("#eta_kode").val();
		var ta_nama = $("#eta_nama").val();
		var ta_ktr = $("#eta_ktr").val();
		var ta_status = $("#eta_status").val();
		var token = $("meta[name='csrf-token']").attr("content");
		$.ajax({
			type:'POST',
			url:urlid,
			data: { _token: token,taKode:ta_kode,taNama:ta_nama,taKtr:ta_ktr,taStatus:ta_status,taKode2:ta_kode2},
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

</script>
@endpush

