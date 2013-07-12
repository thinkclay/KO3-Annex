<?php if ( isset($data) ) : ?>
<div class="wysiwyg" data-cms="cms.model-list.before">{{&cms.model-list.before}}</div>
<ul>
    <?php foreach ( $data as $d ) : ?>
    <li>
        <h4><?= $d; ?></h4>
        <a href="/admin/content/create/<?php echo $d; ?>">create</a> |
        <a href="/admin/content/list/<?php echo $d; ?>">view all</a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>