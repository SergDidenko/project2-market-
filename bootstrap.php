<?php
$loader=require "vendor/autoload.php";

//connect Twig
$load=new Twig_Loader_Filesystem('tpl');
$env=new Twig_Environment($load);

//connect Doctrine
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$con=[
	 'driver'=>'pdo_mysql',
	 'host'=>'localhost',
	 'user'=>'root',
	 'password'=>'',
	 'dbname'=>'doctrine1'
];

//add fifth parameter special for all classes in model with considering using there AnnotationReader instead SimpleAnnotationReader
$conf=Setup::createAnnotationMetadataConfiguration(['./model'],false, null, null, false);
$em=EntityManager::create($con,$conf);

//connect Validator

use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
$builder=new ValidatorBuilder;
$builder->enableAnnotationMapping();
$validator=$builder->getValidator();

//connect additional namespace

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

$r=Request::createFromGlobals();
$session=new Session();
$session->start();
$redirect=new RedirectResponse('/');


