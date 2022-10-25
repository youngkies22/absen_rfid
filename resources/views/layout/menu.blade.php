<!--Sidebar mobile toggler -->
<div class="sidebar-mobile-toggler text-center">
	<a href="#" class="sidebar-mobile-main-toggle">
		<i class="icon-arrow-left8"></i>
	</a>
	<span class="font-weight-semibold">MENU</span>
	<a href="#" class="sidebar-mobile-expand">
		<i class="icon-screen-full"></i>
		<i class="icon-screen-normal"></i>
	</a>
</div>
<!-- /sidebar mobile toggler -->

<!-- Sidebar content -->
<div class="sidebar-content">
	
	<!-- User menu -->
	<div class="sidebar-user-material">
		<div class="sidebar-user-material-body">
			<div class="card-body text-center" id="fotoprofilemenu">
				<a href="{{ route('profile.admin') }}">
					<img width="80" height="80" src="<?php echo GetFotoMenu().'?date='.time(); ?>" class="img-fluid rounded-circle " alt="{{ FullNama() }}">
				</a>
				<h6 class="mb-0 text-white text-shadow-dark">{{ NamaDepan() }}</h6>
				<span class="font-size-sm text-white text-shadow-dark">
					
				</span>
			</div>

			<div class="sidebar-user-material-footer">
				<a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse"><span>Data Akun Saya</span></a>
			</div>
		</div>

		<div class="collapse" id="user-nav">
			<ul class="nav nav-sidebar">
				<li class="nav-item">
					<a href="{{ route('profile.admin') }}" class="nav-link">
						<i class="icon-user-plus"></i>
						<span>Profile</span>
					</a>
				</li>
				@if(HakAksesFilterMenu() == true)
				<li class="nav-item">
					<a href="{{ route('list.akun.admin') }}" class="nav-link">
						<i class="icon-user-plus"></i>
						<span>Data Akun Admin</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('logo.sekolah') }}" class="nav-link">
						<i class="icon-folder-upload"></i>
						<span>Logo Sekolah</span>
					</a>
				</li>
				@endif
				
				{{-- <li class="nav-item">
					<a href="#" class="nav-link">
						<i class="icon-cog5"></i>
						<span>Akun Setting</span>
					</a>
				</li> --}}
				
			</ul>
		</div>
	</div>
	<!-- /user menu -->


	<!-- Main navigation -->
	<div class="card card-sidebar-mobile">
		<ul class="nav nav-sidebar" data-nav-type="accordion">

			<!-- Main -->
			{{-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu</div> <i class="icon-menu" title="Main"></i> --}}</li>
			<li class="nav-item">
				<a href="{{ url('crew/home')}}" class="nav-link active">
					<i class="icon-home4"></i>
					<span>
						Dashboard
					</span>
				</a>
			</li>

			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu MASTER DATA</div> <i class="icon-menu" title="Main"></i></li>
<!--Master Sekolah------------------------------------------------------------------------->
			<li class="nav-item nav-item-submenu {{ set_active_menu(['lihat.sekolah','lihat.jabatan','lihat.tahun.ajaran','lihat.tahun.ajaran','lihat.semester','lihat.agama','lihat.tingkat.pendidikan','lihat.transportasi','lihat.penghasilan','lihat.smp']) }}">
				<a href="#" class="nav-link"><i class="icon-office"></i> <span>Master Sekolah</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" 
				style="{{ set_active_submenu_blok(['lihat.sekolah','lihat.jabatan','lihat.tahun.ajaran','lihat.tahun.ajaran','lihat.semester','lihat.agama','lihat.tingkat.pendidikan','lihat.transportasi','lihat.penghasilan','lihat.smp']) }}">
					<li class="nav-item"><a href="{{ route('lihat.sekolah') }}" class="nav-link {{set_active_submenu('lihat.sekolah')}}"><i class="icon-plus22"></i>Lihat Sekolah</a></li>
					<li class="nav-item"><a href="{{ route('lihat.tahun.ajaran') }}" class="nav-link {{set_active_submenu('lihat.tahun.ajaran')}}"><i class="icon-flag7"></i>Tahun Ajaran</a></li>
					<li class="nav-item"><a href="{{ route('lihat.semester') }}" class="nav-link {{set_active_submenu('lihat.semester')}}"><i class="icon-flag8"></i>Semester</a></li>
				</ul>
			</li>


