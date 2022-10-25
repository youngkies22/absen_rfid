@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['thn'])){ $getthn =$_GET['thn']; }else{ $getthn =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; }else{ $getbln =''; }
if(!empty($_GET['hari'])){ $hari =$_GET['hari']; }else{ $hari =''; }
if(!empty($_GET['ttd'])){ $ttd =$_GET['ttd']; }else{ $ttd =''; }
if(!empty($_GET['thn'])){ 
	$url = 'crew/json-view-rekap-absen-finger?thn='.$getthn.'&bln='.$getbln.'&hari='.$hari; 
} else{ $url='crew/json-view-rekap-absen-finger';}

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
								<option value="0" >SEMUA</option>
								@for($i=1; $i <=31; $i++)
								<option {{ selectAktif($hari, $i)  }} value="{{ $i }}">{{ $i }}</option>
								@endfor
							</select>
						</div>
						<div class="col-lg-2">
							<select data-placeholder="TTD KEPSEK"  name="ttd" id="ttd"  class="form-control select" data-fouc>
								<option></option>
								<option value="0" >TIDAK</option>
								<option value="1">TAMPIL</option>
							</select>
						</div>
						<div class="col-lg-2">
							<button id="cariabsen" class="btn btn-info">Cari Absensi</button>
						</div>
					</div>
					<label class="text-muted">Pilih tanggal / hari SEMUA jika ingin menampilkan semua </label>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
				<iframe id='loadframe' name='frameresult' src="{{ url('crew/cetak-view-rekap-absen-finger?thn='.$getthn.'&bln='.$getbln.'&hari='.$hari.'&ttd='.$ttd) }}" style='border:none;width:1px;height:1px;'></iframe>
			{{-- bagian data --}}
			@if(!empty($_GET['thn']))
			<div class="row">
				<div class="col-md-6">
					<button onclick="frames['frameresult'].print()" target="_blank"  class="btn btn-sm btn-info">
					<i class="icon-printer"></i> Print Absen Bulan</button><br>
					<label class="text-muted">Pastikan Cetak melalui PC atau Laptop</label>
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
		var ttd = $('#ttd').val();
  	location="?thn="+thn+"&bln="+bln+"&hari="+hari+"&ttd="+ttd;
	});

</script>
@endpush

