<?php echo @$before; ?>

<!-- start: footer -->
<?php if ( isset($content) ) : ?>
<div class="footer-wrapper">
    <div class="footer-light"></div>
    <div class="footer">
        <?php echo @$content; ?>
    </div>
</div>
<?php endif; ?>
<!-- end: footer -->

<?php echo @$after; ?>