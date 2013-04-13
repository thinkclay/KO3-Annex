<?php if (isset($before)) echo $before; ?>

<!-- start: content -->
<?php if ( isset($content) ) : ?>
<div class="wrapper">
	<?php echo @$content; ?>
</div>
<?php endif; ?>
<!-- end: content -->

<?php if (isset($after)) echo $after; ?>