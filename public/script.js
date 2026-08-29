/* ==========================================================================
   FUNÇÃO AUXILIAR DE NORMALIZAÇÃO DE ID
   ========================================================================== */
function normalizarId(id) {
    if (!id) return '';
    let idStr = String(id).trim();
    return idStr.replace(/^https?:\/\/[^\/]+/, '');
}

document.addEventListener("DOMContentLoaded", () => {
    // Validação de Usuários Proibidos (Logicfox e Spinelli) no Cadastro
    const formCadastro = document.querySelector("form");
    const inputNick = document.getElementById("userNick");

    if (formCadastro && inputNick) {
        formCadastro.addEventListener("submit", (e) => {
            const valorDigitado = inputNick.value.trim().toLowerCase();
            const termosProibidos = ["logikfox", "spinelli"];

            let bloqueado = false;
            for (let i = 0; i < termosProibidos.length; i++) {
                if (valorDigitado.includes(termosProibidos[i])) {
                    bloqueado = true;
                    break;
                }
            }

            if (bloqueado) {
                alert("Acesso negado: Este nome de usuário não é permitido.");
                e.preventDefault(); // Impede o envio do formulário
            }
        });
    }

    // 1. Sidebar
    const openBtn = document.getElementById('open_btn');
    const sidebar = document.getElementById('sidebar');

    if (openBtn && sidebar) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open-sidebar');
        });
    }

    // 2. Dados e Cards (Opcional caso utilize dados estáticos)
    const data = [
        {
            title: "Fim de tarde",
            description: "@skywalker",
            image: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&auto=format&fit=crop&q=60"
        },
        {
            title: "Luzes da cidade",
            description: "@astroboy",
            image: "https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=500&auto=format&fit=crop&q=60"
        },
        {
            title: "Profundezas",
            description: "@lonelynight",
            image: "https://images.unsplash.com/photo-1682687220063-4742bd7fd538?w=500&auto=format&fit=crop&q=60"
        },
        {
            title: "Natureza viva",
            description: "@greenmind",
            image: "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=500&auto=format&fit=crop&q=60"
        }
    ];

    const cardContainer = document.querySelector('.card-container');
    const searchInput = document.getElementById('SearchInput');

    function displayData(items) {
        if (!cardContainer) return;
        cardContainer.innerHTML = '';

        items.forEach(e => {
            cardContainer.innerHTML += `    
                <div class="card">
                    <img src="${e.image}" alt="${e.title}" style="width:100%; height:140px; object-fit:cover;">
                    <div style="padding: 14px;">
                        <h3>${e.title}</h3>
                        <span style="color: #a1a1aa; font-size: 12px;">${e.description}</span>
                    </div>
                </div>
            `;
        });
    }

    if (cardContainer && data.length > 0) {
        displayData(data);
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', (e) => {
            const value = e.target.value.toLowerCase();
            const filtered = data.filter(item =>
                item.title.toLowerCase().includes(value) ||
                item.description.toLowerCase().includes(value)
            );
            displayData(filtered);
        });
    }

    // 2. Barra de categorias (carrossel: desliza sozinho + setas laterais)
    const catBar = document.getElementById('categoriasBar');
    const arrowLeft = document.getElementById('catArrowLeft');
    const arrowRight = document.getElementById('catArrowRight');

    if (catBar && arrowLeft && arrowRight) {
        const VELOCIDADE_MARQUEE = 0.55; // px por frame (~33px/s)
        const prefereMenosMovimento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const wrapper = catBar.closest('.categorias-wrapper');

        let marqueePausado = false;      // Pausa TEMPORÁRIA (só enquanto o mouse está em cima)
        let modoManual = prefereMenosMovimento; // Desliga o movimento EM DEFINITIVO
        let ultimoTempo = null;

        try {
            if (sessionStorage.getItem('catManual') === '1') modoManual = true;
        } catch (e) { /* sessionStorage indisponível */ }

        const limiteMaximo = () => Math.max(catBar.scrollWidth - catBar.clientWidth, 0);
        const passoRolagem = () => Math.max(catBar.clientWidth * 0.8, 240);

        function centralizarAtivo() {
            const btnAtivo = catBar.querySelector('.cat-btn.active');
            if (!btnAtivo) return;
            const alvo = btnAtivo.offsetLeft - (catBar.clientWidth - btnAtivo.offsetWidth) / 2;
            catBar.scrollLeft = Math.max(0, Math.min(alvo, limiteMaximo()));
        }

        centralizarAtivo();

        function rolarPara(posicao, suave = true) {
            const destino = Math.max(0, Math.min(posicao, limiteMaximo()));
            try {
                catBar.scrollTo({ left: destino, behavior: suave ? 'smooth' : 'auto' });
            } catch (e) {
                catBar.scrollLeft = destino;
            }
        }

        function marqueeLoop(timestamp) {
            if (ultimoTempo === null) ultimoTempo = timestamp;
            const delta = timestamp - ultimoTempo;
            ultimoTempo = timestamp;

            const limite = limiteMaximo();

            if (!marqueePausado && !modoManual && limite > 0) {
                catBar.scrollLeft += VELOCIDADE_MARQUEE * (delta / 16.7);

                if (catBar.scrollLeft >= limite) {
                    catBar.scrollLeft = 0;
                }
            }

            requestAnimationFrame(marqueeLoop);
        }

        requestAnimationFrame((t) => {
            ultimoTempo = null;
            requestAnimationFrame(marqueeLoop);
        });

        if (wrapper) {
            wrapper.addEventListener('mouseenter', () => {
                if (!modoManual) marqueePausado = true;
            });
            wrapper.addEventListener('mouseleave', () => {
                marqueePausado = false;
            });
        }

        function rolar(direcao) {
            const limite = limiteMaximo();
            if (limite === 0) return;

            rolarPara(catBar.scrollLeft + direcao * passoRolagem());
        }

        arrowLeft.addEventListener('click', () => {
            modoManual = true;
            rolar(-1);
        });

        arrowRight.addEventListener('click', () => {
            modoManual = true;
            rolar(1);
        });

        catBar.querySelectorAll('.cat-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                try { sessionStorage.setItem('catManual', '1'); } catch (e) { /* ignora */ }
            });
        });

        let arrastando = false;
        let moveuDuranteArrasto = false;
        let inicioX = 0;
        let scrollInicial = 0;

        catBar.addEventListener('pointerdown', (e) => {
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            arrastando = true;
            moveuDuranteArrasto = false;
            inicioX = e.clientX;
            scrollInicial = catBar.scrollLeft;
            catBar.classList.add('arrastando');
        });

        window.addEventListener('pointermove', (e) => {
            if (!arrastando) return;
            const delta = e.clientX - inicioX;
            if (Math.abs(delta) > 5) {
                moveuDuranteArrasto = true;
                modoManual = true;
            }
            catBar.scrollLeft = scrollInicial - delta;
        });

        window.addEventListener('pointerup', () => {
            arrastando = false;
            catBar.classList.remove('arrastando');
        });

        catBar.addEventListener('click', (e) => {
            if (moveuDuranteArrasto) {
                e.preventDefault();
                e.stopPropagation();
                moveuDuranteArrasto = false;
            }
        }, true);
    }

    // 3. Tema Claro / Escuro (com persistência)
    const chk = document.getElementById('chk');
    if (chk) {
        chk.checked = localStorage.getItem('tema') === 'claro';

        chk.addEventListener('change', () => {
            const temaClaro = document.body.classList.toggle('dark');
            localStorage.setItem('tema', temaClaro ? 'claro' : 'escuro');
        });
    }

    // 4. Pop-up de Configurações
    const configBtn = document.getElementById("logout_btn");
    const popup = document.getElementById("config_popup");
    const overlay = document.getElementById("overlay");
    const closeBtn = document.getElementById("close_popup");

    if (configBtn && popup && overlay) {
        configBtn.addEventListener("click", () => {
            popup.classList.add("show");
            overlay.classList.add("show");
        });

        function fecharConfig() {
            popup.classList.remove("show");
            overlay.classList.remove("show");
        }

        if (closeBtn) closeBtn.addEventListener("click", fecharConfig);
        overlay.addEventListener("click", fecharConfig);
    }

    // 5. Pop-up de Visualização (Imagem do Banco + Comentários)
    const previewModal = document.getElementById("previewModal");
    const previewImage = document.getElementById("previewImage");
    const closePreview = document.querySelector(".close-preview");

    window.abrirModal = function (urlImagem, nomeAutor, idPub, titulo, descricao, curtidas) {
        if (previewModal && previewImage) {
            previewImage.src = urlImagem;
            previewImage.dataset.id = idPub || urlImagem;

            const tituloModal = document.getElementById("modalTitulo");
            if (tituloModal) {
                tituloModal.innerText = titulo || "Sem título";
            }

            const descricaoModal = document.getElementById("modalDescricao");
            if (descricaoModal) {
                descricaoModal.innerText = descricao || "";
            }

            const nomeModal = document.getElementById("modalNomeUsuario");
            if (nomeModal) {
                nomeModal.innerText = nomeAutor;
            }

            const curtidasModal = document.getElementById("modalCurtidas");
            if (curtidasModal) {
                curtidasModal.innerText = curtidas ?? 0;
            }

            // Reseta os comentários ao abrir outra imagem
            const lista = document.getElementById("listaComentarios");
            if (lista) {
                lista.innerHTML = "<p class='sem-comentario'>Nenhum comentário ainda.</p>";
            }

            const contador = document.getElementById("modalComentarios");
            if (contador) {
                contador.innerText = "0";
            }

            // Sincroniza o ícone de salvar do modal se já estiver salvo
            const favoritos = obterFavoritos();
            const idAtual = normalizarId(previewImage.dataset.id);
            const estaSalvo = favoritos.some(item =>
                normalizarId(item.id) === idAtual || normalizarId(item.imagem) === idAtual
            );

            const btnIcon = document.querySelector('#btnSalvarModal i');
            if (btnIcon) {
                if (estaSalvo) {
                    btnIcon.classList.remove('fa-regular');
                    btnIcon.classList.add('fa-solid');
                } else {
                    btnIcon.classList.remove('fa-solid');
                    btnIcon.classList.add('fa-regular');
                }
            }

            previewModal.classList.add("show");
        }
    };

    if (closePreview && previewModal) {
        closePreview.addEventListener("click", () => {
            previewModal.classList.remove("show");
        });

        previewModal.addEventListener("click", (e) => {
            if (e.target === previewModal) {
                previewModal.classList.remove("show");
            }
        });
    }

    // 6. Marcar Bookmarks dos Favoritos ao Carregar a Página
    const favoritos = obterFavoritos();
    const idsFavoritados = favoritos.flatMap(item => [normalizarId(item.id), normalizarId(item.imagem)]);

    document.querySelectorAll('.media-card, .card').forEach(card => {
        const imgElement = card.querySelector('img');
        const rawId = card.dataset.id || (imgElement ? imgElement.getAttribute('src') : '');
        const cardId = normalizarId(rawId);

        const icon = card.querySelector('.bookmark-icon, .fa-bookmark');
        if (icon) {
            if (idsFavoritados.includes(cardId)) {
                icon.classList.add('active', 'fa-solid');
                icon.classList.remove('fa-regular');
            } else {
                icon.classList.remove('active', 'fa-solid');
                icon.classList.add('fa-regular');
            }
        }
    });
});

