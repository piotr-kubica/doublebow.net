<?php

require_once '/home/d2bow/symfony-1.4.6/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
	$this->setWebDir($this->getRootDir().'/../../public_html');
  }
}
