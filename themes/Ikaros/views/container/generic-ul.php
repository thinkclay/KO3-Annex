<?php if ( isset($data) ) : ?>
<ul>
    <?php foreach ( $data as $d ) : ?>
    <li><?php echo $d; ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>