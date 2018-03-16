<?php

use \bitExpert\Disco\BeanFactoryConfiguration;
use \bitExpert\Disco\AnnotationBeanFactory;
use \bitExpert\Disco\BeanFactoryRegistry;

// Load configuration
$config = require __DIR__ . '/config.php';

$beanConfig = new BeanFactoryConfiguration($config['di']['cache']);

if (APPLICATION_ENVIRONMENT === 'production') {
    // Use cached proxies in production
    $beanConfig->setProxyAutoloader(
        new \ProxyManager\Autoloader\Autoloader(
            new \ProxyManager\FileLocator\FileLocator($config['di']['cache']),
            new \ProxyManager\Inflector\ClassNameInflector('Disco')
        )
    );
}

/** @var \Interop\Container\ContainerInterface $container */
$beanFactory = new AnnotationBeanFactory(
    $config['di']['class'],
    ['expressive' => $config],
    $beanConfig);
BeanFactoryRegistry::register($beanFactory);

return $beanFactory;
