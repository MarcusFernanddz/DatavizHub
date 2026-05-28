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
<h1>CADASTRAR</h1>

<form method="post" action="">
    <label>Email</label>
    <input name="emailnumero" size="25" type="text" autocomplete="off" required>
    <br><br>

    <label>Senha</label>
    <input name="senha" size="10" type="password" autocomplete="off" required>
    <br><br>	

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>

<form method="post" action="">
    <button type="submit" name="l">Login</button>
</form>

</body>
</html>

<?php
include('../connect/conexao.php');

function dominioExiste($email) {
    $dominio = substr(strrchr($email, "@"), 1);
    return checkdnsrr($dominio, "MX");
}

if (isset($_POST['cadastrar'])):

    $email = trim($_POST['emailnumero']);  
    $senha = trim($_POST['senha']);
    $errosdom = [];

    
    $is_email = filter_var($email, FILTER_VALIDATE_EMAIL);
    
    if (!$is_email) {
        $errosdom[] = "O formato do e-mail é inválido.";
        } elseif (!dominioExiste($email)) {
            $errosdom[] = "Este domínio de e-mail não parece ser real.";
            }
            
            
    if (strlen($senha) < 8) {
    $erros_senha[] = "pelo menos 8 caracteres";
    }
    if (!preg_match('/[a-z]/', $senha)) {
        $erros_senha[] = "uma letra minúscula";
    }
    if (!preg_match('/[A-Z]/', $senha)) {
        $erros_senha[] = "uma letra maiúscula";
    }
    if (!preg_match('/[\W_]/', $senha)) {
        $erros_senha[] = "um símbolo (ex: @, #, $)";
    }


    if(!empty($erros_senha)) {
        $frase_erro = implode(", ", $erros_senha);
        echo "<p>A senha deve conter: $frase_erro</p>";
    }
    else{
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO teste (emailnumero, senha) 
        VALUES ('$email', '$senhaCriptografada')";
        if ($conn->query($sql) === TRUE) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    }

endif;

if (isset($_POST['l'])):
    header("Location: login.php");
endif;
?>