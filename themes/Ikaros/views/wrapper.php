<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <title><?php echo (isset($title)) ? $title : 'Annex'; ?></title>

        <!-- META -->
        <?php if (isset($fbproperty)) : ?>
        <meta property="fb:app_id" content="<?= $fbproperty['app_id']; ?>" />
        <meta property="og:type" content="<?= $fbproperty['type']; // qwizzle_users:property ?>" />
        <meta property="og:url" content="http://<?= $fbproperty['url']; ?>" />
        <meta property="og:title" content="<?= $fbproperty['address']; ?>" />
        <meta property="og:description" content="<?= $fbproperty['description']; ?>" />
        <meta property="og:image" content="<?= $fbproperty['img_link']; ?>" />
        }
        <?php endif; ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if(isset($description)): ?><meta name="description" content="<?php echo $description?>" /><?php endif; ?>
        <?php if(isset($keywords)): ?><meta name="keywords" content="<?php echo $keywords?>" /><?php endif; ?>

        <!-- STYLES -->
        <?php if(isset($styles)) foreach($styles as $file => $type){ echo HTML::style($file, ['media' => $type]); } ?>
        <?php if(isset($less)) echo Less::compile($less); ?>

        <!-- JS_VARS -->
        <?php if (isset($js_vars)) : ?>
        <script type="text/javascript">
        <?php foreach ($js_vars as $key => $value) echo "var $key = '$value'; \r\n"; ?>
        </script>
        <?php endif; ?>

        <!-- SCRIPTS -->
        <?php if (isset($scripts)) foreach ($scripts as $file) { echo HTML::script($file, NULL, FALSE), "\n"; } ?>

        <?php if (isset($head)) echo $head; ?>
    </head>

    <body
        id="<?php if (isset($id)) echo $id; ?>"
        class="<?php if (isset($classes)) foreach ($classes as $c) echo $c.' '; ?><?php if (isset($class)) echo $class; ?>"
    >
        <?php if (isset($before)) echo $before; ?>
        <?php if(isset($header)) echo $header; ?>
        <?php if(isset($main)) echo $main; ?>
        <?php if(isset($footer)) echo $footer; ?>
        <?php if(isset($after)) echo $after; ?>
    </body>
</html>