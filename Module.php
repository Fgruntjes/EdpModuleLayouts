<?php
namespace EdpModuleLayouts;

class Module
{
    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller      = $e->getTarget();
            $controllerClass = get_class($controller);
            $config          = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$controllerClass])) {
                $controller->layout($config['module_layouts'][$controllerClass]);
                return;
            }
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 100);
    }
}
