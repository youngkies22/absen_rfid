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
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive-sm">
					<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled" width="100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>ID KARTU</th>
								<th>KODE MESIN</th>
								<th>NAMA MESIN</th>
								<th>EDIT</th>
							</tr>
						</thead>
						<tbody>
							@if(!is_null($data))
								@foreach ($data as $val)
								<tr>
									<td>{{ $val->knfKartuId }}</td>
									<td>{{ $val->knfKodeMesin }}</td>
									<td>{{ $val->knfNamaMesin }}</td>
									<td>
											<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" href="edit-kartu-not-found/{{ $val->knfId }}"><i class="icon-pencil7"> Edit</i></a>
														<a data-url="{{ route("delete.kartu.not.found") }}" data-id="{{ $val->knfId }}" title="Hapus Data" class="delete dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id=""><i class="icon-trash"> Hapus </i></a> 
													</div>
												</li>
											</ul>
									</td>
									
								</tr>
								@endforeach
							@endif
						
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<hr>
			
			{{-- koneksi --}}
			
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

@endpush
@push('js_atas2')
{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
@endpush
@push('jsku')
<script type="text/javascript">

	var tabel = $('#tabel').DataTable({
	
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
});
$(document).on('click','.delete',function(e){
		var id = $(this).data("id");
		var url = $(this).data("url");
		var token = $("meta[name='csrf-token']").attr("content");
		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Akan Menghapus Data Ini ? Data yang di Hapus Tidak Akan Bisa di Kembalikan Lagi</h3><center> ',
			timeout: false,
			modal: true,
			layout: 'center',
			closeWith: 'button',
			type: 'confirm',
			buttons: [
			Noty.button('Cancel', 'btn btn-link', function () {
				notyConfirm.close();
			}),

			Noty.button('Hapus <i class="icon-trash ml-2"></i>', 'btn bg-danger ml-1', function () {
				$.ajax({
					type:'POST',
					url:url,
					data: { _token: token,id: id },
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
				notyConfirm.close();
			},
			{id: 'button1', 'data-status': 'ok'}
			)
			]
		}).show();
	});

</script>
@endpush

