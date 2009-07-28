<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');

class sfPluginServiceMock {}

function listenToServiceLoadConfigurationEvent(sfEvent $event)
{
  $container = $event->getSubject();
  $container->register('test', 'sfPluginServiceMock');
}

$t = new lime_test(3, new lime_output_color());

$dispatcher = $configuration->getEventDispatcher();

$dispatcher->connect('service_container.load_configuration', 'listenToServiceLoadConfigurationEvent');

$config = new sfDependencyInjectionContainerPluginConfiguration($configuration);

try
{
  $configuration->getServiceContainer();
  $t->pass('initialize() connect an event to "configuration.method_not_found"');
}
catch (Exception $e)
{
  $t->fail('initialize() connect an event to "configuration.method_not_found"');
}

$t->isa_ok($config->getServiceContainer()->getService('test'), 'sfPluginServiceMock', 'initialize() notify the event "service_container.load_configuration"');

$t->is($dispatcher->hasListeners('component.method_not_found'), true, 'initialize() add a listener to the event "component.method_not_found"');

