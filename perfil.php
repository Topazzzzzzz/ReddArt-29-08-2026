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
        <div class="Nome">
            <h1><?php echo $nomeExibicao; ?></h1>
        </div>
        <div class="info">
            <span> 📍Brasil </span>
            <span> Entrou em 2026 </span>
        </div>
        <a href="editarPerfil.php">
            <div class="Editar">Editar Perfil</div>
        </a>
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
            <div class="publicacoes">
                <a href="">
                    <img src="img/FE1.jpg" alt="">
                    <div class="overlay"><span> Foreground Eclipse </span></div>
                </a>
            </div>
            <div class="publicacoes">
                <a href="">
                    <img src="img/TW2.jpg" alt="">
                    <div class="overlay"><span> Takamachi Walk </span></div>
                </a>
            </div>
            <div class="publicacoes">
                <a href="">
                    <img src="img/UC1.jpg" alt="">
                    <div class="overlay"><span> Undead Corporation </span></div>
                </a>
            </div>
            <div class="publicacoes">
                <a href="">
                    <img src="img/TP1.jpg" alt="">
                    <div class="overlay"><span> Touhou Project </span></div>
                </a>
            </div>
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
        <a href="">
            <div class="publicacao-mobile">
                <img src="img/FE1.jpg" alt="">
            </div>
        </a>
        <a href="">
            <div class="publicacao-mobile">
                <img src="img/TW1.jpg" alt="">
            </div>
        </a>
        <a href="">
            <div class="publicacao-mobile">
                <img src="img/UC1.jpg" alt="">
            </div>
        </a>
        <a href="">
            <div class="publicacao-mobile">
                <img src="img/TP1.jpg" alt="">
            </div>
        </a>
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