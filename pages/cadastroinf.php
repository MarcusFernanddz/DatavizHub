<?php
session_start();
include('../connect/conexao.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome       = trim($_POST['nome'] ?? '');
    $nascimento = trim($_POST['nascimento'] ?? '');
    $rua        = trim($_POST['rua'] ?? '');
    $numero     = trim($_POST['numero'] ?? '');
    $bairro     = trim($_POST['bairro'] ?? '');
    $cidade     = trim($_POST['cidade'] ?? '');
    $estado     = trim($_POST['estado'] ?? '');
    $cep        = trim($_POST['cep'] ?? '');

    if (empty($nome) || empty($nascimento) || empty($cidade)) {
        $mensagem = 'Preencha os campos obrigatórios.';
        $tipo = 'erro';
    } else {
        $stmt = $conn->prepare("INSERT INTO funcionarios (nome, nascimento, cep, rua, numero, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nome, $nascimento, $cep, $rua, $numero, $bairro, $cidade, $estado);
        if ($stmt->execute()) {
            $mensagem = "Funcionário \"$nome\" cadastrado com sucesso!";
            $tipo = 'sucesso';
        } else {
            $mensagem = 'Erro ao cadastrar.';
            $tipo = 'erro';
        }
    }
}

// Excluir
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $conn->query("DELETE FROM funcionarios WHERE id = $id");
    header('Location: cadastroinf.php');
    exit();
}

