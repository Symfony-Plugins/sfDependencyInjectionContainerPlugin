<?php


if (!isset($_SERVER['SYMFONY']))
{
  if (is_dir($sf_lib_dir=dirname(__FILE__).'/../../../../lib/vendor/symfony/lib/'))
  {
    $_SERVER['SYMFONY'] = $sf_lib_dir;
  }
  else
  {
    throw new RuntimeException('Could not find symfony libraries. Please set SYMFONY environment variable.');
  }
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

$configuration = new sfProjectConfiguration(dirname(__FILE__).'/../fixtures/project');
require_once $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

function sfDependencyInjectionContainerPlugin_autoload_again($class)
{
  $autoload = sfSimpleAutoload::getInstance();
  $autoload->reload();
  return $autoload->autoload($class);
}
spl_autoload_register('sfDependencyInjectionContainerPlugin_autoload_again');

if (file_exists($config = dirname(__FILE__).'/../../config/sfDependencyInjectionContainerPluginConfiguration.class.php'))
{
  require_once $config;
  $plugin_configuration = new sfDependencyInjectionContainerPluginConfiguration($configuration, dirname(__FILE__).'/../..', 'sfDependencyInjectionContainerPlugin');
}
else
{
  $plugin_configuration = new sfPluginConfigurationGeneric($configuration, dirname(__FILE__).'/../..', 'sfDependencyInjectionContainerPlugin');
}
