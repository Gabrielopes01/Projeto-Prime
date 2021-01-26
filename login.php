<?php

Use \Classes\PageAdmin;
Use \Classes\Page;
Use \Classes\User;

$app->get("/login", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $page->setTpl("login", [
        "erro"=>isset($_SESSION["mensagem"]) ? $_SESSION["mensagem"] : ""
    ]);
});

$app->post("/login", function(){

    User::verifyLogin($_POST["email"], $_POST["senha"]);

});


$app->get("/logout", function(){

/*    $page = new PageAdmin([
          "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('logout');
*/
    User::logout();

});

?>