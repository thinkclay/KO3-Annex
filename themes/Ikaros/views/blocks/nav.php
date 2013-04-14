<div class="logo">
  <a href="/">
    <img src="/images/ikaros/logo.png" alt="" />
  </a>
</div>

<?php
$uri = Request::$current->uri();
$active = 'class="active"';
?>

<!-- Begin Menu -->
<div id="menu" class="menu">
  <ul id="tiny">
    <li <?php echo ($uri == '/') ? $active : ''; ?>>
      <a href="/">Home</a>
    </li>
    <li <?php echo ($uri == 'company/about' OR $uri == 'company/contact') ? $active : ''; ?>>
      <a href="/company/about">About Us</a>
      <ul>
        <li <?php echo ($uri == 'company/contact') ? $active : ''; ?>><a href="/company/contact">Contact Us</a></li>
      </ul>
    </li>
    <li <?php echo (preg_match('/process/i', $uri)) ? $active : ''; ?>>
      <a href="/company/process">Our Process</a>
      <ul>
        <li <?php echo ($uri == 'company/faq') ? $active : ''; ?>><a href="/company/faq">FAQ</a></li>
      </ul>
    </li>
    <li <?php echo (preg_match('/blog/i', $uri)) ? $active : ''; ?>>
      <a href="/blog">Blog</a>
      <ul>
        <li <?php echo ($uri == 'blog/archives') ? $active : ''; ?>><a href="/blog/archives">Archives</a></li>
      </ul>
    </li>
  </ul>
</div>
<div class="clear"></div>
<!-- End Menu -->