<?php
use Symfony\Component\Validator\Constraints as Assert;
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? $redirect->send() : $u[0]->getAdmin();
$err_obj=null;
if ($admin=="admin" || $admin=="root") {
	if ($r->getMethod()==="POST") {
		if ($r->request->has('name')) {
			$categoryName=$r->request->get('name');
			$category=new Category;
			$category=$category->setCategoryName($categoryName);
			$e=$validator->validate($category);
			if ($e->has(0)) {
				$err_obj=$e->get(0)->getMessage();
			}else{
				$err_obj=null;
				$em->persist($category);
				$em->flush();
				$redirect->send();
			}
		}
	}
	echo $env->render('_category_form.twig', ['user'=>$admin, 'err_obj'=>$err_obj]);
}else{
	$redirect->send();
}