<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
$err_obj=[];
if ($r->getMethod()==="POST") {
	if ($r->request->has('title') && $r->request->has('email') && $r->request->has('text')) {
		$subject=htmlentities($r->request->get('title'));
		$from=htmlentities($r->request->get('email'));
		$message=htmlentities($r->request->get('text'));
		$mail=new Mail($subject, $from, $message);
		$e=$validator->validate($mail);
		if ($e->has(0)) {
			foreach ($e as $k => $error) {
				$err_obj[$e->get($k)->getPropertyPath()][]=$e->get($k)->getMessage();
			}
		}else{
			$mail->createMail();
			$redirect->send();			
		}
	}
}
echo $env->render("_about.twig", ['user'=>$admin, 'err_obj'=>$err_obj]);