<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="icon" type="image/png" href="favicon.ico"/>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
    <body class="e404">
        <div class="main_conainer">
            <div class="header">
                <?php echo link_to1('4<span>0</span>4', '/', array('class' => 'double_bow')); ?>
                <div class="double_bow_sub">page doesn't exist</div>
            </div>
            <?php echo $sf_content ?>
        </div>
    </body>
</html>
