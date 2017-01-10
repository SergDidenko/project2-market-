<?php 
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findBy(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
if (isset(explode('/', $r->getPathInfo())[2]) && !isset(explode('/', $r->getPathInfo())[3])) {
	$post_id=explode('/', $r->getPathInfo())[2];
	$query=$em->createQueryBuilder()
			  ->select('p')
			  ->from('Post', 'p')
			  ->where('p.id=?1')
			  ->setParameter(1,$post_id)
			  ->getQuery();
	$post=$query->getResult();
	echo $env->render('_blog.twig', ['posts'=>$post, 'user'=>$admin, 'action'=>'separate', 'user_name'=>$user]);
}else{
	if (isset(explode('/', $r->getPathInfo())[2]) && explode('/', $r->getPathInfo())[3]==="delete") {
		$post_id=explode('/', $r->getPathInfo())[2];
		$query=$em->createQueryBuilder()
				  ->select('p')
				  ->from('Post', 'p')
				  ->where('p.id=?1')
				  ->setParameter(1,$post_id)
				  ->getQuery();
		$post=$query->getSingleResult();
		$em->remove($post);
		$em->flush();
		$redirect->send();
	}elseif (isset(explode('/', $r->getPathInfo())[2]) && explode('/', $r->getPathInfo())[3]==="update") {
		$post_id=explode('/', $r->getPathInfo())[2];
		$repo=$em->getRepository('Post');
		$query=$repo->createQueryBuilder('p')
				  ->select('p','t')
				  ->innerJoin('p.tags','t')
				  ->where('p.id=?1')
				  ->setParameter(1,$post_id)
				  ->getQuery();
		$post=$query->getArrayResult()[0];
		$tags_initial=$post['tags'];
		foreach ($tags_initial as $k => $v) {
			$tags_in[]=$v['tag'];
		}
		if ($r->getMethod()==="POST") {
			if ($r->request->has('title') && $r->request->has('content') && $r->request->has('tag') || $r->files->has('upload')) {
				$title=htmlentities($r->request->get('title'));
				$content=htmlentities($r->request->get('content'));
				$tag=htmlentities($r->request->get('tag'));
				$post=$query->getResult()[0];
				if ($r->files->get('upload')!==null) {
					$upload=$r->files->get('upload');
					$imageName=$upload->getClientOriginalName();
					$imagePath=$upload->move('files')->getPathName();
					$post->setImageName($imageName)->setImagePath($imagePath);
				}
				$post->setTitle($title)->setContent($content);
				$tags=explode(',',str_replace(" ", "", $tag));
				$repo=$em->getRepository('Tag');
				foreach ($tags as $k => $v) {
					$tag=$repo->findOneBy(['tag'=>$v]);
					if (!in_array($v, $tags_in)) {
						if ($tag===null) {
							$t=new Tag;
							$t_temp[]=$t->setTag($v)->addPost($post);
							$post->addTag($t);
						}else{
							$post->addTag($tag);
						}
					}
				}
				if (!empty($t_temp)) {
					foreach ($t_temp as $k => $tag) {
						$em->persist($tag);
					}
				}
				$em->flush();
				$redirect->send();
			}
		}
		echo $env->render('_post.twig',['post'=>$post, 'user'=>$admin, 'button'=>'Update']);
	}
}