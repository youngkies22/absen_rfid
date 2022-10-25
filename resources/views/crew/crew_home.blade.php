@extends('master')
@section('title', 'ADMIN BUDUT')
@section('content')
<!-- Content area -->
<div class="content">

	<div class="row">

		{{-- <div class="col-xl-12">
			<div class="row">
				<div class="col-lg-12">
					<div class="card" style="padding-left:10px; padding-right:10px; padding-top:10px;">
					<h5 class="card-title" style="padding-left:10px;">LIVE DATA ABSEN</h5>
					<div class="table-responsive" >
						<table id="tabel" class="table table-striped table-bordered  " width="90%">
							<thead style="background-color: #05405a; color: white;">
								<tr>
									<th>NAMA</th>
									<th>HARI</th>
									<th>JAM MASUK</th>
									<th>STATUS</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
				</div>
			</div>
		</div> --}}
		
		<!-- page 1-->
		<div class="col-xl-8">
			
			<div class="row">
				{{-- menu 1 --}}
				<div class="col-lg-4">
					
					<div class="card bg-teal-400">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">
									<table>
										
										<tr>
											<td>{{ getDataUser() }}</td>
											<td>:</td>
											<td align="center"></td>
											<td> Guru</td>
										</tr>
										
									</table>
								</h3>
								<span style="font-size: 15px;" class="badge bg-teal-800 badge-pill align-self-center ml-auto"></span>
							</div>

							<div>
								Jumlah Guru
								<div class="font-size-sm opacity-75"></div>
							</div>
						</div>

						
							<div id="server-load1"></div>
						
					</div>
					<!-- /members online -->
				</div>

				{{-- menu 2 --}}
				<div class="col-lg-4">
					<div class="card bg-warning-400">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">
									<table>
										
											<tr>
												<td>{{ getDataKartuNotFound() }}</td>
												<td>:</td>
												<td align="center"></td>
												<td> Kartu Kosong</td>
											</tr>
											
										<tr></tr>
									</table>
								</h3>
								<span style="font-size: 15px;" class="badge badge-primary badge-pill align-self-center ml-auto"></span>
							</div>

							<div>
								Jumlah Kartu Kosong
								<div class="font-size-sm opacity-75"></div>
							</div>
						</div>

						<div id="server-load"></div>
					</div>
					<!-- /current server load -->
				</div>
				{{-- menu 3 --}}
				<div class="col-lg-4">
					<div class="card bg-blue-400">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">
									<table>
									
										<tr>
											<td>{{ getDataMesin() }}</td>
											<td>:</td>
											
											<td> Mesin RFID</td>
										</tr>
									
										<tr></tr>
									</table>
								</h3>
								<span style="font-size: 15px;" class="badge badge-success badge-pill align-self-center ml-auto"></span>
							</div>

							<div>
								Jumlah Mesin RFID
								<div class="font-size-sm opacity-75"></div>
							</div>
						</div>

						<div id="server-load2"></div>
					</div>
					<!-- /today's revenue -->
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<button id="btn-telegram" class="btn btn-primary ">Test Bot Telegram <i class="icon-paperplane ml-2"></i></button>
					<br><span class="text-muted">Klik untuk test boot telegram</span>
				</div>
				<div class="col-lg-4">
					<a href="{{ route('form.live') }}" target="_blank" class="btn btn-danger ">Show live monitor <i class="icon-paperplane ml-2"></i></a>
					<br><span class="text-muted">Klik untuk show live monitor</span>
				</div>
			</div>
		</div>
		<!-- /page 1-->
	
		
		<!-- page 2-->
		<div class="col-xl-4">

			<!-- Sekolah-->
			<div class="card">
				<div class="table-responsive">
					<table class="table text-nowrap">
						<thead>
							<tr>
								<th>Kode Sekolah</th>
								<th>NPSN Sekolah</th>
								<th>Nama Sekolah</th>
							</tr>
						</thead>
						<tbody>
								<tr>
								<td>
									<span ><span class="badge badge-mark border-blue mr-1"></span> {{ getDataSekolahById()->sklKode }}</span>
								</td>
								<td>
									<span >{{ getDataSekolahById()->sklNpsn }}</span>
								</td>
								<td>
									<span >{{ getDataSekolahById()->sklNama }}</span>
								</td>
								
							</tr>
							
							
						</tbody>
					</table>
				</div>
			</div>
			<!-- /Sekolah -->

			

			
		</div>
		<!-- /page 2-->

	
	
	</div>
	
</div>
<!-- /content area -->
@endsection
@push('js_atas')
<!-- pluginnya datatables-->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
@endpush
@push('js_atas2')
<script src="{{ asset('global_assets/js/demo_pages/dashboard.js')}}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>



@endpush
@push('jsku')
<script type="text/javascript">


	$(document).on('click','#btn-telegram',function(e){
		$.ajax({
					type:'GET',
					url:"{{ route('bot.telegram') }}",
					success:function(respon){
						console.log(respon);
						new Noty({
							theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: "Berhasil Kirim Notif Ke Grup Telegram",
						type: 'success',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
				});
	});

	var tabel = $('#tabel').DataTable({
		//dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		
		//processing: true,
		serverSide: true,
		responsive: true,
		autoWidth: false,
		ajax: '{{ route('json.absen.finger.live') }}',	
		columns: [
			{ "data": "hgNamaFull"},
			{ "data": "hgHari"},
			{ "data": "hgJamIn"},
			{ "data": "hgKodeAbsen"},
			
		],
	
		order: [2, 'desc'],	
		
});

setInterval( function () {
    tabel.ajax.reload();
}, 2000 );
</script>

@endpush

