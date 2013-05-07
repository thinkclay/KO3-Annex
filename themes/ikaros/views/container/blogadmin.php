  <h1 class="center">Blog Admin Page</h1>
  <div class="intro center">Edit and Remove Posts Accordingly.</div>


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
          <div class="meta">Posted by <a href="#"><?php echo $post['user_name']; ?></a> under <a href="#"><?php if(isset($post['category'])) { echo $post['category']; } ?></a></div>
        </div>
      </div>
      <a href="/blogadmin/update/<?php echo $post['_id']?>">Edit</a>
      <a href="#">Remove</a>

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

  