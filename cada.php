<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include "setup/conexao.php";

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['userNome'] ?? '');
    $nick = trim($_POST['userNick'] ?? '');
    $email = trim($_POST['userEmail'] ?? '');
    $senha = $_POST['userSenha'] ?? '';
    $senhaConfirm = $_POST['userSenhaConfirm'] ?? '';
    $descricao = trim($_POST['userDescricao'] ?? '');
    $idCargoPadrao = 1; 

    // Bloqueio de termos restritos no Nick
    $nickMinusculo = strtolower($nick);
    if (str_contains($nickMinusculo, 'logicfox') || str_contains($nickMinusculo, 'spinelli')) {
        $erro = "Alerta: O nickname possui termos restritos.";
    }

    // Validação de Confirmação de Senha
    if (empty($erro) && $senha !== $senhaConfirm) {
        $erro = "As senhas não coincidem! Por favor, digite novamente.";
    }

    // Upload de Foto de Perfil
    $caminhoFoto = "img/default-avatar.png"; 

    if (empty($erro) && isset($_FILES['userFoto']) && $_FILES['userFoto']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['userFoto']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $extensoesPermitidas)) {
            $novoNomeFoto = md5(uniqid(time())) . "." . $extensao;
            $diretorioDestino = "uploads/perfil/";

            if (!is_dir($diretorioDestino)) {
                mkdir($diretorioDestino, 0777, true);
            }

            $caminhoFoto = $diretorioDestino . $novoNomeFoto;
            move_uploaded_file($_FILES['userFoto']['tmp_name'], $caminhoFoto);
        } else {
            $erro = "Formato de imagem inválido! Use JPG, PNG ou WEBP.";
        }
    }

    if (empty($erro)) {
        if (!empty($nome) && !empty($nick) && !empty($email) && !empty($senha)) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO tblUsuario (userNome, userNick, userEmail, userSenha, userDescricao, userFoto, idCargo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nome, $nick, $email, $senhaHash, $descricao, $caminhoFoto, $idCargoPadrao);

            if ($stmt->execute()) {
                $novoIdUsuario = $stmt->insert_id;
                $stmt->close();

                $_SESSION['idUsuario'] = $novoIdUsuario;
                $_SESSION['userNome'] = $nome;

                $conn->close();

                header("Location: index.php");
                exit();
            } else {
                if ($conn->errno === 1062) {
                    $erro = "E-mail ou Nick já cadastrados!";
                } else {
                    $erro = "Erro ao cadastrar: " . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            }
        } else {
            $erro = "Preencha todos os campos obrigatórios!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - ReddArt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #000000, #0c0c11, #111113);
            color: #ffffff;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow-x: hidden;
        }

        .site-header {
            width: 100%;
            text-align: center;
            margin-bottom: 24px;
        }

        .site-title {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 3px;
            background: linear-gradient(135deg, #4f46e5, #000146);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
        }

        .cadastro-container {
            background-color: #121218;
            border: 1px solid #1f1f2e;
            padding: 32px;
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-row {
            display: flex;
            gap: 12px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        label {
            color: #8a8a9e;
            font-size: 13px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 12px 14px;
            background-color: #0c0c11;
            color: #ffffff;
            border-radius: 10px;
            border: 1px solid #1f1f2e;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        input:focus, textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        /* Área Personalizada para Upload de Foto de Perfil */
        .file-drop-area {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 2px dashed #1f1f2e;
            border-radius: 12px;
            background-color: #0c0c11;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }

        .file-drop-area:hover {
            border-color: #4f46e5;
            background-color: rgba(79, 70, 229, 0.05);
        }

        .file-drop-area i {
            font-size: 24px;
            color: #4f46e5;
            margin-bottom: 6px;
        }

        .file-drop-area span {
            font-size: 12px;
            color: #8a8a9e;
        }

        .file-drop-area input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .btn-cadastro {
            width: 100%;
            margin-top: 8px;
            padding: 12px;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .btn-cadastro:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .link-login {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #8a8a9e;
            text-decoration: none;
            font-size: 13px;
        }

        .link-login strong {
            color: #4f46e5;
        }

        .erro {
            color: #f87171;
            text-align: center;
            font-size: 13px;
            background: rgba(248, 113, 113, 0.1);
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(248, 113, 113, 0.2);
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <header class="site-header">
        <h1 class="site-title">ReddArt</h1>
    </header>

    <div class="cadastro-container">

        <?php if (!empty($erro)): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
            
            <div class="form-group">
                <label for="userNome">Nome Completo:*</label>
                <input type="text" id="userNome" name="userNome" maxlength="75" required placeholder="Ex: João Silva">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="userNick">Nickname:*</label>
                    <input type="text" id="userNick" name="userNick" maxlength="50" required placeholder="@joao">
                </div>

                <div class="form-group">
                    <label for="userEmail">E-mail:*</label>
                    <input type="email" id="userEmail" name="userEmail" maxlength="75" required placeholder="seu@email.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="userSenha">Senha:*</label>
                    <input type="password" id="userSenha" name="userSenha" maxlength="20" required placeholder="Sua senha">
                </div>

                <div class="form-group">
                    <label for="userSenhaConfirm">Confirmar Senha:*</label>
                    <input type="password" id="userSenhaConfirm" name="userSenhaConfirm" maxlength="20" required placeholder="Repita a senha">
                </div>
            </div>

            <div class="form-group">
                <label>Foto de Perfil:</label>
                <div class="file-drop-area">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span id="file-label">Escolha ou arraste sua foto</span>
                    <input type="file" id="userFoto" name="userFoto" accept="image/*" onchange="updateFileName(this)">
                </div>
            </div>

            <div class="form-group">
                <label for="userDescricao">Bio / Descrição:</label>
                <textarea id="userDescricao" name="userDescricao" maxlength="200" placeholder="Conte um pouco sobre você..."></textarea>
            </div>

            <button type="submit" class="btn-cadastro">Criar Conta</button>

            <a href="login.php" class="link-login">
                Já tem uma conta? <strong>Faça Login</strong>
            </a>
        </form>
    </div>

    <script>
        function updateFileName(input) {
            const label = document.getElementById('file-label');
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
            } else {
                label.textContent = 'Escolha ou arraste sua foto';
            }
        }
    </script>
</body>

</html>
