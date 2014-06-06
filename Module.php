<?php
namespace EdpModuleLayouts;

use Zend\Mvc\MvcEvent;

class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            /** @var $e MvcEvent */
            if(!$e->getRouteMatch()) return;

            $controller      = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config          = $e->getApplication()->getServiceManager()->get('config');
            $routeMatch      = $e->getRouteMatch();
            $controllerName  = $routeMatch->getParam('controller', 'default');
            $action          = $routeMatch->getParam('action', 'default');

            $config = isset($config['module_layouts']) ? $config['module_layouts'] : array();

            // Check on module
            if (!isset($config[$moduleNamespace])) return;
            $config = $config[$moduleNamespace];
            if (is_string($config)) {
                $controller->layout($config);
                return;
            }

            // Check on controller
            if (!isset($config[$controllerName])) return;
            $config = $config[$controllerName];

            if (is_string($config)) {
                $controller->layout($config);
                return;
            }

            // Check on action
            if (!isset($config[$action])) return;
            $controller->layout($action);
        }, 100);
    }
}
