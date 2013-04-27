<div class="two-column <?php if (isset($classes)) foreach ($classes as $class) echo $class; ?>">
	<section class="column-left">
		<?php echo @$left; ?>
	</section>
	
	<section class="column-right">
		<?php echo @$right; ?>
	</section>
</div>