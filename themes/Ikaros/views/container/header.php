<?php echo @$before; ?>

<!-- start: header -->
<?php if ( isset($content) ) : ?>
<div class="header-wrapper">
    <div class="header">
        <?php echo @$content; ?>
    </div>
    <div class="header-light"></div>
</div>
<?php endif; ?>
<!-- end: header -->

<?php echo @$after; ?>