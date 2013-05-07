<div class="blog">
    <form method="POST" action="/blogadmin/create">

        <?php 
            if(isset($post))
            {
                if ($post['tags']) { $tags = implode(',',$post['tags']); } 
            }
        ?>

    	<h3><label for="blog-title">Blog Title</label></h3>
    	<input type="text" name="blog-title" id="blog-title" value="<?php if (@$post['title']) { echo $post['title']; } else { echo '';} ?>"/>

    	<h3><label for="blog-summary">Blog Summary</label></h3>
    	<input type="text" name="blog-summary" id="blog-summary" value="<?php if (@$post['short_description']) { echo $post['short_description']; } else { echo '';} ?>"/>

    	<h3><label for="blog-category">Category</label></h3>
    	<input type="text" name="blog-category" id="blog-category" value="<?php if (@$post['category']) { echo $post['category']; } else { echo '';} ?>"/>

    	<h3><label for="blog-tag">Blog Tags (comma seperated)</label></h3>
    	<input type="text" name="blog-tag" id="blog-tag" value="<?php if (@$tags) { echo $tags; } else { echo '';} ?>"/>

        <h3><label for="blog-post">Blog Post</label></h3>
        <textarea name="blog-post" id="blog-post" class="redactor"><?php if (@$post['long_description']) { echo $post['long_description']; } else { echo '';} ?></textarea>

        <input type="hidden" name="_id" id="_id" value="<?php if (@$post['_id']) { echo $post['_id']; } else { echo '';} ?>"/>

        
        <br />
        <a href="#" class="button navy">Publish</a>
    </form>
</div>