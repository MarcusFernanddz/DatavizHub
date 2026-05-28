<?php
session_start();
include('../connect/conexao.php');

if(isset($_POST['login'])):

    $email = trim($_POST['emailnumero']);
    $senha = trim($_POST['senha']);

    $stmt = $conn->prepare("SELECT idconta, emailnumero, senha FROM teste WHERE emailnumero = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){

        $dados = $resultado->fetch_assoc();

        if(password_verify($senha, $dados['senha'])){

            
            $_SESSION['id'] = $dados['idconta'];
            $_SESSION['emailnumero'] = $dados['emailnumero'];

            header("Location: hub.php");
            exit();

        }else{
            echo "<script>alert('Senha incorreta!');</script>";
        }

    }else{
        echo "<script>alert('Usuário não encontrado!');</script>";
    }

endif;

if(isset($_POST['esq'])):
    header("Location: esqueci_senha.php");
    exit();
endif;

if(isset($_POST['cad'])):
    header("Location: hugolindo.php");
    exit();
endif;
?>

<html>
<head>
<style>
    body {
        background-image: url('https://media1.tenor.com/m/lP9CwPDtCB0AAAAC/dante-dmc.gif');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        height: auto;
        margin: 0;
        color: blue;
    }
</style>
</head>

<body>

<h1>LOGIN</h1>

<form method="post" action="">
    
    <label>Email</label>
    <input name="emailnumero" size="25" type="text" autocomplete="off" required>
    
    <br><br>

    <label>Senha</label>
    <input name="senha" size="10" type="password" autocomplete="off" required>

    <br><br>

    <button type="submit" name="login">Entrar</button>

</form>
<form method="post" action="">
    <button type="submit" name="esq">Esqueci minha senha</button>
</form>
<form method="post" action="">
    <button type="submit" name="cad">Cadastrar</button>
</form>

</body>
</html>