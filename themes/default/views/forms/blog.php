<div class="blog">
    <form method="POST" action="/blog/create">

    	<h3><label for="blog-title">Blog Title</label></h3>
    	<input type="text" name="blog-title" id="blog-title"/>

    	<h3><label for="blog-summary">Blog Summary</label></h3>
    	<input type="text" name="blog-summary" id="blog-summary"/>

    	<h3><label for="blog-category">Category</label></h3>
    	<input type="text" name="blog-category" id="blog-category"/>

    	<h3><label for="blog-tag">Blog Tags (comma seperated)</label></h3>
    	<input type="text" name="blog-tag" id="blog-tag"/>

        <h3><label for="blog-post">Blog Post</label></h3>
        <textarea name="blog-post" id="blog-post" class="redactor"></textarea>
        
        <br />
        <a href="#" class="button navy">Publish</a>
    </form>
</div>