<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? $redirect->send() : $u[0]->getAdmin();
$err_obj=[];
if ($user=='root') {
	if (!isset(explode('/',$r->getPathInfo())[2])) {
		$users=$em->getRepository('User');	
		$users=$users->findBy(['admin'=>['admin', 'user']]);
		echo $env->render('_root.twig', ['users'=>$users, 'user'=>$admin]);
	}
	if(isset(explode('/',$r->getPathInfo())[2]) && explode('/',$r->getPathInfo())[2]==="update"){
		$user_id=explode('/',$r->getPathInfo())[3];
		$users=$em->getRepository('User');
		$users=$users->findOneBy(['id'=>$user_id]);
		$username=$users->getUsername();
		$admin_name=$users->getAdmin();
		if ($r->getMethod()==="POST") {
			if ($r->request->has('username') && $r->request->has('admin')) {
				$user=$r->request->get('username');
				$u=new User;
				$u->setUsername($user);
				$e=$validator->validate($u);
				if ($e->has(0)) {
					foreach ($e as $k => $error) {
						$err_obj[$e->get($k)->getPropertyPath()][]=$e->get($k)->getMessage();
					}
				}else{
					$adm=$r->request->get('admin');
					if ($user===$username && $adm===$admin_name) {
						$redirect->send();
					}
					elseif($user===$username && $adm!==$admin_name){
						$users->setAdmin($adm);
					 	$em->flush();
					}
					elseif ($user!==$username && $adm===$admin_name) 
					{
						$users->setUsername($user);
						$em->flush();
					}else{
					 	$users->setUsername($user)->setAdmin($adm);
					 	$em->flush();
					 }
					 $redirect->send();
				}
			}
		}
		echo $env->render('_user.twig', ['users'=>$users, 'user'=>$admin, 'username'=>$username,'button'=>'Update', 'err_obj'=>$err_obj]);
	}
	if (isset(explode('/',$r->getPathInfo())[2]) && explode('/',$r->getPathInfo())[2]==="delete") {
		$user_id=explode('/',$r->getPathInfo())[3];
		$users=$em->getRepository('User');
		$user=$users->find($user_id);
		$em->remove($user);
		$em->flush();
		$redirect->send();
	}
}