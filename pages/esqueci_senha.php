<?php
require '../vendor/autoload.php';
include('../connect/conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Por favor, informe um e-mail válido.';
        $tipo = 'erro';
    } else {
        $stmt = $conn->prepare("SELECT idconta FROM teste WHERE emailnumero = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $token  = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $upd = $conn->prepare("UPDATE teste SET reset_token = ?, reset_expira = ? WHERE emailnumero = ?");
            $upd->bind_param("sss", $token, $expira, $email);
            $upd->execute();

            $link = "http://localhost/hugofofolindo/pages/recsenha.php?token=$token";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username = 'testeenvioemailphp5@gmail.com';
                $mail->Password = 'mjyb lwxk fvqr spta';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('testeenvioemailphp5@gmail.com', 'sistema');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Recuperação de senha';
                $mail->Body    = "
                    <div style='font-family:sans-serif;max-width:500px;margin:auto;padding:30px;'>
                        <h2 style='color:#1a1a2e;'>Redefinir sua senha</h2>
                        <p>Clique no botão abaixo para criar uma nova senha. O link expira em <strong>1 hora</strong>.</p>
                        <a href='$link'
                           style='display:inline-block;margin:20px 0;padding:12px 28px;
                                  background:#e94560;color:#fff;text-decoration:none;
                                  border-radius:6px;font-weight:bold;'>
                            Redefinir senha
                        </a>
                        <p style='color:#888;font-size:13px;'>Se você não solicitou isso, ignore este e-mail.</p>
                    </div>
                ";

                $mail->send();
                $mensagem = 'E-mail enviado! Verifique sua caixa de entrada.';
                $tipo = 'sucesso';
            } catch (Exception $e) {
                $mensagem = 'Erro ao enviar e-mail: ' . $mail->ErrorInfo;
                $tipo = 'erro';
            }
        } else {
            // Mesma mensagem por segurança
            $mensagem = 'E-mail enviado! Verifique sua caixa de entrada.';
            $tipo = 'sucesso';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sora', sans-serif;
            background: #0f0f1a;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(233,69,96,0.15) 0%, transparent 70%);
            top: -100px; right: -100px; border-radius: 50%; pointer-events: none;
        }
        body::after {
            content: ''; position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(72,52,212,0.12) 0%, transparent 70%);
            bottom: -80px; left: -80px; border-radius: 50%; pointer-events: none;
        }
        .card {
            background: #1a1a2e; border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px; padding: 48px 40px;
            width: 100%; max-width: 420px;
            position: relative; z-index: 1;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .icon {
            width: 56px; height: 56px; background: rgba(233,69,96,0.15);
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
            font-size: 26px; margin-bottom: 24px;
        }
        h1 { color: #f0f0f0; font-size: 1.6rem; font-weight: 700; margin-bottom: 8px; }
        p.sub { color: #888; font-size: 0.9rem; line-height: 1.6; margin-bottom: 32px; }
        label {
            display: block; color: #aaa; font-size: 0.8rem; font-weight: 600;
            letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px;
        }
        input[type="email"] {
            width: 100%; padding: 14px 16px;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; color: #f0f0f0;
            font-family: 'Sora', sans-serif; font-size: 0.95rem;
            outline: none; transition: border-color 0.2s, background 0.2s; margin-bottom: 20px;
        }
        input[type="email"]:focus { border-color: #e94560; background: rgba(233,69,96,0.07); }
        button {
            width: 100%; padding: 14px; background: #e94560; color: #fff;
            border: none; border-radius: 10px; font-family: 'Sora', sans-serif;
            font-size: 1rem; font-weight: 600; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        button:hover  { background: #c73652; }
        button:active { transform: scale(0.98); }
        .msg {
            padding: 12px 16px; border-radius: 10px;
            font-size: 0.88rem; margin-bottom: 20px; font-weight: 500;
        }
        .msg.sucesso { background: rgba(34,197,94,0.12); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .msg.erro    { background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .voltar {
            display: block; text-align: center; margin-top: 24px;
            color: #666; font-size: 0.85rem; text-decoration: none; transition: color 0.2s;
        }
        .voltar:hover { color: #e94560; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">🔑</div>
    <h1>Esqueceu a senha?</h1>
    <p class="sub">Informe seu e-mail e enviaremos um link para redefinir sua senha.</p>

    <?php if ($mensagem): ?>
        <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Seu e-mail</label>
        <input type="email" id="email" name="email"
               placeholder="voce@exemplo.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               required>
        <button type="submit">Enviar link de recuperação</button>
    </form>

    <a class="voltar" href="login.php">← Voltar para o login</a>
</div>
</body>
</html>