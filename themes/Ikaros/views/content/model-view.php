<?php if ( isset($data) ) : ?>

<div class="model-content">
    <?php foreach ( $data as $row ) : ?>
    <?php var_dump($row); ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>