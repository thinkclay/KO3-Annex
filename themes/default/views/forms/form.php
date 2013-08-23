<div class="wysiwyg" data-cms="cms.model.form">{{&cms.model.form}}</div>

<form
    class="model-form <?php echo isset($class) ? $class : ''; ?>"
    action="<?php echo isset($action) ? $action : ''; ?>"
    method="<?php echo isset($method) ? $method : 'GET'; ?>"
    enctype="multipart/form-data"
>
    <fieldset>
        <?php foreach( $elements as $element ) : ?>
            <?php echo $element; ?>
        <?php endforeach; ?>

        <hr />
        <input type="submit" class="btn" />
    </fieldset>
</form>