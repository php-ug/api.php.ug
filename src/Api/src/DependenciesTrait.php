<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Api;

use bitExpert\Disco\BeanFactoryRegistry;
use Doctrine\ORM\EntityManager;
use ContainerInteropDoctrine\EntityManagerFactory;
use Geocoder\Geocoder;
use Geocoder\HttpAdapter\CurlHttpAdapter;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use GuzzleHttp\Client;
use Phpug\Api\Acl\RoleManager;
use Phpug\Api\Acl\UsersGroupAssertion;
use Phpug\Api\Action\GetUsergroupListAction;
use Phpug\Api\Action\HomePageAction;
use Phpug\Api\Action\PingAction;
use Phpug\Api\Cache\Cache;
use Phpug\Api\Cache\Populator\CountryCode;
use Phpug\Api\Service\GetAllUsergroups;
use Phpug\Api\Validator\IsRoleAllowedToSeeGroup;
use Phpug\Api\Validator\IsUsergroupActive;
use Phpug\Api\Validator\UsergroupValidatorChain;
use Phpug\Api\Mapper\AddAccessRightsForUsergroupMapper;
use Phpug\Api\Mapper\AddUsergroupCountryMapper;
use Phpug\Api\Mapper\GroupcontactMapper;
use Phpug\Api\Mapper\GrouptypeMapper;
use Phpug\Api\Mapper\ServiceMapper;
use Phpug\Api\Mapper\TagMapper;
use Phpug\Api\Mapper\UsergroupCollectionMapper;
use Phpug\Api\Mapper\UsergroupMapper;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Rbac\Role;

trait DependenciesTrait
{
    /**
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getHomePageAction() : HomePageAction
    {
        $router   = $this->getBeanFactory()->get(RouterInterface::class);
        $template = $this->getBeanFactory()->has(TemplateRendererInterface::class)
            ? $this->getBeanFactory()->get(TemplateRendererInterface::class)
            : null;

        return new HomePageAction($router, $template);
    }

    /**
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getPingAction() : PingAction
    {
        return new PingAction();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getTagMapper() : TagMapper
    {
        return new TagMapper();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getServiceMapper() : ServiceMapper
    {
        return new ServiceMapper();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getGroupTypeMapper() : GrouptypeMapper
    {
        return new GrouptypeMapper();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getGroupContactMapper() : GroupcontactMapper
    {
        return new GroupcontactMapper();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getAddUsergroupCountryMapper() : AddUsergroupCountryMapper
    {
        return new AddUsergroupCountryMapper(
            $this->getUsergroupMapper(),
            $this->getCountryCodeCache()
        );
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"type" = true}),
     *     @Alias({"name" = "Phpug\Api\Mapper\UsergroupMappable"})
     * }})
     */
    public function getAddAccessRightsForUsergroupMapper() : AddAccessRightsForUsergroupMapper
    {
        return new AddAccessRightsForUsergroupMapper(
            $this->getAddUsergroupCountryMapper(),
            $this->getUsersGroupAssertion(),
            $this->getAcl(),
            $this->getRoleManager()
        );
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getUsergroupMapper() : UsergroupMapper
    {
        return new UsergroupMapper(
            $this->getGroupContactMapper(),
            $this->getGroupTypeMapper(),
            $this->getTagMapper()
        );
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getUsergroupCollectionMapper() : UsergroupCollectionMapper
    {
        $chain = new UsergroupValidatorChain();
        $chain->add(new IsUsergroupActive());
        $chain->add(new IsRoleAllowedToSeeGroup(
            $this->getAcl(),
            $this->getRoleManager()
        ));
        return new UsergroupCollectionMapper(
            $this->getAddAccessRightsForUSergroupMapper(),
            $chain
        );
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getUsergroupValidatorChain() : UsergroupValidatorChain
    {
        return new UsergroupValidatorChain();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getAcl() : Acl
    {
        $config = $this->config();
        $config = $config['acl'];

        if (!isset($config['routes'])) {
            throw new \Exception('Invalid ACL Config found');
        }

        $roles = $config['roles'];
        if (!isset($roles['guest'])) {
            $roles['guest'] = '';
        }

        $admins = $config['admins'];
        if (! isset($admins)) {
            throw new \UnexpectedValueException('No admin-user set');
        }

        $acl = new Acl();

        foreach ($roles as $name => $parent) {
            if (!$acl->hasRole($name)) {
                if (empty($parent)) {
                    $parent = array();
                } else {
                    $parent = explode(',', $parent);
                }

                $acl->addRole(new GenericRole($name), $parent);
            }
        }

        foreach ($config['routes'] as $permission => $routes) {
            foreach ($routes as $route => $role) {
                if ($route !== '*') {
                    if (!$acl->hasResource($route)) {
                        $acl->addResource(new GenericResource($route));
                    }
                }

                $assert = null;
                if (is_array($role)) {
                    $assert = $this->getBeanFactory()->get($role['assert']);
                    $role   = $role['role'];
                }

                $role = explode(',', $role);

                foreach( $role as $roleItem) {
                    switch ($permission) {
                        case 'allow':
                            $acl->allow($roleItem, $route, null, $assert);
                            break;
                        case 'deny':
                            $acl->deny($roleItem, $route, null, $assert);
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        return $acl;
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getRoleManager() : RoleManager
    {
        $config = $this->config();
        $config = $config['acl'];

        if (! isset($config['admins'])) {
            throw new \UnexpectedValueException('No admin-user set');
        }

        $roleManager = new RoleManager();
        $roleManager->setAdmins($config['admins'])
                    ->setAdminRole('admin')
                    ->setDefaultRole('guest')
                    ->setLoggedInRole('member');

        return $roleManager;
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getUsersGroupAssertion() : UsersGroupAssertion
    {
        return new UsersGroupAssertion();
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getGetUsergroupListAction() : GetUsergroupListAction
    {
        return new GetUsergroupListAction(
            $this->getGetAllUsergroups(),
            $this->getUsergroupCollectionMapper()
        );
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getGetAllUsergroups() : GetAllUsergroups
    {
        return new GetAllUsergroups(
            $this->getEntityManager()
        );
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getEntityManager() : EntityManager
    {
        $emf = new EntityManagerFactory();

        return $emf($this->getBeanFactory());
    }

    /**  @Bean({"aliases" = {@Alias({"name" = "CountryCodeCache"})}}) */
    public function getCountryCodeCache() : Cache
    {
        $countryCodeCache = new Cache();
        $countryCodeCache->setServiceManager($this->getBeanFactory());
        $countryCodeCache->setPopulator(new CountryCode($this->getGeocoder()));

        return $countryCodeCache;
    }

    /**  @Bean({"aliases" = {@Alias({"type" = true})}}) */
    public function getGeocoder() : Geocoder
    {

        $locale   = 'en';
        $adapter  = new \Http\Adapter\Guzzle6\Client();
//            null,
//            null,
//            'PHP.ug country-locator - info@php.ug'
//        );

        $provider = new Nominatim(
            $adapter,
            'http://nominatim.openstreetmap.org',
            $locale
        );

        return new StatefulGeocoder($provider);
    }

}
