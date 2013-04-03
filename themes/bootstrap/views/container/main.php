<?php if (isset($before)) echo $before; ?>

<!-- start: .content -->
<?php if ( isset($content) ) : ?>
<div class="main">
<div class="container">
	<?php echo @$content; ?>	
</div>
</div>
<?php endif; ?>
<!-- end: .content -->

<?php if (isset($after)) echo $after; ?>