<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['emailnumero'];
$hora = (int)date('H');
$saudacao = $hora < 12 ? 'Bom dia' : ($hora < 18 ? 'Boa tarde' : 'Boa noite');

$blocos = [
    ['titulo' => 'Clientes',      'desc' => 'Gerencie sua base de clientes', 'icone' => '👥', 'link' => 'clientes.php',     'cor' => '#6c63ff'],
    ['titulo' => 'Relatórios',   'desc' => 'Visualize dados e métricas',    'icone' => '📊', 'link' => 'relatorios.php',   'cor' => '#00b894'],
    ['titulo' => 'Produtos',     'desc' => 'Cadastros e estoque',           'icone' => '📦', 'link' => 'produtos.php',     'cor' => '#e17055'],
    ['titulo' => 'Financeiro',   'desc' => 'Entradas, saídas e saldos',     'icone' => '💰', 'link' => 'financeiro.php',   'cor' => '#fdcb6e'],
    ['titulo' => 'Configurações','desc' => 'Ajustes do sistema',            'icone' => '⚙️', 'link' => 'config.php',       'cor' => '#74b9ff'],
    ['titulo' => 'Suporte',      'desc' => 'Abrir chamados e ajuda',        'icone' => '🎧', 'link' => 'suporte.php',      'cor' => '#fd79a8'],
    ['titulo' => 'Funcionários', 'desc' => 'Cadastro e gestão de equipe',   'icone' => '🪪', 'link' => 'cadastroinf.php',  'cor' => '#55efc4'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hub — Painel Principal</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg: #0f0f13;
  --surface: #17171d;
  --card: #1e1e26;
  --border: rgba(255,255,255,0.07);
  --text: #ececec;
  --muted: #6b6b7e;
  --faint: #2e2e3a;
}
body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; }
nav { display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 60px; background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 50; }
.nav-brand { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 17px; letter-spacing: -0.3px; color: var(--text); text-decoration: none; }
.nav-brand span { color: #7c6ff7; }
.nav-right { display: flex; align-items: center; gap: 16px; }
.nav-user { display: flex; align-items: center; gap: 10px; font-size: 14px; color: var(--muted); }
.avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #7c6ff7, #a89ff7); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; color: #fff; flex-shrink: 0; }
.btn-logout { display: inline-flex; align-items: center; gap: 6px; background: transparent; border: 1px solid var(--border); color: var(--muted); padding: 6px 14px; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; text-decoration: none; cursor: pointer; transition: all 0.15s; }
.btn-logout:hover { border-color: #e05252; color: #e05252; background: rgba(224,82,82,0.08); }
.btn-logout svg { width: 15px; height: 15px; flex-shrink: 0; }
main { flex: 1; max-width: 900px; width: 100%; margin: 0 auto; padding: 60px 24px 80px; }
.welcome { margin-bottom: 56px; }
.welcome-tag { display: inline-block; font-size: 11px; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; color: #7c6ff7; background: rgba(124,111,247,0.1); padding: 4px 12px; border-radius: 999px; margin-bottom: 20px; }
.welcome h1 { font-family: 'Syne', sans-serif; font-size: clamp(28px, 5vw, 44px); font-weight: 700; line-height: 1.15; letter-spacing: -1px; color: var(--text); margin-bottom: 12px; }
.welcome h1 em { font-style: normal; background: linear-gradient(90deg, #a89ff7, #7c6ff7 60%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.welcome p { font-size: 15px; color: var(--muted); line-height: 1.6; max-width: 480px; }
.grid-title { font-size: 11px; font-weight: 500; letter-spacing: 1.8px; text-transform: uppercase; color: var(--muted); margin-bottom: 20px; }
.blocks-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 14px; }
.block { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 24px 22px; text-decoration: none; color: var(--text); display: flex; flex-direction: column; gap: 14px; transition: transform 0.18s, border-color 0.18s, background 0.18s; position: relative; overflow: hidden; }
.block::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--block-color, #7c6ff7); opacity: 0; transition: opacity 0.18s; }
.block:hover { transform: translateY(-3px); border-color: rgba(255,255,255,0.14); background: #22222c; }
.block:hover::after { opacity: 1; }
.block-icon { font-size: 28px; line-height: 1; width: 48px; height: 48px; border-radius: 12px; background: var(--faint); display: flex; align-items: center; justify-content: center; transition: background 0.18s; }
.block:hover .block-icon { background: color-mix(in srgb, var(--block-color) 15%, transparent); }
.block-title { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 600; margin-bottom: 4px; }
.block-desc { font-size: 13px; color: var(--muted); line-height: 1.4; }
.block-arrow { position: absolute; top: 22px; right: 22px; font-size: 18px; color: var(--muted); opacity: 0; transform: translateX(-4px); transition: opacity 0.18s, transform 0.18s; }
.block:hover .block-arrow { opacity: 1; transform: translateX(0); }
@media (max-width: 520px) { nav { padding: 0 18px; } main { padding: 36px 18px 60px; } .blocks-grid { grid-template-columns: 1fr 1fr; gap: 10px; } .block { padding: 18px 16px; } }
</style>
</head>
<body>

<nav>
  <a href="hub.php" class="nav-brand">meu<span>hub</span></a>
  <div class="nav-right">
    <div class="nav-user">
      <div class="avatar"><?= strtoupper(mb_substr($usuario, 0, 1)) ?></div>
      <span><?= htmlspecialchars($usuario) ?></span>
    </div>
    <a href="hub.php?logout=1" class="btn-logout" onclick="return confirm('Deseja sair da conta?')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Sair
    </a>
  </div>
</nav>

<main>
  <div class="welcome">
    <div class="welcome-tag">Painel Principal</div>
    <h1><?= $saudacao ?>, <em><?= htmlspecialchars($usuario) ?></em> 👋</h1>
    <p>Selecione um módulo abaixo para começar. Todos os seus recursos estão organizados aqui.</p>
  </div>

  <div class="grid-title">Módulos disponíveis</div>
  <div class="blocks-grid">
    <?php foreach ($blocos as $b): ?>
    <a class="block" href="<?= $b['link'] ?>" style="--block-color: <?= $b['cor'] ?>">
      <div class="block-icon"><?= $b['icone'] ?></div>
      <div class="block-body">
        <div class="block-title"><?= htmlspecialchars($b['titulo']) ?></div>
        <div class="block-desc"><?= htmlspecialchars($b['desc']) ?></div>
      </div>
      <span class="block-arrow">→</span>
    </a>
    <?php endforeach; ?>
  </div>
</main>

</body>
</html>