<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findBy(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
echo $env->render('_not_found.twig', ['user'=>$admin, 'header'=>"NOT FOUND"]);