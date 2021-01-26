<?php

Use \Classes\PageAdmin;
Use \Classes\User;


$app->get("/admin", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    User::isAdmin();

    $usuarios = User::getALLUsers();


    $page->setTpl('home', [
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
        "usuarios"=>$usuarios
    ]);

});


$app->get("/admin/add", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    User::isAdmin();

    $page->setTpl("add", [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);
});


$app->post("/admin/add", function(){

    User::addUser($_POST);

});

$app->get("/admin/edit/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    User::isAdmin();

    $usuario = User::getUserByID($id);

    $page->setTpl("edit", [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "usuario"=>$usuario[0]
    ]);

});

?>