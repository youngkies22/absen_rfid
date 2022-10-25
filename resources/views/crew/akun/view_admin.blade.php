@extends('master')
@section('content')
<!-- Content area -->

<div class="content">
	<!-- 2 columns form -->
	<div class="card">
		<div class="card-header bg-dark header-elements-inline">
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
			<div class="row mb-2">
				<div class="col-md-6">
					<a href="{{ route('add.akun.admin') }}" class="btn btn-primary legitRipple"><i class="icon-user-plus"></i> Tambah Admin</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive-sm">
					<table id="tabel" class="table table-striped table-bordered  " width="100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>AKSI</th>
								<th>USER NAME</th>
								<th>NAMA ADMIN</th>
								<th>HAK AKSES</th>
								
							</tr>
						</thead>
						<tbody>
						
							@foreach ($getData as $data)
								@if($data->admKode != "SUPERADMIN")
								<tr>
									<td>{{ $no++ }}</td>
									<td>
										@if(HakAksesFilterMenuSuper() == true)
										<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
											<li class="list-inline-item dropdown">
												<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
							
												<div class="dropdown-menu dropdown-menu-right">
													<a href="edit-akun-admin/{{ encrypt_url($data->admId) }}" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>
													<a id="delete"  data-id="{{ encrypt_url($data->admId) }}" title="Hapus Data" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-trash"> Hapus</i></a>
													{{-- <a title="Reset Password" id="resetpass" class="dropdown-item btn btn-sm btn-outline bg-warning text-warning border-warning legitRipple" data-id="{{ encrypt_url($data->admId) }}"><i class="icon-reset"> Reset Password </i></a> --}}
												</div>
											</li>
										</ul>
										@endif
									</td>
									<td>{{ $data->admUsername }}</td>
									<td>{{ $data->admFullName }}</td>
									<td>{{ $data->admKode }}</td>
								</tr>
								@endif
							@endforeach
						</tbody>
					</table>
					</div>
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

	var tabel = $('#tabel').DataTable({
		
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},

		
});

  
  $(document).on('click','#delete',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");

		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Akan Menghapus Data Ini ?</h3><center> ',
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
					type:'PUT',
					url:'{{ route("delete.admin") }}',
					data: { _token: token,id: id },
					success:function(respon){
						console.log(respon);
						new Noty({
								theme: ' alert alert-success alert-styled-left p-0 bg-white',
								text: respon.success,
								type: 'success',
								progressBar: false,
								closeWith: ['button']
							}).show();
							setInterval(function(){ location.reload(true); }, 1000);
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

