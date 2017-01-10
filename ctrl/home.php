<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin(); // get info from column admin
$c=$em->createQueryBuilder()
		  ->select('p')
		  ->from('Post','p')
		  ->getQuery();
$con=$c->getResult();
// total number of elements
$count=Post::countPost($con);
$p=$em->getRepository('Post');
$query=$p->createQueryBuilder('p')
		 ->select('p','t')
		 ->innerJoin('p.tags','t')
		 ->getQuery();
$posts=$query->getResult();
$posts=array_slice($posts, $count-3, 3);
$p_repo=$em->getRepository('Product');
$products=$p_repo->findBy(['discount'=>'yes']);
echo $env->render("_home.twig", ['user'=>$admin, 'posts'=>$posts, 'products'=>$products, 'user_name'=>$user]);