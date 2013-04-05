<form action="<?php echo isset($action) ? $action : ''; ?>" method="<?php echo isset($method) ? $method : 'GET'; ?>">
  <?php foreach( $elements as $element ) : ?>
    <?php echo $element; ?>
  <?php endforeach; ?>

    <hr />
  <input type="submit" class="btn" />
</form>