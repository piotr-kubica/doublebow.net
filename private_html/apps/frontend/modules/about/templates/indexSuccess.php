<?php slot('title') ?>
    DoubleBow - About
<?php end_slot(); ?>

<?php include_partial('articles/header', array('menu_link' => 'about')) ?>
<div class="container_10 content">
    <div class="grid_7">
        <div class="left_column about">
            <h2 class="page">About</h2>
            <div class="main_content">
                <div class="float_box">
                    <a class="top" href="#top">top</a>
                </div>
                <div>
                    <h2>Hi!</h2>
                </div>
                <div class="article_text">
                    <div class="pic"></div>
                    <h3>My name is Peter.</h3>
                    <p>
                        I'm a student and a webdeveloper <b>since 2011</b>.
                        I live in <b>Wroclaw</b>, Poland. I'm strongly interested in Java, web 
                        development, web design, Flash and OpenSource solutions. I love coding, 
                        creating and solving development problems.
                    </p>
                    <p>
                        <span class="copyright">Doublebow</span> is my personal blog. I will post usefull tips about
                        web developent and programming.
                    </p>
                    <p>
                        If you want to see part of my work, visit my <?php echo link_to('portfolio', '/portfolio'); ?>.
                    </p>
                </div>
            </div>
            <div class="main_content">
                <div>
                    <h2>Skills</h2>
                </div>
                <div class="article_text">
                    <div>
                        <div class="block left">
                            <h4>OOP languages</h4>
                            <p>
                                Java (Oracle Certified Java Programmer), C#, C++
                            </p>
                        </div>
                        <div class="block">
                            <h4>DBMS</h4>
                            <p>
                                MySQL, Oracle (PL/SQL), MS Access
                            </p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <div class="block left">
                            <h4>Web technologies</h4>
                            <p>
                                PHP, (X)HTML, JavaScript, CSS, Flash (AS 3.0)
                            </p>
                        </div>
                        <div class="block">
                            <h4>OS</h4>
                            <p>
                                Windows, Linux
                            </p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="main_content">
                <div>
                    <h2>Contact me</h2>
                </div>
                <div class="article_text">
                    <p>
                        double2bow@gmail.com
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php include_component('articles', 'sidebar') ?>
    <div class="clear"></div>
</div>
<?php include_component('articles', 'footer') ?>
