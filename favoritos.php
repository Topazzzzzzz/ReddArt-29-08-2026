<?php 
include_once 'include/header.php'; 
?>

<main>
    <link rel="stylesheet" href="css/fav.css">
    <div class="top-bar top-bar-end">
        <div class="button_dn">
            <input type="checkbox" id="chk" class="checkbox">
            <label class="label" for="chk">
                <i class="fas fa-moon"></i>
                <i class="fas fa-sun"></i>
                <div class="ball"></div>
            </label>
        </div>
    </div>

    <div class="section-container salvos-container">
        <div class="salvos-header">
            <h2>Meus Salvos</h2>
            <button class="btn-criar-pasta" onclick="abrirModalPasta()">
                <i class="fa-solid fa-plus"></i> Criar Pasta
            </button>
        </div>

        <div class="pastas-grid" id="pastasContainer">
            <!-- Pastas criadas aparecerão aqui -->
        </div>

        <div class="section-header" style="margin-top: 30px;">
            <h3>Todas as Mídias Salvas</h3>
        </div>

        <div class="cards-row" id="favoritosContainer">
            <!-- Mídias salvas via localStorage entram aqui -->
        </div>
    </div>
</main>

<div id="modalCriarPasta" class="preview-modal">
    <div class="modal-pasta-box">
        <span class="close-preview" onclick="fecharModalPasta()">&times;</span>
        <h3>Criar nova pasta</h3>
        <form id="formNovaPasta">
            <input type="text" id="nomePastaInput" placeholder="Ex: Inspirações de Jogos, Paisagens..." required maxlength="50">
            <button type="submit" class="btn-salvar-pasta">Criar</button>
        </form>
    </div>
</div>

<script src="public/script.js?v=<?php echo time(); ?>"></script>

<script>
// Funções do Modal de Pasta
function abrirModalPasta() {
    const modal = document.getElementById('modalCriarPasta');
    if (modal) modal.style.display = 'flex';
}

function fecharModalPasta() {
    const modal = document.getElementById('modalCriarPasta');
    if (modal) modal.style.display = 'none';
}

// Helper para padronizar caminhos de IDs/URLs
function normalizar(str) {
    if (!str) return '';
    return String(str).trim().replace(/^https?:\/\/[^\/]+/, '');
}

// Renderização dos Itens Salvos
function carregarMidiasSalvas() {
    const container = document.getElementById('favoritosContainer');
    if (!container) return;

    const favoritos = JSON.parse(localStorage.getItem('meusFavoritos')) || [];
    console.log("Itens lidos do localStorage na pagina favoritos:", favoritos);

    if (favoritos.length === 0) {
        container.innerHTML = '<p style="color: #888; padding: 15px;">Você ainda não salvou nenhuma mídia.</p>';
        return;
    }

    container.innerHTML = '';

    favoritos.forEach(item => {
        const imagemSrc = item.imagem && item.imagem !== '' ? item.imagem : 'img/placeholder.jpg';
        const idIdentificador = normalizar(item.id || item.imagem);

        // Passamos o 'event' na função do onclick
        const cardHTML = `
            <div class="media-card" data-id="${idIdentificador}" data-titulo="${item.titulo || 'Sem título'}">
                <img src="${imagemSrc}" alt="${item.titulo || 'Mídia'}">
                <div class="card-footer-info">
                    <span class="curtida">
                        <i class="fa-regular fa-heart"></i>
                        <span>${item.curtidas || 0}</span>
                    </span>
                    <span><i class="fa-regular fa-comment"></i> 0</span>
                    <i class="fa-solid fa-bookmark bookmark-icon active" onclick="removerSalvo(event, '${idIdentificador}', this)"></i>
                </div>
            </div>
        `;
        container.innerHTML += cardHTML;
    });
}

function removerSalvo(event, idOuImagem, iconeElemento) {
    // IMPEDE que o clique se espalhe para o script.js (muda tudo!)
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    let favoritos = JSON.parse(localStorage.getItem('meusFavoritos')) || [];
    const alvo = normalizar(idOuImagem);

    // Filtra e remove
    favoritos = favoritos.filter(item => {
        const itemId = normalizar(item.id);
        const itemImg = normalizar(item.imagem);
        return itemId !== alvo && itemImg !== alvo;
    });

    // Salva o array limpo
    localStorage.setItem('meusFavoritos', JSON.stringify(favoritos));

    // Remove da tela
    const card = iconeElemento ? iconeElemento.closest('.media-card, .card') : null;
    if (card) {
        card.remove();
    }

    if (favoritos.length === 0) {
        const container = document.getElementById('favoritosContainer');
        if (container) {
            container.innerHTML = '<p style="color: #888; padding: 15px;">Você ainda não salvou nenhuma mídia.</p>';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    carregarMidiasSalvas();
});
</script>

</body>
</html>