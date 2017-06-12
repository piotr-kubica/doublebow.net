<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>
        <?php if (!include_slot('title')): ?>
            DoubleBow - Web Developer's Blog
        <?php endif; ?>
    </title>
    <link rel="alternate" type="application/rss+xml" href="http://doublebow.net/rss" title="Doublebow RSS feed" />
    <link rel="icon" type="image/png" href="favicon.ico"/>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-23404183-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    <?php if (!include_slot('dynamic_js')): ?>
    <?php endif; ?>
    <?php if (!include_slot('dynamic_css')): ?>
    <?php endif; ?>
  </head>
<body>
    <div id="top" class="preloader">
        <div class="facebook"></div>
        <div class="twitter"></div>
        <div class="rss"></div>
    </div>
    <div class="main_conainer">
    <div class="shadow_wrapper_left">
        <div class="shadow_wrapper_right">
            <div class="shadow_wrapper_top">
                <div class="shadow_wrapper_bottom">
                    <div class="main_wrapper">
                        <?php echo $sf_content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
