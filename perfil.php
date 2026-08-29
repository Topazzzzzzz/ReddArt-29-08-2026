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

$nomeExibicao = htmlspecialchars($usuario['userNome'] ?? '');
$descricao    = htmlspecialchars($usuario['userDescricao'] ?? '');
$fotoPerfil   = !empty($usuario['userFoto'])   ? htmlspecialchars($usuario['userFoto'])   : 'img/default-avatar.png';
$bannerPerfil = !empty($usuario['userBanner']) ? htmlspecialchars($usuario['userBanner']) : 'img/default-banner.jpg';

// Busca as 4 publicações mais recentes FEITAS PELO USUÁRIO LOGADO
// (tblPublicacoes é preenchida em processar_upload.php).
// O array $publicacoes resultante é usado no layout desktop, no layout
// 1025px–1440px e no layout mobile, para não montar a mesma query
// nem duplicar a mesma lista três vezes no HTML.
$publicacoes = [];
$sqlPublicacoes = "SELECT * FROM tblPublicacoes WHERE idUsuario = $idUsuario ORDER BY pubHora DESC LIMIT 4";
$resPublicacoes = mysqli_query($conn, $sqlPublicacoes);

if ($resPublicacoes) {
    while ($linha = mysqli_fetch_assoc($resPublicacoes)) {
        $publicacoes[] = [
            'img'    => $linha['pubLink'],
            'titulo' => $linha['pubLegenda'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ReddArt | Perfil </title>

    <link rel="stylesheet" href="css/perfil.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prociono&display=swap" rel="stylesheet">
</head>

<body>

    <!-- BANNER INDEPENDENTE DO TIPO -->
    <div class="banner">
        <img src="<?php echo $bannerPerfil; ?>" alt="Banner Do Perfil">
    </div>

    <!--Linha Normal do HTML -->
    <div class="linha">
        <div class="foto">
            <img src="<?php echo $fotoPerfil; ?>" alt="Foto do Perfil">
        </div>
        <div class="links">
            <a href="perfil.php"><b>Perfil</b></a>
            <a href="Galeria.php"><b> Galeria </b></a>
            <a href="#"><b>Coleções</b></a>
            <a href="#"><b>Redes Sociais</b></a>
        </div>
    </div>

    <!-- LINHA DO MOBILE -->
    <div class="linha-mobile">
        <div class="foto-mobile">
            <img src="<?php echo $fotoPerfil; ?>" alt="">
        </div>
    </div>

    <!-- Informações do usuário e botão de edição -->
    <div class="cover">
        <div class="box">
            <div class="nome">
                <h1> <?php echo $nomeExibicao; ?> </h1>
            </div>
            <div class="info">
                <span> <b>📍Brasil </b></span>
                <span> <b> — </b> </span>
                <span> <b> Entrou em 2026 </b> </span>
            </div>
        </div>
        <a href="editarPerfil.php"> Editar Perfil </a>
    </div>

    <!-- COVER MOBILE (NOME) -->
    <div class="cover-mobile"></div>
    <div class="nome-mobile">
        <h1> <?php echo $nomeExibicao; ?> </h1>
    </div>

    <!-- BARRA DE LINKS MOBILE -->
    <div class="links-mobile">
        <a href="perfil.php"> Perfil </a>
        <a href="Galeria.php"> Galeria </a>
        <a href="#">Coleções</a>
        <a href="#">Redes Sociais</a>
    </div>

    <!-- INFORMAÇÕES DO USUARIO (Mobile/Tablet) -->
    <div class="info-mobile">
        <div class="info">
            <span> 📍Brasil </span>
            <span> Entrou em 2026 </span>
        </div>
    </div>

    <!-- BOTÃO EDITAR (Mobile) -->
    <a href="editarPerfil.php" class="editar-mobile">
        <h1> Editar Perfil </h1>
    </a>

    <!-- Publicações Desktop -->
    <div class="corpo">
        <div class="container">
            <div class="titulo">
                <label for=""> PUBLICAÇÕES RECENTES </label>
            </div>
            <?php if (!empty($publicacoes)): ?>
                <?php foreach ($publicacoes as $pub): ?>
                    <div class="publicacoes">
                        <a href="index.php">
                            <img src="<?php echo htmlspecialchars($pub['img']); ?>" alt="<?php echo htmlspecialchars($pub['titulo']); ?>">
                            <div class="overlay"><span><?php echo htmlspecialchars($pub['titulo']); ?></span></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sem-publicacoes">
                    <p>Você ainda não publicou nenhuma arte.</p>
                    <a href="adicionar.php">Publicar a primeira arte</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="descricao">
            <div class="titulo">
                <label for=""> SOBRE MIM </label>
            </div>
            <div class="texto">
                <textarea readonly><?php echo $descricao; ?></textarea>
            </div>
        </div>
    </div>

    <!-- PUBLICAÇÕES MOBILE -->
    <div class="corpo-mobile">
        <div class="titulo-mobile">
            <h1> PUBLICAÇÕES RECENTES </h1>
        </div>
        <?php if (!empty($publicacoes)): ?>
            <?php foreach ($publicacoes as $pub): ?>
                <a href="">
                    <div class="publicacao-mobile">
                        <img src="<?php echo htmlspecialchars($pub['img']); ?>" alt="<?php echo htmlspecialchars($pub['titulo']); ?>">
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sem-publicacoes">
                <p>Você ainda não publicou nenhuma arte.</p>
                <a href="adicionar.php">Publicar a primeira arte</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- DESCRIÇÃO MOBILE -->
    <div class="descricao-mobile">
        <div class="title-mobile">
            <h1> SOBRE MIM </h1>
        </div>
        <div class="texto-mobile">
            <textarea readonly><?php echo $descricao; ?></textarea>
        </div>
    </div>


    <div class="trace">
        <div class="photo">
            <img src="<?php echo $fotoPerfil; ?>" alt="Foto do Perfil">
        </div>
        <div class="linka">
            <a href="perfil.php"> <b> PERFIL </b> </a>
            <a href="Galeria.php"> <b> GALERIA </b> </a>
            <a href="#"> <b> COLEÇÕES </b> </a>
            <a href="#"> <b> REDES SOCIAIS </b> </a>
        </div>
    </div>

    <!-- NOME/INFO/BOTÃO (versão 1025px-1440px) -->
    <div class="kaigaku">
        <div class="namae">
            <h1> <?php echo $nomeExibicao; ?> </h1>
        </div>
        <div class="information">
            <span> 📍Brasil </span>
            <span> Entrou em 2026 </span>
        </div>

        <a href="editarPerfil.php"> <b> Editar Perfil </b> </a>
    </div>

    <!-- PUBLICAÇÕES (versão 1025px-1440px) -->
    <div class="publicacaozinha">
        <div class="Titulo">
            <h1> PUBLICAÇÕES RECENTES </h1>
        </div>
        <div class="post">
            <?php if (!empty($publicacoes)): ?>
                <?php foreach ($publicacoes as $pub): ?>
                    <div class="post-item">
                        <a href="">
                            <img src="<?php echo htmlspecialchars($pub['img']); ?>" alt="<?php echo htmlspecialchars($pub['titulo']); ?>">
                            <div class="post-overlay"><span><?php echo htmlspecialchars($pub['titulo']); ?></span></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sem-publicacoes">
                    <p>Você ainda não publicou nenhuma arte.</p>
                    <a href="adicionar.php">Publicar a primeira arte</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!--Footer do Desktop -->
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="index.php">
                    <h2 class="footer-logo">ReddArt</h2>
                </a>
            </div>

            <div class="footer-col">
                <h3>Navegação</h3>
                <a href="Perfil.php">Perfil</a>
                <a href="publicacoesPerfil.php">Publicações</a>
                <a href="perfil2.php">Coleções</a>
            </div>

            <div class="footer-col">
                <h3>Redes Sociais</h3>
                <a href="">Instagram</a>
                <a href="">Twitter / X</a>
                <a href="">Pixiv</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 ReddArt. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>

</html>