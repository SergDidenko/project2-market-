<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
$err_obj=[];
if ($user==null) {
	if (explode('/', $r->getPathInfo())[2]==='registration') {
		if ($r->getMethod()==="POST") {
			if ($r->request->has("username") && $r->request->has("password")) {
				$user=new User;
				$username=htmlentities($r->request->get('username'));
				$password=htmlentities($r->request->get("password"));

				$user->setUsername($username)->setPassword($password);
				//validation
				$e=$validator->validate($user);
				if ($e->has(0)) {
					foreach ($e as $k=>$error) {
						$err_obj[$e->get($k)->getPropertyPath()][]=$e->get($k)->getMessage();
					}
				}else{
					$user->setUsername($username)->setPassword(password_hash($password,1));
					$u_repo=$em->getRepository('User');
					$users=$u_repo->findAll();
					if ($users==[]) {
						$user->setAdmin('root');
					}
					else{
						$user->setAdmin('user');
					}
					$em->persist($user);
					$em->flush();
					$redirect->send();
				}
			}else{
					$redirect->send();
					exit();
			}
		}
		echo $env->render('_user.twig', ['title'=>'Registration form', 'user'=>$admin, 'button'=>'Register', 'err_obj'=>$err_obj]);
	}
	elseif (explode('/', $r->getPathInfo())[2]==='login') {
		if ($r->getMethod()==="POST") {
			if ($r->request->has('username') && $r->request->has('password')) {
				$username=htmlentities($r->request->get('username'));
				$password=htmlentities($r->request->get('password'));
				//validation
				$user=new User;
				$user->setUserName($username)->setPassword($password);
				$e=$validator->validate($user);
				if ($e->has(0)) {
					foreach ($e as $k => $error) {
						$err_obj[$e->get($k)->getPropertyPath()][]=$e->get($k)->getMessage();
					}
				}else{
					//check some user
					$query=$em->createQueryBuilder()
							  ->select('u')
							  ->from('User', 'u')
							  ->where('u.username=?1')
							  ->setParameter(1,$username)
							  ->getQuery();
					$user=$query->getSingleResult();
					if (!empty($user)) {
						if (password_verify($password, $user->getPassword())) {
							$session->set('user',$username);
							$redirect->send();
							exit();
						}else{
							echo "Password incorrect";
						}
					}else{
						echo "User not valid";
					}
				}
			}
		}
		echo $env->render('_user.twig', ['title'=>'Login form','user'=>$admin, 'button'=>'Login', 'err_obj'=>$err_obj]);
	}
}
if ($user=="root") {
	if (explode('/', $r->getPathInfo())[2]==='registration') {
		if ($r->getMethod()==="POST") {
			if ($r->request->has("username") && $r->request->has("password") && $r->request->has("admin")) {
				$username=$r->request->get("username");
				$password=$r->request->get("password");
				$user=new User;
				//validation
				$user->setUsername($username)->setPassword($password);
				$e=$validator->validate($user);
				if ($e->has(0)) {
					foreach ($e as $k => $error) {
						$err_obj[$e->get($k)->getPropertyPath()][]=$e->get($k)->getMessage();
					}
				}else{
					$user->setUsername(htmlentities($r->request->get('username')))->setPassword(password_hash(htmlentities($r->request->get('password')),1))->setAdmin($r->request->get('admin'));
					$em->persist($user);
					$em->flush();
					$redirect->send();
				}
			}else{
				$redirect->send();
				exit();
			}
		}
		echo $env->render('_user.twig', ['title'=>'Registration form', 'user'=>$admin, 'button'=>'Register', 'err_obj'=>$err_obj]);
	}
}
if ($user!==null) {
	if (explode('/',$r->getPathInfo())[2]==='logout') {
		$session->remove('user');
		$redirect->send();
	}
}