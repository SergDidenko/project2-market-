<?php

//connect routing

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;


$collection=new RouteCollection;
$collection->add('home', new Route('/'));
$collection->add('about', new Route('/about'));
$collection->add('product', new Route('/prod'));
$collection->add('category', new Route('/category'));
$collection->add('user', new Route('/user/{crud}', ['crud'=>null], ['crud'=>'(registration|login|logout)']));
$collection->add('catalog', new Route('/catalog/{id}/{product}/{crud}', ['id'=>null, 'product'=>null, 'crud'=>null], ['id'=>'\d{1,2}', 'product'=>'\d{1,3}', 'crud'=>'(delete|update)']));
$collection->add('root', new Route('/root/{crud}/{id}', ['crud'=>null, 'id'=>null], ['id'=>'\d{1,3}', 'crud'=>'(update|delete)']));
$collection->add('blog', new Route('/blog/{page}', ['page'=>1], ['page'=>'\d{1,3}']));
$collection->add('post', new Route('/post/{id}/{crud}', ['id'=>null, 'crud'=>null ], ['id'=>'\d{1,3}', 'crud'=>'(update|delete)']));
$collection->add('tag', new Route('/tag/{id}', ['id'=>null], ['id'=>'\d{1,3}']));


$context=new RequestContext;
$context->fromRequest($r);
$matcher=new UrlMatcher($collection, $context);
$path=$r->getPathInfo();
$generator=new UrlGenerator($collection,$context);
try {
    extract($matcher->match($path), EXTR_SKIP);
    ob_start();
    require sprintf('ctrl/%s.php', $_route);
    $response = new Response(ob_get_clean());
} catch (ResourceNotFoundException $e) {
	require 'ctrl/not_found.php';
    $response = new Response('','404');
}catch (Exception $e) {
	require 'ctrl/server_error.php';
    $response = new Response('', '500');
}
$response->send();
