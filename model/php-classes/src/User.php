<?php

namespace Classes;

Use \Classes\Sql;

class User{

    //Pega 1 usuario pelo id
    public static function getUserById($id){

        $sql = new Sql();

        $usuario = $sql->select("SELECT * FROM Usuario WHERE Id = :id", [
            ":id"=>$id
        ]);

        return $usuario;

    }

    //Pega todos os usuários do  banco
    public static function getALLUsers(){

        $sql = new Sql();

        $usuarios = $sql->select("SELECT * FROM Usuario");

        return $usuarios;

    }

    //Verifica se o cadastro do usuário foi inserido corretamente
    public static function verifyLogin($user,$password){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE Email = :user", [
            ":user"=>$user
        ]);

        if(count($resultado) > 0){
            if(password_verify($password, $resultado[0]["Senha"])){

                $_SESSION['nome'] = $resultado[0]["Nome"];
                $_SESSION['adm'] = $resultado[0]["Adm"];
                $_SESSION['email'] = $resultado[0]["Email"];

                header("Location: /");
                exit;

            } else {
                $_SESSION['mensagem'] = "Usuário e/ou Senha Inválidos";
                header("Location: /login");
                exit;
            }

        }

        $_SESSION['mensagem'] = "Usuário e/ou Senha Inválidos";
        header("Location: /login");
        exit;


    }


    //Verifica se o usuário tem permissão de administrador
    public static function isAdmin(){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE Email = :email", [
            ":email"=>$_SESSION["email"]
        ]);

        if($resultado[0]["Adm"] == 0) {
            $_SESSION["mensagem"] = "Você não tem permissão de acessar esta página";
            header("Location: /");
            exit;
        }


    }

    //Verifica se o Email digitado ja foi cadastrado
    public static function verifyEmail($email){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE Email = :email", array(
            ":email"=>$email
        ));

        if(count($resultado) > 0){
            return true;
        }else{
            return false;
        }

    }

    //Adiciona um usuário
    public static function addUser($parametros){

        //Verificando se o campo Nome foi preenchido
        if($parametros["nome"] === ""){
            $_SESSION['mensagem'] = "Campo Nome Obrigatório";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o campo Email foi preenchido
        if(!isset($parametros["email"]) || $parametros["email"] === ""){
            $_SESSION["mensagem"] = "Campo Email Obrigatório";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o campo Senha foi preenchido
        if($parametros["senha"] === ""){
            $_SESSION['mensagem'] = "Campo Senha Obrigatório";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o campo Senha foi preenchido
        if(!isset($parametros["tipo"])){
            $_SESSION['mensagem'] = "Campo Tipo de Usuário Obrigatório";
            header("Location: /admin/add");
            exit;
        }

        $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

        //Verificando se o Email esta correto
        if (!preg_match($checkEmail, $parametros["email"])) {
            $_SESSION['mensagem'] = "Email Inválido";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se a Senha bate com o Confirmar Senha
        if($parametros["senha"] !== $parametros["csenha"]){
            $_SESSION['mensagem'] = "Senhas não Conferem";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o Email ja esta cadastrado
        if(User::verifyEmail($parametros["email"])){
            $_SESSION['mensagem'] = "Email ja Cadastrado";
            header("Location: /admin/add");
            exit;
        }

        $sql = new Sql();

        $sql->query("INSERT Usuario VALUES (:nome, :email, DEFAULT, :senha, :adm)", [
            ":nome"=>$parametros["nome"],
            ":email"=>$parametros["email"],
            ":senha"=>password_hash($parametros["senha"], PASSWORD_DEFAULT),
            ":adm"=>$parametros["tipo"]
        ]);

        $_SESSION["mensagem"] = "Usuário Cadastrado com Sucesso";

        header("Location: /admin");
        exit;

    }

    //Sai da contsa do usuario
    public static function logout(){

        session_destroy();
        header("Location: /");
        exit;

    }


    //Esta função verifica se o usário esta logado para acessar tal pagina
    public static function checkLogin(){
        if(!isset($_SESSION['nome']) || $_SESSION['nome'] === ""){
            $_SESSION['mensagem'] = "Faça o login para acessar a página";
            header("Location: /login");
            exit;
        }
    }



//Fim da Classe
}


?>