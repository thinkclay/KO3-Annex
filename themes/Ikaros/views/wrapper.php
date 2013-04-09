<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <title><?php echo (isset($title)) ? $title : 'Annex'; ?></title>

        <!-- META -->
        <meta charset="UTF-8">
        <?php if (isset($fbproperty)) : ?>
        <meta property="fb:app_id" content="<?= $fbproperty['app_id']; ?>" />
        <meta property="og:type" content="<?= $fbproperty['type']; ?>" />
        <meta property="og:url" content="http://<?= $fbproperty['url']; ?>" />
        <meta property="og:title" content="<?= $fbproperty['address']; ?>" />
        <meta property="og:description" content="<?= $fbproperty['description']; ?>" />
        <meta property="og:image" content="<?= $fbproperty['img_link']; ?>" />
        }
        <?php endif; ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if(isset($description)): ?><meta name="description" content="<?php echo $description?>" /><?php endif; ?>
        <?php if(isset($keywords)): ?><meta name="keywords" content="<?php echo $keywords?>" /><?php endif; ?>

        <!-- STYLES -->
        <link rel="shortcut icon" type="image/x-icon" href="/images/ikaros/favicon.png" />
        <?php if (isset($styles)) foreach($styles as $file => $type){ echo HTML::style($file, ['media' => $type])."\r\n\t"; } ?>
        <?php echo isset($less) ? Less::compile($less) : ""; ?>

        <!--[if IE 8]><link rel="stylesheet" type="text/css" href="/styles/ikaros/ie8.css" media="all" /><![endif]-->
        <!--[if IE 9]><link rel="stylesheet" type="text/css" href="/styles/ikaros/ie9.css" media="all" /><![endif]-->

        <!-- GLOBAL JS VARS -->
        <?php if (isset($js_vars)) : ?>
        <script type="text/javascript">
        <?php foreach ($js_vars as $key => $value) echo "var $key = '$value'; \r\n\t"; ?>
        </script>
        <?php endif; ?>

        <!-- SCRIPTS -->
        <?php if (isset($scripts)) foreach ($scripts as $file) { echo HTML::script($file, NULL, FALSE), "\r\n\t"; } ?>

        <?php if (isset($head)) echo $head; ?>

    </head>

    <body id="<?php echo @$id; ?>" class="<?php if (isset($classes)) foreach ($classes as $c) echo $c.' '; ?><?php echo @$class; ?>">

        <?php if (isset($before)) echo $before; ?>

        <?php if (isset($header)) echo $header; ?>

        <?php if (isset($main)) echo $main; ?>

        <?php if (isset($footer)) echo $footer; ?>

        <?php if (isset($after)) echo $after; ?>

    </body>
</html>