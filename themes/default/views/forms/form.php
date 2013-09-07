<div class="wysiwyg" data-cms="cms.model.form">{{&cms.model.form}}</div>

<form
    class="model-form <?php echo isset($class) ? $class : ''; ?>"
    action="<?php echo isset($action) ? $action : ''; ?>"
    method="<?php echo isset($method) ? $method : 'GET'; ?>"
    enctype="multipart/form-data"
>
    <?php foreach ( $fieldsets as $fieldset_name => $fieldset_data ) : ?>
      <fieldset id="<?= $fieldset_name; ?>">
        <?php if ( isset($fieldset_data['legend']) ) : ?><legend><?= $fieldset_data['legend']; ?></legend><?php endif; ?>
        <div class="fields"><?= $fieldset_data['fields']; ?></div>
      </fieldset>
    <?php endforeach; ?>

    <hr />
    <input type="submit" class="btn" />
</form>