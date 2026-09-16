<?php
session_start();
include "setup/conexao.php";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ipOrigem = $_SERVER['REMOTE_ADDR'];

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipOrigem = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipOrigem = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    $navegador = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido', 0, 255);

    $email = trim($_POST['userEmail'] ?? '');
    $senha = $_POST['userSenha'] ?? '';

    if (!empty($email) && !empty($senha)) {

        $stmt = $conn->prepare("SELECT idUsuario, userNome, userSenha, idCargo FROM tblUsuario WHERE userEmail = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario['userSenha'])) {

                $logStmt = $conn->prepare("INSERT INTO tblLogs (idUsuario, logEmail, logStatus, logIpOrigem, logNavegador, logdataHora) VALUES (?, ?, 'Sucesso', ?, ?, NOW())");
                $logStmt->bind_param("isss", $usuario['idUsuario'], $email, $ipOrigem, $navegador);
                $logStmt->execute();
                $logStmt->close();

                $_SESSION['usuario_id'] = $usuario['idUsuario'];
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['userNome'] = $usuario['userNome'];
                $_SESSION['usuario_cargo'] = $usuario['idCargo'];

                header("Location: index.php");
                exit;
            } else {

                $erro = "Senha incorreta!";

                // Grava o Log de Senha Incorreta (incluindo o Navegador)
                $logStmt = $conn->prepare("INSERT INTO tblLogs (idUsuario, logEmail, logStatus, logIpOrigem, logNavegador, logdataHora) VALUES (?, ?, 'Falha', ?, ?, NOW())");
                $logStmt->bind_param("isss", $usuario['idUsuario'], $email, $ipOrigem, $navegador);
                $logStmt->execute();
                $logStmt->close();
            }
        } else {

            $erro = "E-mail não encontrado!";

            $idNulo = null;

            $logStmt = $conn->prepare("INSERT INTO tblLogs (idUsuario, logEmail, logStatus, logIpOrigem, logNavegador, logdataHora) VALUES (?, ?, 'Falha', ?, ?, NOW())");
            $logStmt->bind_param("isss", $idNulo, $email, $ipOrigem, $navegador);
            $logStmt->execute();
            $logStmt->close();
        }

        $stmt->close();
    } else {

        $erro = "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REDDART - Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <!-- Coluna Esquerda -->
    <div class="coluna">
        <div class="carrossel">
            <div class="track track-up">
                <img src="fotosLogin/FireBird.png" alt="Art">
                <img src="fotosLogin/Legendary.png" alt="Art">
                <img src="fotosLogin/FloralHeaven.png" alt="Art">
                <img src="fotosLogin/FurImmer.webp" alt="Art">
                <img src="fotosLogin/OperaOfTheWasteland.png" alt="Art">
                <img src="fotosLogin/LehreRose.png" alt="Art">
            </div>
            <div class="track track-down">
                <img src="fotosLogin/Era.jpg" alt="Art">
                <img src="fotosLogin/ExposeBurnout.png" alt="Art">
                <img src="fotosLogin/FightAttitude.png" alt="Art">
                <img src="fotosLogin/HowlyAmbition.jpg" alt="Art">
                <img src="fotosLogin/WhatAnExplosion.jpg" alt="Art">
                <img src="fotosLogin/Savage.jpg" alt="Art">
            </div>
        </div>
    </div>

    <!-- Centro -->
    <div class="centro">
        <h1 class="logo">REDDART</h1>
        <div class="form-card">
            <form class="form" method="POST" action="">
                <div class="input-group">
                    <label>E-mail:</label>
                    <input type="email" name="userEmail" placeholder="seuemail@exemplo.com" required>
                </div>
                <div class="input-group">
                    <label>Senha:</label>
                    <input type="password" name="userSenha" placeholder="••••••••••••" required>
                </div>
                <button type="submit" class="btn-primary">Entrar</button>
                <?php if ($erro): ?>
                    <p style="color:#ff5c5c; font-size:0.85rem; margin-top:10px;"><?php echo htmlspecialchars($erro); ?></p>
                <?php endif; ?>
                <p class="signup-text">Não tem uma conta? <a href="cada.php">Faça Cadastro</a></p>
            </form>
        </div>
    </div>

    <!-- Coluna Direita -->
    <div class="coluna">
        <div class="carrossel">
            <div class="track track-down">
                <img src="fotosLogin/STheWay.png" alt="Art">
                <img src="fotosLogin/KiLLKiSS.png" alt="Art">
                <img src="fotosLogin/KamiSamaBaka.png" alt="Art">
                <img src="fotosLogin/ImPrisonedXII.png" alt="Art">
                <img src="fotosLogin/CrucifixX.png" alt="Art">
                <img src="fotosLogin/ChoirSChoir.png" alt="Art">
            </div>
            <div class="track track-up">
                <img src="fotosLogin/FE1.png" alt="Art">
                <img src="fotosLogin/FE2.png" alt="Art">
                <img src="fotosLogin/FE3.png" alt="Art">
                <img src="fotosLogin/FE4.jpg" alt="Art">
                <img src="fotosLogin/FE5.jpg" alt="Art">
                <img src="fotosLogin/TP.jpg" alt="Art">
            </div>
        </div>
    </div>

</body>

</html>