<!--Master Guru------------------------------------------------------------------------->			
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.guru','lihat.guru','tambah.wali.kelas','lihat.wali.kelas','tambah.kajur','lihat.kajur'])}}">
				<a href="#" class="nav-link"><i class="icon-graduation2"></i> <span>Master Guru</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.guru','lihat.guru','tambah.wali.kelas','lihat.wali.kelas','tambah.kajur','lihat.kajur'])}}">
					{{-- guru --}}
					<li class="nav-item"><a href="{{ route('form.import.guru') }}" class="nav-link {{set_active_submenu('form.import.guru')}}"><i class="icon-upload"></i> Import Data Guru</a></li>
					<li class="nav-item"><a href="{{ route('add.guru') }}" class="nav-link {{set_active_submenu('add.guru')}}"><i class="icon-plus22"></i>Tambah Guru</a></li>
					<li class="nav-item"><a href="{{ route('lihat.guru') }}" class="nav-link {{set_active_submenu('lihat.guru')}}"><i class="icon-users2"></i>Lihat Guru</a></li>
					
					
				</ul>

			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu Absensi Siswa</div> <i class="icon-menu" title="Main"></i></li>
<!--Absen Finger------------------------------------------------------------------------->	
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.absen.finger','lihat.absen.finger'])}}">
				<a href="#" class="nav-link"><i style="font-size: 23px" class="mi-alarm"></i> <span>Absen Finger</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.absen.finger','lihat.absen.finger'])}}">
					<li class="nav-item"><a href="{{ route('add.absen.finger') }}" class="nav-link {{set_active_submenu('add.absen.finger')}}"><i class="icon-plus22"></i>Tambah Absen</a></li>
					<li class="nav-item"><a href="{{ route('lihat.absen.finger') }}" class="nav-link {{set_active_submenu('lihat.absen.finger')}}"><i class="icon-clipboard6"></i>Lihat Absen</a></li>
					
				</ul>
			</li>
<!--Rekap Absen Finger------------------------------------------------------------------------->	
			<li class="nav-item nav-item-submenu {{set_active_menu(['view.rekap.absen.finger','view.rekap.absen.rentang'])}}">
				<a href="#" class="nav-link"><i style="font-size: 23px" class="mi-fingerprint"></i> <span>Rekap Absen Finger</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['view.rekap.absen.finger','view.rekap.absen.rentang'])}}">
					<li class="nav-item"><a href="{{ route('view.rekap.absen.finger') }}" class="nav-link {{set_active_submenu('view.rekap.absen.finger')}}"><i class="icon-clipboard5"></i>Rekap Absen Bulan </a></li>
					<li class="nav-item"><a href="{{ route('view.rekap.absen.rentang') }}" class="nav-link {{set_active_submenu('view.rekap.absen.rentang')}}"><i class="icon-clipboard5"></i>Rekap Absen Rentang </a></li>

				</ul>
			</li>
			
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.mesin.rfid','view.mesin.rfid','view.kartu.not.found','view.user.rfid'])}}">
				<a href="#" class="nav-link"><i style="font-size: 23px" class="icon-feed"></i> <span>Arduino RFID</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.mesin.rfid','view.mesin.rfid','view.kartu.not.found','view.user.rfid'])}}">
					<li class="nav-item"><a href="{{ route('add.mesin.rfid') }}" class="nav-link {{set_active_submenu('add.mesin.rfid')}}"><i class="icon-plus22"></i>Tambah Mesin RFID</a></li>
					<li class="nav-item"><a href="{{ route('view.mesin.rfid') }}" class="nav-link {{set_active_submenu('view.mesin.rfid')}}"><i class="icon-folder"></i>Data Mesin RFID</a></li>
					<li class="nav-item"><a href="{{ route('view.user.rfid') }}" class="nav-link {{set_active_submenu('view.user.rfid')}}"><i class="icon-folder"></i>Data User RFID</a></li>
					<li class="nav-item"><a href="{{ route('view.kartu.not.found') }}" class="nav-link {{set_active_submenu('view.kartu.not.found')}}"><i class="icon-clipboard6"></i>Kartu Not Found</a></li>

				</ul>
			</li>

			
			
			<!-- /main -->
		</ul>
	</div>
	<!-- /main navigation -->
</div>
@push('js_atas2')
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
@endpush
@push('jsku')
{{-- <script type="text/javascript">	

</script> --}}
@endpush
<!-- /sidebar content-->
