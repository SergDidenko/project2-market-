<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findByUsername(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
$err_obj=[];
if ($r->getMethod()==="POST") {
	if ($r->request->has('title') && $r->request->has('content') && $r->request->has('tag') && $r->files->has('upload')) {
		$title=htmlentities($r->request->get('title'));
		$content=htmlentities($r->request->get('content'));
		$upload=$r->files->get('upload');
		$tag=htmlentities($r->request->get('tag'));
		$p=new Post;
		$u=$em->createQueryBuilder()
				 ->select('u')
				 ->from('User','u')
				 ->where('u.username=?1')
				 ->setParameter(1,$user)
				 ->getQuery();
		$user_info=$u->getSingleResult();
		$post=$p->setTitle($title)->setContent($content)->setImageName($upload->getClientOriginalName())->setImagePath($upload->move('files')->getPathName())->setUser($user_info);
		$tag=explode(',',str_replace(" ","",$tag));
		$t_repo=$em->getRepository('Tag');
		foreach ($tag as $v) {
			$tags=$t_repo->findOneBy(['tag'=>$v]);
			if ($tags==null) {
				$t=new Tag;
				$t_temp[]=$t->setTag($v)->addPost($post);
				$post->addTag($t);
			}
			else{
				$post->addTag($tags);
			}
		}
		$e_post=$validator->validate($post);
		if (isset($t_temp)) {
			$e_temp=$validator->validate($t_temp);
		}
		if ($e_post->has(0)) {
			foreach ($e_post as $k => $error) {
				$err_obj[$e_post->get($k)->getPropertyPath()][]=$e_post->get($k)->getMessage();
			}
			foreach ($e_temp as $k => $error) {
				$err_obj['tag'][]=$e_temp->get($k)->getMessage();
			}
		}else{
			$em->persist($post);
			if (isset($t_temp)) {
				foreach ($t_temp as $k => $tag) {
					$em->persist($tag);
				}
			}
			$em->flush();
			$redirect->send();	
		}
	}
}

$path=explode('/', $r->getPathInfo());
$page=isset($path[2]) ? $path[2] : 1;
$p=$em->getRepository('Post');
//get all posts and tags for each of them
$query=$p->createQueryBuilder('p')
		 ->select('p','t')
		 ->innerJoin('p.tags','t')
		 ->orderBy('p.createAt')
		 ->getQuery();
$posts=$query->getResult();

// total number of elements

$total=Post::countPost($posts);
$pager=new Pager($total,$page);
$max=$pager->getMax();
$start=$pager->getStart();
$posts=array_slice($posts,$start,$max);
echo $env->render('_blog.twig',['user'=>$admin,'posts'=>$posts, 'page'=>$page,'user_name'=>$user, 'err_obj'=>$err_obj]);

$links=$pager->getLinks();
$maxPage=$pager->getMaxPage();
echo $env->render('_pager.twig',['page'=>$page,'links'=>$links,'maxpage'=>$maxPage]);
