<?php if ( isset($data) ) : ?>
<div class="wysiwyg" data-cms="cms.model-list.before">{{&cms.model-list.before}}</div>
<ul>
    <?php foreach ( $data as $d ) : ?>
    <li><a href="/admin/content/overview/<?php echo $d; ?>"><?= $d; ?></a></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>