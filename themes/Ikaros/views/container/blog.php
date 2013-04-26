  <h1 class="center">Current News</h1>
  <div class="intro center">Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Maecenas faucibus mollis interdum, consectetur adipiscing elit.</div>

  <!-- Begin Content -->
  <div class="post-container">
  <?php foreach($posts as $post): ?>
    <div class="post">
      <div class="post-info">
        <div class="date">
          <div class="day"><?php echo date('d', $post['created']); ?></div>
          <div class="month"><?php echo date('M', $post['created']); ?></div>
        </div>
        <div class="title-meta">
          <h1 class="post-title"><a href="/blog/single"><?php echo $post['title']; ?></a></h1>
          <div class="meta">Posted by <a href="#"><?php echo $post['user_name']; ?></a> under <a href="#"><?php if(isset($post['category'])) { echo $post['category']; } ?></a> | <a href="#">No Comments</a></div>
        </div>
      </div>
      <!-- <div class="featured"><a href="/blog/single"><img src="/images/ikaros/art/post1.jpg" alt="" /></a></div> -->
      <p><?php echo $post['long_description']; ?></p>
      <div class="tags"><span></span> Tags: 
        <?php foreach($post['tags'] as $tag) : ?>
          <a href="#"><?php echo $tag; ?></a>,
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
   
    <!-- Begin Page-navi -->
    <div id="navigation">
      <div class="nav-previous"><a href="#" ><span class="meta-nav-prev">&larr; Older posts</span></a></div>
      <!--
			<div class="nav-next"><a href="#" ><span class="meta-nav-next">Newer posts &rarr;</span></a></div>
			 -->
    </div>
    <!-- End Page-navi -->

  </div>
  <!-- End Content -->

  <div class="sidebar">
    <div class="sidebox">
      <h3>Popular Posts</h3>
      <ul class="posts-list">
        <li> <div class="featured"><a href="#"><img src="/images/ikaros/art/a1.jpg" alt="" /></a></div>
          <div class="meta">
            <h6><a href="#">Interior Design Workshop</a></h6>
            <em>02 Mar 2012</em>
          </div>
        </li>
        <li> <div class="featured"><a href="#"><img src="/images/ikaros/art/a2.jpg" alt="" /></a></div>
          <div class="meta">
            <h6><a href="#">Unbearable Waiting Hours in the Doctor's Office</a></h6>
            <em>23 Dec 2011</em>
          </div>
        </li>
        <li> <div class="featured"><a href="#"><img src="/images/ikaros/art/a3.jpg" alt="" /></a></div>
          <div class="meta">
            <h6><a href="#">How to Eat Healthy</a></h6>
            <em>15 Nov 2011</em>
          </div>
        </li>
      </ul>
    </div>
    <div class="sidebox">
      <h3>Search</h3>
      <form class="searchform" method="get">
        <input type="text" id="s" name="s" value="type and hit enter" onfocus="this.value=''" onblur="this.value='type and hit enter'"/>
      </form>
    </div>
    <div class="sidebox">
      <h3>Custom Text</h3>
      <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
      <p>Sed posuere consectetur est at lobortis. Sed posuere consectetur est at lobortis. Fusce  mauris condimentum.</p>
    </div>
    <div class="sidebox">
      <h3>Categories</h3>
      <ul class="list">
        <li><a href="#">Web Design (21)</a></li>
        <li><a href="#">Photography (19)</a></li>
        <li><a href="#">Graphic Design (16)</a></li>
        <li><a href="#">Manipulation (15)</a></li>
        <li><a href="#">Motion Graphics (12)</a></li>
      </ul>
    </div>
  </div>
  <div class="clear"></div>