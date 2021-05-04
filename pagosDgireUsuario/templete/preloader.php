<?php
?>

<link rel="stylesheet" type="text/css" href="../style/preloader.css"/>

<div class="preloader bg--white">
	<i class="fa fa-spinner fa-spin fa-5x text--steelblue"></i>
</div>

<script type="text/javascript">
	jQuery(window).load(function(){
		jQuery('.preloader').fadeOut('slow');
		jQuery('.document__body').delay(700).fadeIn('slow');
	});
</script>
