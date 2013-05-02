  <!-- Begin Services -->

  <h1 class="center editable" data-cms="cms.tabs.header">{{cms.tabs.header}}</h1>
  <div class="intro wysiwyg" data-cms="cms.tabs.intro">{{&cms.tabs.intro}}</div>

  <div class="jq-tabs">
    <ul>
    <?php for ( $i=0; $i<count($tabs); $i++ ) : ?>
      <li><a href="#tab<?php echo $i; ?>" class="editable" data-cms="cms.tabgroub.tab<?php echo $i; ?>.name">{{cms.tabgroub.tab<?php echo $i; ?>.name}}</a></li>
    <?php endfor; ?>
    </ul>
    <?php for ( $i=0; $i<count($tabs); $i++ ) : ?>
    <div id="tab<?php echo $i; ?>">
      <div class="wysiwyg" data-cms="cms.tabgroub.tab<?php echo $i; ?>.content">{{&cms.tabgroub.tab<?php echo $i; ?>.content}}</div>
      <?php echo $tabs[$i]; ?>
    </div>
    <?php endfor; ?>
</div>