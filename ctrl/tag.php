<?php 
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
$path=explode('/', $r->getPathInfo());
$tag=isset($path[2]) ? $path[2] : 1;
$repo=$em->getRepository('Post');
$query=$repo->createQueryBuilder('p')
			->select('p','t')
			->innerJoin('p.tags', 't')
			->getQuery();
$posts=$query->getResult();

echo $env->render('_tag.twig', ['user'=>$admin, 'posts'=>$posts, 'user_name'=>$user,'t'=>$tag]);

