<?php
session_start();

require_once "../setup/conexao.php";

if (!isset($_SESSION['usuario_cargo']) || (int)$_SESSION['usuario_cargo'] !== 2) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT l.idLog, l.idUsuario, l.logEmail, l.logStatus, l.logIpOrigem, l.logNavegador, l.logdataHora, 
               u.userNome, u.userNick 
        FROM tblLogs l 
        LEFT JOIN tblUsuario u ON l.idUsuario = u.idUsuario 
        ORDER BY l.logdataHora DESC 
        LIMIT 100";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REDDART - Relatório de Acessos</title>
    
    <link rel="stylesheet" href="../css/relatorio.css">
</head>

<body>

    <div class="painel-conteudo">
        <h1 class="logo-principal">REDDART</h1>
        <span class="etiqueta-admin">AUDITORIA DE SEGURANÇA</span>
        <h2 class="titulo-pagina">Relatório de Acessos ao Sistema</h2>

        <table class="tabela-logs">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>E-mail Tentado</th>
                    <th>Status</th>
                    <th>IP de Origem</th>
                    <th>Navegador / Dispositivo</th>
                    <th>Data / Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($log = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td class="coluna-usuario">
                                <?php if (!empty($log['userNome'])): ?>
                                    <strong class="nome-usuario"><?php echo htmlspecialchars($log['userNome']); ?></strong>
                                    <?php if (!empty($log['userNick'])): ?>
                                        <small class="nick-usuario">@<?php echo htmlspecialchars($log['userNick']); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <small class="usuario-desconhecido">Conta não identificada</small>
                                <?php endif; ?>
                            </td>
                            <td class="coluna-email"><?php echo htmlspecialchars($log['logEmail']); ?></td>
                            <td class="coluna-status">
                                <?php 
                                    $status = strtolower($log['logStatus']);
                                    if ($status === 'sucesso'): 
                                ?>
                                    <span class="badge-status status-sucesso">Sucesso</span>
                                <?php else: ?>
                                    <span class="badge-status status-falha">Falha</span>
                                <?php endif; ?>
                            </td>
                            <td class="coluna-ip">
                                <span class="tag-ip"><?php echo htmlspecialchars($log['logIpOrigem']); ?></span>
                            </td>
                            <td class="coluna-navegador">
                                <small style="color: #aaa; font-size: 0.75rem; word-break: break-all;">
                                    <?php echo htmlspecialchars($log['logNavegador'] ?? 'Não registrado'); ?>
                                </small>
                            </td>
                            <td class="coluna-data">
                                <?php echo date('d/m/Y H:i:s', strtotime($log['logdataHora'])); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="sem-registros">
                            Nenhum registro de acesso encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="area-botoes">
            <a href="home.php" class="botao-voltar">Voltar ao Painel</a>
        </div>
    </div>

</body>

</html>