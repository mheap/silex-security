<?php

namespace mheap;

use Silex\Application;
use Silex\ServiceProviderInterface;

class SecurityServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app){

        // By default, we're empty
        $params = array(
            "security.firewalls" => array()
        );

        // Set up the defaults for login/logout
        $defaultSecurityPages = array(
            "login" => "/login",
            "logout" => "/logout",
            "login_redirects_to" => "/",
            "login_check" => "/_login_check"
        );

        if (!isset($app['mheap.security.pages'])){
            $app['mheap.security.pages'] = $defaultSecurityPages;
        } else {
            $app['mheap.security.pages'] = array_merge(
                $defaultSecurityPages, $app['mheap.security.pages']
            );
        }

        // Make sure we can access login and login_check unauthenticated
        $login_routes = array(
            $app['mheap.security.pages']['login']
        );

        // Merge our anon routes with the login routes
        if (!isset($app['mheap.security.open_routes'])){
            $app['mheap.security.open_routes'] = $login_routes;
        } else {
            $app['mheap.security.open_routes'] = array_merge(
                $app['mheap.security.open_routes'], $login_routes
            );
        }

        // Did we get a user provider?
        $interfaces = class_implements($app['mheap.security.user_provider']);
        if (!isset($interfaces['Symfony\Component\Security\Core\User\UserProviderInterface'])){
            throw new \Exception("mheap.security.user_provider must implement UserProviderInterface");
        }

        // Secure every other endpoint by default
        $params['security.firewalls']['secured'] = array(
            "pattern" => '^.*$',
            'form' => array('default_target_path' => $app['mheap.security.pages']['login_redirects_to'], 'login_path' => $app['mheap.security.pages']['login'], 'check_path' => $app['mheap.security.pages']['login_check']),
            'logout' => array('logout_path' => $app['mheap.security.pages']['logout']),
            'users' => $app['mheap.security.user_provider'],
            'anonymous' => true
        );

        // And register our security provider
        $app->register(new \Silex\Provider\SecurityServiceProvider(), $params);
    }

    public function boot(Application $app){
    }

}
