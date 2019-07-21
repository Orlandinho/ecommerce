<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app->get('/admin/users', function() {

	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : "";
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if ($search != ''){

		$pagination = User::getPageSearch($search, $page);

	} else {

		$pagination = User::getPage($page);
	}

	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++ )
	{

		array_push($pages, [
			'href'=>'/admin/users?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);
	}
    
	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$pagination['data'],
		'search'=>$search,
		'pages'=>$pages
	));

});

$app->get('/admin/users/create', function() {

	User::verifyLogin();
    
	$page = new PageAdmin();

	$page->setTpl("users-create");

});

$app->get('/admin/users/:iduser/delete', function($iduser) {//salvar edição de algum usuário

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

$app->get('/admin/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);
    
	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

$app->post('/admin/users/create', function() {//guardar no banco de dados

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [//criptografa a senha antes de enviar pro banco de dados

 		"cost"=>12

 	]);

 	$user->setData($_POST);
    
	$user->save();

	header("Location: /admin/users");
	exit;

});

$app->post('/admin/users/:iduser', function($iduser) {//salvar edição de algum usuário

	User::verifyLogin();
    
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});

?>