/* ==========================================================================
   FUNÇÕES GLOBAIS (AJAX / EVENTOS / FAVORITOS)
   ========================================================================== */

// Função global para enviar comentários no modal
function enviarComentario() {
    const input = document.getElementById("inputComentario");
    const lista = document.getElementById("listaComentarios");

    if (!input || !lista) return;

    const texto = input.value.trim();

    if (texto !== "") {
        if (lista.innerHTML.includes("Nenhum comentário ainda.")) {
            lista.innerHTML = "";
        }

        const novoComentario = document.createElement("div");
        novoComentario.className = "comentario-item";
        novoComentario.innerHTML = `<strong>Você:</strong> ${texto}`;

        lista.appendChild(novoComentario);
        input.value = "";
        lista.scrollTop = lista.scrollHeight;

        const contador = document.getElementById("modalComentarios");
        if (contador) {
            contador.innerText = parseInt(contador.innerText) + 1;
        }
    }
}

function curtir(event, idPublicacao) {
    event.stopPropagation();

    const dados = new FormData();
    dados.append("idPublicacao", idPublicacao);

    fetch("curtir.php", {
        method: "POST",
        body: dados
    })
        .then(response => response.text())
        .then(resultado => {
            if (resultado !== "erro") {
                const element = document.getElementById("curtidas-" + idPublicacao);
                if (element) element.textContent = resultado;
            }
        })
        .catch(error => {
            console.error(error);
            alert("ERRO: " + error);
        });
}

