<?php
session_start();
include "setup/conexao.php";

// Se o usuário não estiver logado, manda ele para a tela de login
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

// Busca os dados do usuário
$sqlUsuario = "SELECT * FROM tblUsuario WHERE idUsuario = $idUsuario";
$resUsuario = mysqli_query($conn, $sqlUsuario);
$usuario = mysqli_fetch_assoc($resUsuario);

// Foto e banner atuais
$caminhoFotoAtual = $usuario['userFoto'] ?? '';
$caminhoBannerAtual = $usuario['userBanner'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - ReddArt</title>
    <link rel="stylesheet" href="css/editarPerfil.css">
</head>
<body>
    <div class="perfil-container">
        <h2>Editar Meu Perfil</h2>
        <form action="atualizar_perfil.php" method="POST" enctype="multipart/form-data">
            <label for="userNome">Apelido / Nome de Exibição:</label>
            <input type="text" id="userNome" name="userNome" value="<?php echo htmlspecialchars($usuario['userNome'] ?? ''); ?>" required>

            <label for="userDescricao">Biografia / Descrição (Opcional para o Banner):</label>
            <textarea id="userDescricao" name="userDescricao"><?php echo htmlspecialchars($usuario['userDescricao'] ?? ''); ?></textarea>

            <label for="userFoto">Foto de Perfil Atual:</label>
            <?php if (!empty($caminhoFotoAtual)): ?>
                <div><img src="<?php echo htmlspecialchars($caminhoFotoAtual); ?>" class="preview-img" alt="Foto atual"></div>
            <?php endif; ?>
            <input type="file" id="userFoto" name="userFoto" accept="image/*">

            <label for="userBanner">Banner Atual:</label>
            <?php if (!empty($caminhoBannerAtual)): ?>
                <div><img src="<?php echo htmlspecialchars($caminhoBannerAtual); ?>" class="preview-banner" alt="Banner atual"></div>
            <?php endif; ?>
            <input type="file" id="userBanner" name="userBanner" accept="image/*">

            <button type="submit" class="btn-salvar">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>