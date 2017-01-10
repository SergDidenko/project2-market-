<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findBy(['username'=>$user]);
$admin=$u==null ? null : $u[0]->getAdmin();
$c=$em->createQuery("SELECT c FROM Category c");
$categories=$c->getArrayResult();
$p_repo=$em->getRepository('Product');

if ($r->getMethod()==="POST") {
	if ($r->request->has("price") && $r->request->has("price2")) {
		$price=$r->request->get("price");
		$price_second=$r->request->get("price2");
		if (isset(explode('/',$r->getPathInfo())[2])) {
			$id=explode('/',$r->getPathInfo())[2];
			$p=$p_repo->createQueryBuilder('p')
				  ->select('p')
				  ->innerJoin('p.category', 'c', 'WITH', 'c.id=?1')
				  ->setParameter(1,$id)
				  ->where("p.price>=".$price)
				  ->andWhere("p.price<=".$price_second)
				  ->getQuery();
			$products=$p->getResult();
		}else{
			$p=$p_repo->createQueryBuilder('p')
				  ->select('p')
				  ->where("p.price>=?1")
				  ->andWhere("p.price<=?2")
				  ->setParameter(1,$price)
				  ->setParameter(2,$price_second)
				  ->getQuery();
			$products=$p->getResult();
		}
	}
	elseif($r->request->has('search')){
		$search=htmlentities($r->request->get('search'));
		if (isset(explode('/', $r->getPathInfo())[2])) {
			$id=explode('/',$r->getPathInfo())[2];
			$p=$p_repo->createQueryBuilder('p')
					  ->select('p')
					  ->innerJoin('p.category','c', 'WITH','c.id=?1')
					  ->where("p.productName LIKE ?2")
					  ->orWhere("p.description LIKE ?2")
					  ->setParameter(1,$id)
					  ->setParameter(2,'%'.$search.'%')
					  ->getQuery();
			$products=$p->getResult();
		}else{
			$p=$p_repo->createQueryBuilder('p')
					  ->select('p')
					  ->where("p.productName LIKE ?1")
					  ->orWhere("p.description LIKE ?1")
					  ->setParameter(1,'%'.$search.'%')
					  ->getQuery();
			$products=$p->getResult();
		}
	}
}else{
	if (isset(explode('/',$r->getPathInfo())[2])) {
		$id=explode('/',$r->getPathInfo())[2];
		$p=$p_repo->createQueryBuilder('p')
			  ->select('p')
			  ->innerJoin('p.category', 'c', 'WITH', 'c.id=?1')
			  ->setParameter(1,$id)
			  ->getQuery();
		$products=$p->getResult();
	}else{
		$products=$p_repo->findAll();
	}
}

// update & delete some product

if (isset(explode('/',$r->getPathInfo())[3])) {
	$product_id=explode('/',$r->getPathInfo())[3];
	$category_id=explode('/',$r->getPathInfo())[2];
	$products=$p_repo->findOneBy(['id'=>$product_id]);
	if (isset(explode('/',$r->getPathInfo())[4]) && explode('/',$r->getPathInfo())[4]==="delete") {
		$query=$em->createQueryBuilder()
				  ->select('p')
				  ->from('Product', 'p')
				  ->where('p.id=?1')
				  ->setParameter(1,$product_id)
				  ->getQuery();
		$product=$query->getSingleResult();
		$em->remove($product);
		$em->flush();
		$redirect->send();
	}elseif(isset(explode('/',$r->getPathInfo())[4]) && explode('/',$r->getPathInfo())[4]==="update"){
		$query=$em->createQueryBuilder()
				  ->select('p')
				  ->from('Product', 'p')
				  ->where('p.id=?1')
				  ->setParameter(1,$product_id)
				  ->getQuery();
		$product=$query->getSingleResult();
		$query=$em->createQuery("SELECT c FROM Category c");
		$category=$query->getArrayResult();
		if ($r->getMethod()==="POST") {
			if ($r->request->has("name") && $r->request->has("description") && $r->request->has("price") && $r->request->has("discount") || $r->files->has("upload")) {
				$productName=htmlentities($r->request->get("name"));
				$description=htmlentities($r->request->get("description"));
				$price=htmlentities($r->request->get("price"));
				$discount=htmlentities($r->request->get("discount"));
				$upload=$r->files->get("upload");
				$query=$em->createQueryBuilder()
						  ->select('p')
						  ->from('Product', 'p')
						  ->where('p.id=?1')
						  ->setParameter(1,$product_id)
						  ->getQuery();
				$product=$query->getSingleResult();
				$imageName=$product->getImageName();
				$imagePath=$product->getImagePath();
					foreach ($upload as $k => $image) {
						if ($image!==null) {
							$imageName[]=$image->getClientOriginalName();
							$imagePath[]=$image->move('product_image')->getPathName();
						}
					}
				$product->setProductName($productName)->setDescription($description)->setPrice($price)->setDiscount($discount)->setImageName($imageName)->setImagePath($imagePath);
				$em->flush();
				$redirect->send();	
			}
		}
		echo $env->render('_product_form.twig', ['product'=>$product,'user'=>$admin,'category_id'=>$category_id, 'category'=>$category, 'product_id'=>$product_id, 'button'=>'Update']);
		exit();
	}
	echo $env->render('_catalog_separate.twig',['products'=>$products, 'category_id'=>$category_id, 'product_id'=>$product_id,'user'=>$admin]);

}else{
	$category=[];
	foreach ($products as $key => $value) {
		$category[]=['product'=>$value->getId(),'category'=>$value->getCategory()->getId()];
	}
	echo $env->render('_catalog.twig',['products'=>$products, 'categories'=>$categories, 'category'=>$category,'user'=>$admin]);
}
