<?php
session_start();
include "setup/conexao.php";

if (!isset($_SESSION['idUsuario'])) {
    echo "erro";
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$idPublicacao = intval($_POST['idPublicacao'] ?? 0);

if ($idPublicacao <= 0) {
    echo "erro";
    exit;
}

// Adiciona 1 curtida
$sql = "UPDATE tblPublicacoes 
        SET pubCurtida = COALESCE(pubCurtida, 0) + 1
        WHERE idPublicacao = $idPublicacao";

if (mysqli_query($conn, $sql)) {

    // Busca a quantidade atualizada
    $sqlBusca = "SELECT pubCurtida 
                 FROM tblPublicacoes 
                 WHERE idPublicacao = $idPublicacao";

    $resultado = mysqli_query($conn, $sqlBusca);
    $publicacao = mysqli_fetch_assoc($resultado);

    echo $publicacao['pubCurtida'];

} else {
    echo "erro";
}
?>