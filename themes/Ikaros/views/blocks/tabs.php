  <!-- Begin Services -->

  <h1 class="center editable" data-cms="cms.tabs.header">{{cms.tabs.header}}</h1>
  <div class="intro center editable" data-cms="cms.tabs.intro">{{cms.tabs.intro}}</div>

  <!-- Begin Column -->
  <div class="three-fourth">
    <h3 class="editable" data-cms="cms.tabs.subheader">{{cms.tabs.subheader}}</h3>
    <div id="services-container" class="tab-container">
      <ul class="etabs left">
        <?php for ( $i=0; $i<count($tabs); $i++ ) : ?>
        <li class="tab">
          <a href="#tab<?php echo $i; ?>" class="editable" data-cms="cms.tabgroub.tab<?php echo $i; ?>.name">
              {{cms.tabgroub.tab<?php echo $i; ?>.name}}
          </a>
        </li>
        <?php endfor; ?>
      </ul>
      <div class="panel-container">
        <?php for ( $i=0; $i<count($tabs); $i++ ) : ?>
        <div id="tab<?php echo $i; ?>">
          <h3 class="editable" data-cms="cms.tabgroub.tab<?php echo $i; ?>.header">{{cms.tabgroub.tab<?php echo $i; ?>.header}}</h3>
          <p class=" editable" data-cms="cms.tabgroub.tab<?php echo $i; ?>.content">{{cms.tabgroub.tab<?php echo $i; ?>.content}}</p>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>
  <!-- End Column -->
  <!-- Begin Column -->
  <div class="one-fourth last">
    <h3>Toggle</h3>
    <!-- Begin Toggle -->
    <div class="toggle">
      <h4 class="title">Click to title</h4>
      <div class="togglebox">
        <div>
          <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla facilisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc rhoncus tortor quis eros bibendum a tempus est dapibus. Vivamus consectetur quam eu tellus porttitor ultrices. Nunc metus massa, ullamcorper sit amet malesuada a, porttitor in tellus.</p>
        </div>
      </div>
    </div>
    <!-- End Toggle -->

    <!-- Begin Toggle -->
    <div class="toggle">
      <h4 class="title">Click to title</h4>
      <div class="togglebox">
        <div>
          <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla facilisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc rhoncus tortor quis eros bibendum a tempus est dapibus. Vivamus consectetur quam eu tellus porttitor ultrices. Nunc metus massa, ullamcorper sit amet malesuada a, porttitor in tellus.</p>
        </div>
      </div>
    </div>
    <!-- End Toggle -->

    <!-- Begin Toggle -->
    <div class="toggle">
      <h4 class="title">Click to title</h4>
      <div class="togglebox">
        <div>
          <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla facilisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc rhoncus tortor quis eros bibendum a tempus est dapibus. Vivamus consectetur quam eu tellus porttitor ultrices. Nunc metus massa, ullamcorper sit amet malesuada a, porttitor in tellus.</p>
        </div>
      </div>
    </div>
    <!-- End Toggle -->

  </div>
  <!-- End Column -->
  <div class="clear"></div>