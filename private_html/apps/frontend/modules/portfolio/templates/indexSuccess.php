<?php slot('title') ?>
    DoubleBow - Portfolio
<?php end_slot(); ?>

<?php include_partial('articles/header', array('menu_link' => 'portfolio')) ?>
<div class="container_10 content">
    <div class="grid_7">
        <div class="left_column portfolio">
            <h2 class="page">Part of my work</h2>

            <div class="main_content">
                <div class="float_box">
                    <a class="top" href="#top">top</a>
                </div>
                <div>
                    <h2><a href="http://kwiaciarnia-dekor.prv.pl">Kwiaciarnia Dekor</a></h2>
                </div>
                <div id="kwiaciarnia_dekor" class="pic">
                    <div class="tech">
                        <ul>
                            <li>design</li>
                            <li>xhtml, css</li>
                            <li>ajax gallery</li>
                            <li>google maps api</li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="main_content">
                <div>
                    <h2><a>Flash picture gallery</a></h2>
                </div>
                <div id="flash_gallery_1" class="pic">
                    <div class="tech">
                        <ul>
                            <li>Flash</li>
                            <li>Action Script 3.0</li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>


        </div>
    </div>
    <?php include_component('articles', 'sidebar') ?>
    <div class="clear"></div>
</div>
<?php include_component('articles', 'footer') ?>
