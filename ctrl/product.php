<?php
$user=$session->has('user') ? $session->get('user') : null;
$repo=$em->getRepository('User');
$u=$repo->findBy(['username'=>$user]);
$admin=$u==null ? $redirect->send() : $u[0]->getAdmin(); // get info from column admin
$err_obj=[];

if ($admin=="admin" || $admin=="root") {
	if ($r->getMethod()==="POST") {
		if ($r->request->has('name') && $r->request->has('description') && $r->request->has('price') && $r->files->has('upload') && $r->request->has('discount') && $r->request->has('category')) {
			$productName=htmlentities($r->request->get('name'));
			$description=htmlentities($r->request->get('description'));
			$price=htmlentities($r->request->get('price'));
			$product=new Product;
			$product->setProductName($productName)->setDescription($description)->setPrice($price);
			$e=$validator->validate($product);
			if ($e->has(0)) {
				foreach ($e as $k => $error) {
					$err_obj[$e->get($k)->getPropertyPath()][]=$e->get($k)->getMessage();
				}
			}else{
				$upload=$r->files->get('upload');
				foreach ($upload as $k => $image) {
					$imageName[]=$image->getClientOriginalName();
					$imagePath[]=$image->move('product_image')->getPathName();
				}
				$category=htmlentities($r->request->get('category'));
				$discount=htmlentities($r->request->get('discount'));
				$repo=$em->getRepository('Category');
				$category=$repo->findBy(['categoryName'=>$category]);
				$product=$product->setProductName($productName)->setDescription($description)->setPrice($price)->setImageName($imageName)->setImagePath($imagePath)->setDiscount($discount)->setCategory($category[0]);
				$em->persist($product);
				$em->flush();
				$redirect->send();
			}
		}
	}
	$query=$em->createQuery("SELECT c FROM Category c");
	$category=$query->getArrayResult();
	echo $env->render('_product_form.twig', ['user'=>$admin, 'category'=>$category, 'button'=>'Create', 'err_obj'=>$err_obj]);
}else{
	$redirect->send();
}