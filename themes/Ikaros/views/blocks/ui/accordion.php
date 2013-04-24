<hr />

<div class="<?php if ( isset($class) ) echo $class; ?>">

  <h3 class="editable" data-cms="cms.accordion.header">{{cms.accordion.header}}</h3>

  <?php for ( $i=0; $i<$count; $i++ ) : ?>
  <div class="toggle">
    <h4 class="title editable" data-cms="cms.accordion<?php echo $i; ?>.title">{{cms.accordion<?php echo $i; ?>.title}}</h4>
    <div class="callout togglebox wysiwyg" data-cms="cms.accordion<?php echo $i; ?>.body">{{&cms.accordion<?php echo $i; ?>.body}}</div>
  </div>
  <?php endfor; ?>

</div>