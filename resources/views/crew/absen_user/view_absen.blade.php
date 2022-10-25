@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['thn'])){ $getthn =$_GET['thn']; }else{ $getthn =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; }else{ $getbln =''; }
if(!empty($_GET['hari'])){ $hari =$_GET['hari']; }else{ $hari =''; }
if(!empty($_GET['thn'])){ 
	$url = 'crew/json-absen-finger?thn='.$getthn.'&bln='.$getbln.'&hari='.$hari; 
} else{ $url='crew/json-absen-finger';}

?>
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
			<div class="row ">
				<div class="col-md-12">
					<div class="form-group row">
						

						<div class="col-lg-2">
							<select required data-placeholder="Pilih Tahun"  name="thn" id="thn"  class="form-control select" data-fouc>
								<option></option>
								@foreach ($getBulanTahunAbsen as $thn)
								<option {{ selectAktif($getthn, $thn->tahun)}}  value="{{ $thn->tahun }}">{{$thn->tahun}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Bulan"  name="bln" id="bln"  class="form-control select" data-fouc>
								<option></option>
								@foreach (getBulan() as $bln)
								<option {{ selectAktif($getbln, $bln)  }} value="{{ $bln }}">{{ bulanIndo($bln)}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<select data-placeholder="Pilih Hari"  name="hari" id="hari"  class="form-control select" data-fouc>
								<option></option>
								<option value="0">SEMUA</option>
								@for($i=1; $i <=31; $i++)
								<option {{ selectAktif($hari, $i)  }} value="{{ $i }}">{{ $i }}</option>
								@endfor
							</select>
						</div>
						<div class="col-lg-2">
							<button id="cariabsen" class="btn btn-info">Cari Absensi</button>
						</div>
					</div>
					<label class="text-muted">Pilih tanggal / hari SEMUA jika ingin menampilkan semua </label>
				</div>
			</div>

			{{-- <div class="row">
				<div class="col-md-12">
				<iframe id='loadframe' name='frameresult' src="{{ url('crew/cetak-view-rekap-absen-finger?thn='.$getthn.'&bln='.$getbln.'&hari='.$hari) }}" style='border:none;width:1px;height:1px;'></iframe>
					
					<div class="row">
						<div class="col-md-6">
							<button onclick="frames['frameresult'].print()" target="_blank"  class="btn btn-sm btn-info">
							<i class="icon-printer"></i> Print Absen Bulan</button><br>
							<label class="text-muted">Pastikan Cetak melalui PC atau Laptop</label>
						
						</div>
					</div>
					@endif
				</div>
			</div> --}}
			
			<div class="row">
				<div class="col-md-12">
					<table id="tabel2" class="table table-striped table-bordered" width="100" style="width: 100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Nama</th>
								<th>Tanggal</th>
								<th>Hari</th>
								<th>Jam In</th>
								<th>Jam Out</th>
								<th>Status Absen</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
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
									<label>Jam Masuk</label>
									<input type="hidden" id="eta_id"  class="form-control">
									<input required type="text" id="eta_jamin"  class="form-control">
								</div>

								<div class="col-sm-6">
									<label>Jam Pulang</label>
									<input required type="text" id="eta_jamout" class="form-control">
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">

								<div class="col-sm-6">
									<label>Status</label>
									<select data-placeholder="Pilih Status"  name="eta_status" id="eta_status"  class="form-control select-fixed-single" data-fouc>
									<option value="H">HADIR</option>
									<option value="A">ALPHA</option>
									<option value="I">IZIN</option>
									<option value="S">SAKIT</option>
									<option value="T">TERLAMBAT</option>
									</select>
								</div>
							</div>
						</div>

						
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button data-id="{{ route('update.absen.finger') }}" id="esimpandata" class="btn bg-primary">Update Data</button>
					</div>
			</div>
		</div>
	</div>
	<!-- /vertical form modal -->

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

{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
	$("#cariabsen").click(function(){
		
		var thn = $('#thn').val();
		var bln = $('#bln').val();
		var hari = $('#hari').val();
  	location="?thn="+thn+"&bln="+bln+"&hari="+hari;
	});
	var tabel = $('#tabel2').DataTable({
		processing: true,
		//serverSide: true,
		ajax: '{{ url($url) }}',
		type: "GET",
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			emptyTable: "Data Tidak Ada , Silahkan Pilih Rombel Terlebih Dahulu",
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
			columns: [
				{ "data": "no" },
				{ "data": "hgUserGuru" },
				{ "data": "hgNamaFull" },
				{ "data": "hgTgl" },
				{ "data": "hari" },
				{ "data": "hgJamIn" },
				{ "data": "hgJamOut" },
				{ "data": "hgKodeAbsen" },
				{ "data": "aksi" },
			],
		
	scrollX: true,
			scrollY: '700px',
			scrollCollapse: true,
			fixedColumns: {
				leftColumns: 2,
			},
	order: [4, 'desc'],
});

  $(document).on('click','#delete',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");
		var cek = confirm('Apakah Anda Yakin Akan Menghapus Data Ini ?');
		if(cek==true){
			$.ajax({
				type:'PUT',
				url:id+'/delete-absen-finger',
				data: { _token: token,id: id },
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
		}
  });
	$(document).on('click','.btnmodal',function(e){
		var eta_id = $(this).data("id");
		var eta_jamin = $(this).data("jamin");
		var eta_jamout = $(this).data("jamout");
		var eta_status = $(this).data("status");

		$("#eta_id").val(eta_id);
		$("#eta_jamin").val(eta_jamin);
		$("#eta_jamout").val(eta_jamout);
		$("#eta_status").val(eta_status).change();
	});

	$(document).on('click','#esimpandata',function(e){
		
		var urlid = $(this).data("id");
		var id = $("#eta_id").val();
		var jamin = $("#eta_jamin").val();
		var jamout = $("#eta_jamout").val();
		var status = $("#eta_status").val();
		var token = $("meta[name='csrf-token']").attr("content");
		
		$.ajax({
			type:'POST',
			url:urlid,
			data: { _token: token,id:id,jamin:jamin,jamout:jamout,status:status},
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