// Salvar/Favoritar diretamente pelo Botão do Modal
function alternarIconeSalvar() {
    const btnSalvar = document.getElementById('btnSalvarModal');
    if (!btnSalvar) return;

    const btnIcon = btnSalvar.querySelector('i') || btnSalvar;
    const previewImage = document.getElementById('previewImage');
    const tituloModal = document.getElementById('modalTitulo');
    const curtidasModal = document.getElementById('modalCurtidas');

    if (!previewImage || !previewImage.src) {
        console.error("Nenhuma imagem ativa no modal para salvar.");
        return;
    }

    const imagemUrl = previewImage.getAttribute('src');
    const cardId = previewImage.dataset.id || imagemUrl;

    const cardData = {
        id: normalizarId(cardId),
        titulo: tituloModal ? tituloModal.innerText : 'Sem título',
        imagem: imagemUrl,
        curtidas: curtidasModal ? curtidasModal.innerText : '0'
    };

    alternarFavorito(cardData);

    if (btnIcon.classList.contains('fa-solid')) {
        btnIcon.classList.remove('fa-solid');
        btnIcon.classList.add('fa-regular');
    } else {
        btnIcon.classList.remove('fa-regular');
        btnIcon.classList.add('fa-solid');
    }
}

/* ==========================================================================
   SISTEMA DE FAVORITOS (LOCALSTORAGE)
   ========================================================================== */

