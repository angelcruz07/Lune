<?php 

namespace Lune\Tests;

use Lune\HttpMethod;
use Lune\Router; 
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase { 

    public function test_revolve_basic_route_white_callback_action() { 
        $uri = '/test';
        $action = fn () => "test";
        $router = new Router();
        $router -> get($uri, $action); 

        $this->assertEquals($action, $router -> resolve($uri, HttpMethod::GET->value));
    }
    public function test_resolve_multiple_basic_routes_with_callback_action(){ 
        $routes = [ 
            '/test' => fn () => "test",	
            '/foo' => fn () => "foo",
            '/bar' => fn () => "bar",
            'long/nested/router' => fn () => "long nested route",
        ]; 

        $router = new Router(); 

        foreach( $routes as $uri => $action) { 
            $router->get($uri, $action);
        }
        foreach($routes as $uri => $action){ 
            $this->assertEquals($action, $router->resolve($uri, HttpMethod::GET->value));
        }
    }

    public function test_resolve_multiple_basic_routes_with_callback_action_for_different_http_method(){
        $routes = [
            [HttpMethod::GET, "/test",  fn () => "get"],
            [HttpMethod::POST, "/test",  fn () => "post"],
            [HttpMethod::PUT, "/test",  fn () => "put"],
            [HttpMethod::PATCH, "/test",  fn () => "patch"],
            [HttpMethod::DELETE, "/test",  fn () => "delete"],

            [HttpMethod::GET, "/random/get",  fn () => "get"],
            [HttpMethod::POST, "/random/nested/post",  fn () => "post"],
            [HttpMethod::PUT, "/put/random/router",  fn () => "put"],
            [HttpMethod::PATCH, "/some/get/ramdom",  fn () => "patch"],
            [HttpMethod::DELETE, "/d",  fn () => "delete"],
        ];
        $router = new Router(); 
        foreach ($routes as [$method, $uri, $action]) { 
            $router ->{strtolower($method->value)}($uri, $action);    
        }
        foreach ($routes as [$method, $uri, $action]){ 
            $this -> assertEquals($action, $router->resolve($uri, $method->value));
        }

    }
}


?>