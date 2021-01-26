<?php

Use \Classes\Page;

$app->get("/", function(){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
        "adm"=>isset($_SESSION['adm'])? $_SESSION['adm']:''
    ]);

    $page->setTpl('home', [
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});



?>