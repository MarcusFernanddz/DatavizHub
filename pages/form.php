<?php 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <style>
        /* Um pouquinho de CSS para não precisar de tantos <br> */
        .campo { margin-bottom: 15px; }
        label { font-weight: bold; }
    </style>
</head>
<body>

    <h1>Formulário</h1>

    <form action="#" method="POST">
        <div class="campo">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" size="25" autocomplete="off" required>
        </div>

        <div class="campo">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" size="25" required>
        </div>

        <div class="campo">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" size="25" required>
        </div>

        <div class="campo">
            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade" min="0" max="120" required>
        </div>

        <div class="campo">
            <label>Sexo:</label>
            <label><input type="radio" name="genero" value="masculino"> Homem</label>
            <label><input type="radio" name="genero" value="feminino"> Mulher</label>
        </div>
        
        <div class="campo">
            <label>Interesses:</label>    
            <input type="checkbox" id="ti" name="interesses[]" value="TI"> <label for="ti">TI</label>
            <input type="checkbox" id="esporte" name="interesses[]" value="Esporte"> <label for="esporte">Esporte</label>
        </div>
        
        <div class="campo">
            <label for="cidade">Cidade:</label> 
            <select name="cidade" id="cidade">        
                <option value="Morrinhos">Morrinhos</option>
                <option value="Goiânia">Goiânia</option>    
            </select>
        </div>

        <button type="submit">Enviar Dados</button>
    </form>
</body>
</html>