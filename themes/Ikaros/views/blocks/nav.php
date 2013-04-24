<?php
$uri = '/'.Request::$current->uri();
$uri2 = '/'.strtolower(Request::$current->controller()).'/'.strtolower(Request::$current->action());
$active = 'class="active"';

if ( isset($selected) )
{
    $navigation[$selected]['li_class'] = 'active';
}
?>

<?php if ( isset($navigation) ) : ?>
<nav id="main-navigation" class="clearfix">
  <div class="container clearfix">
    <div class="logo">
      <a href="/">
        <img src="/images/ikaros/logo.png" alt="" />
      </a>
    </div>

    <!-- Begin Menu -->
    <div id="menu" class="menu">
      <ul id="tiny">
        <?php foreach ( $navigation as $item ) : ?>
        <li class="<?php echo isset($item['li_class']) ? $item['li_class'] : ''; ?>">
          <a href="<?php echo $item['url']; ?>" >
            <?php echo $item['text']; ?>
          </a>
          <?php if ( isset($item['sub']) AND is_array($item['sub']) ) : ?>
          <ul>
            <?php foreach ( $item['sub'] as $sub ) : ?>
            <li><a href="<?php echo @$sub['url']; ?>"><?php echo @$sub['text']; ?></a></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <!-- End Menu -->
  </div>
</nav>
<?php endif; ?>

<?php if ( isset($selected) AND isset($navigation[$selected]) AND isset($navigation[$selected]['sub']) ) : ?>
<nav id="submenu" class="clearfix">
  <div class="container">
    <ul>
      <?php foreach ( $navigation[$selected]['sub'] as $item ) : ?>
      <li <?php if ( $uri == $item['url'] OR $uri2 == $item['url'] ) echo 'class="active"'; ?>>
        <a href="<?php echo $item['url']; ?>">
          <?php echo $item['text']; ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>
<?php endif; ?>