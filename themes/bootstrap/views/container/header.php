<?php echo @$before; ?>

<!-- start: .header -->
<?php if ( isset($content) ) : ?>
<div class="header">
<div class="container">
	<?php echo @$content; ?>	
</div>
</div>
<?php endif; ?>
<!-- end: .header -->

<?php echo @$after; ?>