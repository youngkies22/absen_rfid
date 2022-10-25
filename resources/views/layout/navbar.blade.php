
<div class="navbar navbar-expand-md navbar-dark fixed-top color_mryes">
		<div class="navbar-brand">
			<a href="{{ url('crew/home')}}" class="d-inline-block">
				<img src="../../../../global_assets/images/logo_light.png" alt="">
			</a>
		</div>
		
		<div class="d-md-none">
			<a title="Bersihkan Cache" class="navbar-toggler clearcacheredish" href="{{ route('logout') }}" >
			&nbsp;<i class="icon-trash"></i>
			</a>
			<a title="Logout" class="navbar-toggler" href="{{ route('logout') }}" >
				Logout &nbsp;<i class="icon-switch2"></i>
			</a>
			<button title="Menu" class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				Menu &nbsp; <i class="icon-paragraph-justify3"></i>{{-- menu pada mode mobile --}}
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			
			</ul>
			<span class="navbar-text ml-md-3">
				<span class="badge badge-mark border-orange-300 mr-2"></span>
				{{ env('AKADEMIK_WELCOME') }}
			</span>
			
			<span class="badge bg-success ml-md-3 mr-md-auto">Online</span>
			<ul class="navbar-nav ml-md-auto">
				
				
				<li class="nav-item">
					<a href="{{ route('logout') }}" class="navbar-nav-link">
						<?php echo convert(memory_get_usage());?> Logout &nbsp;
						<i class="icon-switch2"></i>
						
					</a>
				</li>
			</ul>
		</div>
	</div>
{{-- setting agar turun ke bawah --}}
<div style="padding-top: 35px"></div>
