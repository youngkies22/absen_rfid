@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['tgl'])){ $tgl =$_GET['tgl']; }else{ $tgl =''; }
if(!empty($_GET['tgl2'])){ $tgl2 =$_GET['tgl2']; }else{ $tgl2 =''; }
if(!empty($_GET['ttd'])){ $ttd =$_GET['ttd']; }else{ $ttd =''; }
if(!empty($_GET['tgl'])){ 
	$url = 'crew/json-view-rekap-absen-rentang?tgl='.$tgl.'&tgl2='.$tgl2.'&ttd='.$ttd; 
} else{ $url='crew/json-view-rekap-absen-rentang';}

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
							<span class="input-group-prepend">
								<span class="input-group-text"><i class="icon-calendar22"></i>&nbsp; Tanggal Awal</span>
							</span>
							<input type="text" id="tgl" name="tgl" class="form-control daterange-single" >
						</div>
						<div class="col-lg-2">
							<span class="input-group-prepend">
								<span class="input-group-text"><i class="icon-calendar22"></i>&nbsp; Tanggal Akhir</span>
							</span>
							<input type="text" id="tgl2" name="tgl2" class="form-control daterange-single" >
						</div>
						<div class="col-lg-2">
							<span class="input-group-prepend">
								<span class="input-group-text"><i class="icon-pen6"></i>&nbsp; TTD</span>
							</span>
							<select data-placeholder="TTD KEPSEK"  name="ttd" id="ttd"  class="form-control select" data-fouc>
								<option></option>
								<option value="0" selected>TIDAK TAMPIL</option>
								<option value="1">TAMPIL</option>
							</select>
						</div>
						<div class="col-lg-2">
							<br>
							<button id="cariabsen" class="btn btn-info">Cari Absensi</button>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					{{-- bagian data --}}
					<iframe id='loadframe' name='frameresult' src="{{ url('crew/cetak-view-rekap-absen-rentang?tgl='.$tgl.'&tgl2='.$tgl2.'&ttd='.$ttd) }}" style='border:none;width:1px;height:1px;'></iframe>
					@if(!empty($_GET['tgl']))
					<div class="row">
						<div class="col-md-6">
							<button onclick="frames['frameresult'].print()" target="_blank"  class="btn btn-sm btn-info">
							<i class="icon-printer"></i> Print Absen</button><br>
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


<!-- pluginnya form select-->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>


{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

{{-- daterangepicker --}}
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>

@endpush


@push('jsku')
<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});
		
		// Single picker
		$('.daterange-single').daterangepicker({ 
			singleDatePicker: true,
			locale: {
				format: 'D-M-Y'
			}
		});
	});

	$("#cariabsen").click(function(){
		var tgl 	= $('#tgl').val();
		var tgl2 	= $('#tgl2').val();
		var ttd 	= $('#ttd').val();
		location	="?tgl="+tgl+"&tgl2="+tgl2+"&ttd="+ttd;
		//alert(tgl);
		// var thn = $('#thn').val();
		// var bln = $('#bln').val();
		// var hari = $('#hari').val();
		// var ttd = $('#ttd').val();
  	// location="?thn="+thn+"&bln="+bln+"&hari="+hari+"&ttd="+ttd;
	});

</script>
@endpush