// Listar
$resultado = $conn->query("SELECT * FROM funcionarios ORDER BY nome ASC");
$funcionarios = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Funcionários</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Inter', sans-serif;
    background: #0f0f13;
    color: #ececec;
    min-height: 100vh;
}
nav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 32px; height: 60px;
    background: #17171d; border-bottom: 1px solid rgba(255,255,255,0.07);
}
.nav-brand { font-family: 'Syne', sans-serif; font-size: 17px; color: #ececec; text-decoration: none; }
.nav-brand span { color: #55efc4; }
.btn-back {
    font-size: 13px; color: #6b6b7e; text-decoration: none;
    border: 1px solid rgba(255,255,255,0.07); padding: 6px 14px;
    border-radius: 8px; transition: color 0.15s, border-color 0.15s;
}
.btn-back:hover { color: #55efc4; border-color: #55efc4; }

main { max-width: 560px; margin: 0 auto; padding: 48px 24px 80px; }

h1 { font-family: 'Syne', sans-serif; font-size: 22px; margin-bottom: 6px; }
.sub { font-size: 13px; color: #6b6b7e; margin-bottom: 32px; }

.msg {
    padding: 12px 16px; border-radius: 10px;
    font-size: 13px; font-weight: 500; margin-bottom: 24px;
}
.msg.sucesso { background: rgba(85,239,196,0.1); color: #55efc4; border: 1px solid rgba(85,239,196,0.2); }
.msg.erro    { background: rgba(239,68,68,0.1);  color: #f87171; border: 1px solid rgba(239,68,68,0.2); }

.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 12px; color: #6b6b7e; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
.form-group input {
    width: 100%; padding: 12px 14px;
    background: #1e1e26; border: 1px solid rgba(255,255,255,0.07);
    border-radius: 10px; color: #ececec;
    font-family: 'Inter', sans-serif; font-size: 14px; outline: none;
    transition: border-color 0.2s;
}
.form-group input:focus { border-color: #55efc4; }

.row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.divider {
    font-size: 10px; font-weight: 600; letter-spacing: 1.8px;
    text-transform: uppercase; color: #55efc4;
    margin: 24px 0 16px;
}

button[type="submit"] {
    width: 100%; padding: 13px; margin-top: 8px;
    background: #55efc4; color: #0f0f13;
    border: none; border-radius: 10px;
    font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: opacity 0.2s;
}
button[type="submit"]:hover { opacity: 0.85; }

/* LISTA */
.lista-titulo { font-family: 'Syne', sans-serif; font-size: 16px; margin: 48px 0 16px; }

.emp {
    background: #1e1e26; border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px; padding: 14px 16px;
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 10px;
}
.emp-av {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #55efc4, #00b894);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: #0f0f13;
}
.emp-info { flex: 1; }
.emp-name { font-weight: 500; font-size: 14px; }
.emp-meta { font-size: 12px; color: #6b6b7e; margin-top: 2px; }
.btn-del {
    font-size: 12px; color: rgba(239,68,68,0.6);
    border: 1px solid rgba(239,68,68,0.2); padding: 5px 10px;
    border-radius: 7px; text-decoration: none; transition: all 0.15s;
}
.btn-del:hover { color: #ef4444; border-color: #ef4444; background: rgba(239,68,68,0.08); }

.empty { text-align: center; padding: 32px 0; color: #6b6b7e; font-size: 13px; }
</style>
</head>
<body>

<nav>
  <a href="hub.php" class="nav-brand">meu<span>hub</span></a>
  <a href="hub.php" class="btn-back">← Hub</a>
</nav>

<main>
  <h1>🪪 Funcionários</h1>
  <p class="sub">Cadastre e gerencie a equipe.</p>

  <?php if ($mensagem): ?>
    <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="divider">Dados pessoais</div>

    <div class="form-group">
      <label>Nome completo *</label>
      <input type="text" name="nome" placeholder="João da Silva" required>
    </div>

    <div class="form-group">
      <label>Data de nascimento *</label>
      <input type="date" name="nascimento" required>
    </div>

    <div class="divider">Endereço</div>

    <div class="row">
      <div class="form-group">
        <label>CEP</label>
        <input type="text" name="cep" id="cep" placeholder="00000-000" maxlength="9" oninput="buscarCep(this.value)">
      </div>
      <div class="form-group">
        <label>Número</label>
        <input type="text" name="numero" id="numero" placeholder="123">
      </div>
    </div>

    <div class="form-group">
      <label>Rua</label>
      <input type="text" name="rua" id="rua" placeholder="Rua das Flores">
    </div>

    <div class="row">
      <div class="form-group">
        <label>Cidade *</label>
        <input type="text" name="cidade" id="cidade" placeholder="São Paulo" required>
      </div>
      <div class="form-group">
        <label>Estado</label>
        <input type="text" name="estado" id="estado" placeholder="SP" maxlength="2">
      </div>
    </div>

    <div class="form-group">
      <label>Bairro</label>
      <input type="text" name="bairro" id="bairro" placeholder="Centro">
    </div>

    <button type="submit">Cadastrar funcionário</button>
  </form>

  <!-- LISTA -->
  <div class="lista-titulo">Cadastrados (<?= count($funcionarios) ?>)</div>

  <?php if (empty($funcionarios)): ?>
    <div class="empty">Nenhum funcionário ainda.</div>
  <?php else: ?>
    <?php foreach ($funcionarios as $f): ?>
    <div class="emp">
      <div class="emp-av"><?= strtoupper(mb_substr($f['nome'], 0, 1)) ?></div>
      <div class="emp-info">
        <div class="emp-name"><?= htmlspecialchars($f['nome']) ?></div>
        <div class="emp-meta">
          <?= date('d/m/Y', strtotime($f['nascimento'])) ?>
          <?= $f['cidade'] ? ' · ' . htmlspecialchars($f['cidade']) : '' ?>
          <?= $f['estado'] ? '/' . htmlspecialchars($f['estado']) : '' ?>
        </div>
      </div>
      <a href="?excluir=<?= $f['id'] ?>" class="btn-del"
         onclick="return confirm('Remover <?= htmlspecialchars($f['nome']) ?>?')">Remover</a>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</main>

<script>
async function buscarCep(val) {
    val = val.replace(/\D/g, '');
    if (val.length !== 8) return;
    try {
        const r = await fetch(`https://viacep.com.br/ws/${val}/json/`);
        const d = await r.json();
        if (!d.erro) {
            document.getElementById('rua').value    = d.logradouro || '';
            document.getElementById('bairro').value = d.bairro     || '';
            document.getElementById('cidade').value = d.localidade || '';
            document.getElementById('estado').value = d.uf         || '';
            document.getElementById('numero').focus();
        }
    } catch(e) {}
}
</script>
</body>
</html>