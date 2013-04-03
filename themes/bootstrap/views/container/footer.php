<?php echo @$before; ?>

<!-- start: .footer -->
<?php if ( isset($content) ) : ?>
<div class="footer">
<div class="container">
	<?php echo @$content; ?>	
</div>
</div>
<?php endif; ?>
<!-- end: .footer -->

<?php echo @$after; ?>