function obterFavoritos() {
    return JSON.parse(localStorage.getItem('meusFavoritos')) || [];
}

function alternarFavorito(cardData) {
    let favoritos = obterFavoritos();
    const idNormalizado = normalizarId(cardData.id);

    const index = favoritos.findIndex(item => {
        const itemId = normalizarId(item.id);
        const itemImg = normalizarId(item.imagem);
        return itemId === idNormalizado || itemImg === idNormalizado;
    });

    if (index > -1) {
        favoritos.splice(index, 1);
        console.log("Removido dos salvos:", idNormalizado);
    } else {
        cardData.id = idNormalizado;
        cardData.imagem = normalizarId(cardData.imagem);
        favoritos.push(cardData);
        console.log("Salvo com sucesso:", cardData);
    }

    localStorage.setItem('meusFavoritos', JSON.stringify(favoritos));
}

// Escutador global para cliques em cards no feed
document.addEventListener('click', function (e) {
    const bookmark = e.target.closest('.bookmark-icon, .fa-bookmark');

    if (!bookmark || e.target.closest('#btnSalvarModal')) return;

    e.preventDefault();
    e.stopPropagation();

    const card = bookmark.closest('.media-card, .card');
    if (!card) return;

    const imgElement = card.querySelector('img');
    const imagemUrl = imgElement ? imgElement.getAttribute('src') : '';
    let cardId = card.dataset.id || imagemUrl;

    if (!cardId) return;

    const cardData = {
        id: normalizarId(cardId),
        titulo: card.dataset.titulo || imgElement?.alt || 'Sem título',
        imagem: imagemUrl,
        curtidas: card.querySelector('.curtida span, [id^="curtidas-"]')?.innerText.trim() || '0'
    };

    alternarFavorito(cardData);

    if (bookmark.classList.contains('fa-solid')) {
        bookmark.classList.remove('fa-solid', 'active');
        bookmark.classList.add('fa-regular');
    } else {
        bookmark.classList.remove('fa-regular');
        bookmark.classList.add('fa-solid', 'active');
    }
});

// Função para remoção chamada na página de favoritos
function removerSalvo(id, iconeElemento) {
    let favoritos = obterFavoritos();
    const idParaRemover = normalizarId(id);

    favoritos = favoritos.filter(item => {
        const itemId = normalizarId(item.id);
        const itemImg = normalizarId(item.imagem);
        return itemId !== idParaRemover && itemImg !== idParaRemover;
    });

    localStorage.setItem('meusFavoritos', JSON.stringify(favoritos));

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