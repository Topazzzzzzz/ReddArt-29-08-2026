<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ReddArt | Galeria </title>

    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="css/Galeria.css">

    <!-- Google Fonts: Archivo Black e Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prociono&display=swap" rel="stylesheet">
</head>

<body>

    <!-- BANNER INDEPENDENTE DO TIPO -->
    <div class="banner">
        <img src="images/Kuro.webp" alt="Banner Do Perfil">
    </div>

    <!--Linha Normal do HTML -->
    <div class="linha">
        <div class="foto">
            <img src="images/Hinako.jpg" alt="Foto do Perfil">
        </div>
        <div class="links">
            <a href="Perfil.php"><b>Perfil</b></a>
            <a href="publicacoesPerfil.php"><b> Galeria </b></a>
            <a href=""><b>Coleções</b></a>
            <a href=""><b>Redes Sociais</b></a>
        </div>
    </div>

    <!-- LINHA DO MOBILE -->
    <div class="linha-mobile">
        <div class="foto-mobile">
            <img src="images/Hinako.jpg" alt="">
        </div>
    </div>


    <!-- COVER DESKTOP -->
    <div class="cover"></div>

    <!-- COVER MOBILE (NOME) -->
    <div class="cover-mobile"></div>


    <!-- CORPO DESKTOP -->
    <div class="corpo">
        <a href="">
            <div class="publicacao">
                <img src="images/FE5.jpg" alt="">
                <div class="overlay"><span> Foreground Eclipse </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/TW3.jpg" alt="">
                <div class="overlay"><span> Takamachi Walk </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/Yoshiha.jpg" alt="">
                <div class="overlay"><span> Yoshiha </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/UC1.jpg" alt="">
                <div class="overlay"><span> Undead Corporation </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/IMPRISONEDXII.png" alt="">
                <div class="overlay"><span> Ave Mujica </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/Utakotoba.png" alt="">
                <div class="overlay"><span> MyGO!!!!! </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/Fire Bird.jpg" alt="">
                <div class="overlay"><span> Roselia </span></div>
            </div>
        </a>
        <a href="">
            <div class="publicacao">
                <img src="images/INVADE.jpg" alt="">
                <div class="overlay"><span> Raise A Suilen </span></div>
            </div>
        </a>
    </div>

    <!--Footer do Desktop -->
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="https:reddart.hubsapiens.com.br">
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