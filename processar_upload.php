<?php
include "setup/conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    // Recebe o ID do gênero selecionado no formulário
    $idGenero = isset($_POST['categoria']) ? intval($_POST['categoria']) : 1;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $nomeArquivo = $_FILES['imagem']['name'];
        $arquivoTemp = $_FILES['imagem']['tmp_name'];

        $pastaDestino = "img/uploads/";

        if (!file_exists($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        $novoNome = uniqid() . "." . pathinfo($nomeArquivo, PATHINFO_EXTENSION);
        $caminhoFinal = $pastaDestino . $novoNome;

        if (move_uploaded_file($arquivoTemp, $caminhoFinal)) {
            // Insere na tabela vinculando o idGenero selecionado
            $stmt = $conn->prepare("INSERT INTO tblPublicacoes (idUsuario, pubHora, pubLink, pubLegenda, idGenero) VALUES (?, NOW(), ?, ?, ?)");

            $idUsuarioTemp = 1; // Substitua pelo ID da sessão do usuário logado se houver

            $stmt->bind_param("issi", $idUsuarioTemp, $caminhoFinal, $titulo, $idGenero);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                echo "<script>alert('Imagem publicada com sucesso!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Erro ao salvar no banco: " . $stmt->error . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Erro ao mover o arquivo de imagem.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Nenhuma imagem selecionada.'); window.history.back();</script>";
    }
}
?>