<form
    action="<?php echo isset($action) ? $action : ''; ?>"
    method="<?php echo isset($method) ? $method : 'GET'; ?>"
    autocomplete="off">

    <?php $elements = $user->as_form(); ?>

    <?php foreach( $elements as $element ) : ?>
        <?php echo $element; ?>
    <?php endforeach; ?>

    <label for="password">Update Password?</label>
    <input type="password" name="password" autocomplete="off" />

    <label for="password_confirm">Password again</label>
    <input type="password" name="password_confirm" />

    <hr />
  <input type="submit" class="btn" />
</form>