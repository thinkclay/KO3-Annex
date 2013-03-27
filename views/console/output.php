<?php if (count($data) >= 1) : ?>
	
	<?php if ($styles) : ?>
	<style type="text/css">
	.message {
		background-color: #D9EDF7;
		border-color: #BCE8F1;
		border-radius: 4px;
		border: 1px solid #FBEED5;
		color: #3A87AD;
		font-size: 13px;
		line-height: 18px;
		margin-bottom: 18px;
		padding: 8px 35px 8px 14px;
		text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	}
	.error {
		background-color: #F2DEDE;
		border-color: #EED3D7;
		color: #B94A48;
	}
	.success {
		background-color: #DFF0D8;
		border-color: #D6E9C6;
		color: #468847;
	}
		.message big { font-size: 15px; }
		.message span { display: block; padding: 5px; }
	</style>
	<?php endif; ?>
	
	<div class="message <?php echo ($error) ? 'error' : 'success'; ?>">
		<?php foreach ($data as $message) : ?>
		<span><?php echo $message; ?></span>
		<?php endforeach; ?>
	</div>
	
	<?php if ($code) : ?>
	<pre><?php var_dump($code); ?></pre>
	<?php endif; ?>

<?php endif; ?>