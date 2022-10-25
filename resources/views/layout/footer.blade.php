
<!-- Footer -->
<div class="navbar navbar-expand-lg navbar-light">
	<div class="text-center d-lg-none w-100">
		<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
			<i class="icon-unfold mr-2"></i>
			Footer
		</button>
	</div>

	<div class="navbar-collapse collapse" id="navbar-footer">
		<span class="navbar-text">
			<?php //{{getTahunSekarang()}} ?> 
			&copy; {{ env('AKADEMIK_COPYRIGHT') }} , {{env('AKADEMIK_NAMA_SEKOLAH')}} | {{ env('AKADEMIK_VERSI') }} Created by <a href="https://web.facebook.com/youngkq?_rdc=1&_rdr" target="_blank">{{env('AKADEMIK_CREATEDBY')}}</a>
		</span>
	</div>
</div>
<!-- /footer -->
