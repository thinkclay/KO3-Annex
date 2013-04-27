<?php if ( isset($data) ) : ?>
<ul>
    <?php foreach ( $data as $d ) : ?>
    <li>
        <h4><?php echo $d; ?></h4>
        <a href="/admin/content/create/<?php echo $d; ?>">create</a> |
        <a href="/admin/content/list/<?php echo $d; ?>">view all</a>
        <br /><br />
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>