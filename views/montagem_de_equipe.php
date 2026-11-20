
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montagem de Equipe</title>

    <style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    color: white;
    text-align: center;
    background: black;
}

#pagina-equipe {
    min-height: 100vh;
    padding: 30px;

    background-image: url("../public/assets/imagens/field_of_hopes_and_dreams_concept.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

/* TELA INICIAL */
#tela-inicial {
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: black;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* BOTÃO INICIAR */
#botao-iniciar {
    padding: 20px 50px;
    font-size: 25px;
    font-weight: bold;

    background-color: #222;
    color: white;

    border: 3px solid white;
    border-radius: 12px;

    cursor: pointer;
    transition: 0.3s;
}

#botao-iniciar:hover {
    background-color: #555;
    transform: scale(1.1);
}

/* PÁGINA DA EQUIPE */
#pagina-equipe {
    min-height: 100vh;
    padding-top: 20px;
}


h1 {
    margin-top: 50px;
    color: #f5d76e;
}

.subtitulo {
    color: #ccc;
}

.voltar {
    position: fixed;
    top: 20px;
    left: 20px;
    padding: 12px 18px;
    background: #333;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    z-index: 10;
}

.voltar:hover {
    background: #555;
}

.container {
    max-width: 1200px;
    margin: 40px auto;
}

.equipe, .selecao {
    background: rgba(0, 0, 0, 0.55);
    padding: 25px;
    margin-bottom: 30px;
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.slots-equipe {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 15px;
}

.slot {
    position: relative;
    min-height: 155px;
    padding: 12px;
    background: rgba(50, 50, 50, 0.65);
    border: 3px solid transparent;
    border-radius: 12px;
    cursor: pointer;
}

.slot:hover {
    background: #555;
}

.slot.selecionado {
    border-color: #f5d76e;
}

.slot img {
    width: 90px;
    height: 90px;
    object-fit: contain;
}

.slot h3 {
    font-size: 14px;
}

.slot.vazio {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #aaa;
}

.slot.vazio span {
    font-size: 40px;
}

.remover {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #a83232;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
}

.lista-pokebiers {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
}

.pokebier-card {
    padding: 15px;
    background: rgba(50, 50, 50, 0.65);
    border: 2px solid transparent;
    border-radius: 12px;
    cursor: pointer;
    transition: 0.2s;
}

.pokebier-card:hover {
    transform: scale(1.04);
    border-color: #f5d76e;
}

.pokebier-card img {
    width: 110px;
    height: 110px;
    object-fit: contain;
}

.pokebier-card h3 {
    font-size: 15px;
}

.pokebier-card.na-equipe {
    border-color: #5b9b64;
}

.pokebier-card.bloqueado {
    filter: grayscale(100%);
    opacity: 0.4;
    cursor: not-allowed;
}

.cadeado {
    color: #ff7777;
    font-size: 13px;
}

.status {
    color: #9cdb9c;
    font-size: 13px;
}

@media (max-width: 800px) {
    .slots-equipe {
        grid-template-columns: repeat(3, 1fr);
    }

    .lista-pokebiers {
        grid-template-columns: repeat(2, 1fr);
    }
}

</style>
</head>

<body>

<!-- TELA INICIAL -->
<div id="tela-inicial">
    <button id="botao-iniciar">INICIAR</button>
</div>

<!-- MÚSICA -->
<audio id="musica" loop>
    <source src="../public/assets/imagens/thrash_machine.mp3" type="audio/mpeg">
</audio>

<!-- PÁGINA DA EQUIPE -->
<div id="pagina-equipe" style="display: none;">

    <a href="pokebier_menu.php" class="voltar">← Voltar ao menu</a>

    <h1>MONTE SUA EQUIPE</h1>
    <p class="subtitulo">Escolha até 6 Pokebiers para sua equipe!</p>

    <main class="container">

        <section class="equipe">
            <h2>Minha equipe</h2>
            <div id="slots-equipe" class="slots-equipe"></div>
            <p id="mensagem">Selecione um Pokebier!</p>
        </section>

        <section class="selecao">
            <h2>Pokebiers disponíveis</h2>
            <div id="lista-pokebiers" class="lista-pokebiers"></div>
        </section>

    </main>

</div>

<script>
    const pokebiers = [
        { id: 1, nome: "Flowery", imagem: "flowery_default.png", evolucao: false, desbloqueado: true },
        { id: 2, nome: "Spamton", imagem: "Spamton_default.png", evolucao: false, desbloqueado: true },
        { id: 3, nome: "Barriguinha Mole", imagem: "barriguinha_mole_default.gif", evolucao: false, desbloqueado: true },
        { id: 4, nome: "Dark Magician", imagem: "dark_magician_default.png", evolucao: false, desbloqueado: true },
        { id: 5, nome: "Mesmer", imagem: "mesmer_default.gif", evolucao: false, desbloqueado: true },
        { id: 6, nome: "Chiquinho Gavião", imagem: "chiquinho_gaviao_default.png", evolucao: false, desbloqueado: true },
        { id: 7, nome: "Verity", imagem: "verity_default.png", evolucao: false, desbloqueado: true },
        { id: 8, nome: "Inspetor Shepherd", imagem: "inspetor_shepherd_default.png", evolucao: false, desbloqueado: true },
        { id: 9, nome: "Territo", imagem: "territo.png", evolucao: false, desbloqueado: true },
        { id: 10, nome: "Sombrio", imagem: "sombrio.png", evolucao: false, desbloqueado: true },

        // Evoluções: começam bloqueadas
        { id: 11, nome: "OMEGA Flowery", imagem: "omega_flowery_default.gif", evolucao: true, desbloqueado: false },
        { id: 12, nome: "Spamton NEO", imagem: "spamton_neo_default.gif", evolucao: true, desbloqueado: false },
        { id: 13, nome: "Barriguinha Mole e Dollynho", imagem: "barriguinha_mole_e_dollynho_default.png", evolucao: true, desbloqueado: false },
        { id: 14, nome: "Dark Magician of Chaos", imagem: "dark_magician_of_chaos_default.png", evolucao: true, desbloqueado: false },
        { id: 15, nome: "Chiquinho Gavião Satisfeito", imagem: "chiquinho_gaviao_satisfeito_default.gif", evolucao: true, desbloqueado: false },
        { id: 16, nome: "Mesmer Despertada", imagem: "mesmer_despertada_default.png", evolucao: true, desbloqueado: false },
        { id: 17, nome: "Lord Verity", imagem: "lord_verity_default.png", evolucao: true, desbloqueado: false },
        { id: 18, nome: "Con D. Oriano", imagem: "con_d_oriano_default.png", evolucao: true, desbloqueado: false },
        { id: 19, nome: "Terrassauro", imagem: "terrassauro.png", evolucao: true, desbloqueado: false },
        { id: 20, nome: "Umbrius", imagem: "umbrius.png", evolucao: true, desbloqueado: false }
    ];

    // Equipe inicial do jogador
    let equipe = [1, 2, 3, 4, 5, 6];

    // Guarda o espaço da equipe que o jogador quer substituir
    let slotSelecionado = null;

    const pastaImagens = "../public/assets/imagens/";

    function mostrarEquipe() {
        const container = document.getElementById("slots-equipe");

        container.innerHTML = "";

        for (let i = 0; i < 6; i++) {
            const idPokebier = equipe[i];
            const pokebier = pokebiers.find(p => p.id === idPokebier);

            if (pokebier) {
                container.innerHTML += `
                    <div class="slot ${slotSelecionado === i ? 'selecionado' : ''}"
                         onclick="selecionarSlot(${i})">

                        <img src="${pastaImagens + pokebier.imagem}"
                             alt="${pokebier.nome}">

                        <h3>${pokebier.nome}</h3>

                        <button class="remover"
                                onclick="event.stopPropagation(); removerPokebier(${i})">
                            X
                        </button>
                    </div>
                `;
            } else {
                container.innerHTML += `
                    <div class="slot vazio ${slotSelecionado === i ? 'selecionado' : ''}"
                         onclick="selecionarSlot(${i})">
                        <span>+</span>
                        <p>Vazio</p>
                    </div>
                `;
            }
        }
    }

    function mostrarPokebiers() {
        const container = document.getElementById("lista-pokebiers");

        container.innerHTML = "";

        pokebiers.forEach(pokebier => {
            const jaNaEquipe = equipe.includes(pokebier.id);

            container.innerHTML += `
                <div class="pokebier-card
                    ${!pokebier.desbloqueado ? 'bloqueado' : ''}
                    ${jaNaEquipe ? 'na-equipe' : ''}"
                    onclick="adicionarPokebier(${pokebier.id})">

                    <img src="${pastaImagens + pokebier.imagem}"
                         alt="${pokebier.nome}">

                    <h3>${pokebier.nome}</h3>

                    ${!pokebier.desbloqueado
                        ? '<span class="cadeado">🔒 Bloqueado</span>'
                        : jaNaEquipe
                            ? '<span class="status">Na equipe</span>'
                            : '<span class="status">Disponível</span>'
                    }
                </div>
            `;
        });
    }

    function selecionarSlot(indice) {
        slotSelecionado = indice;

        document.getElementById("mensagem").textContent =
            "Agora escolha um Pokebier para esse espaço.";

        mostrarEquipe();
    }

    function adicionarPokebier(id) {
        const pokebier = pokebiers.find(p => p.id === id);

        if (!pokebier.desbloqueado) {
            alert("Você ainda não desbloqueou esse Pokebier!");
            return;
        }

        if (equipe.includes(id)) {
            alert("Esse Pokebier já está na sua equipe!");
            return;
        }

        if (slotSelecionado !== null) {
            
            equipe[slotSelecionado] = id;
            slotSelecionado = null;

        } else if (equipe.length < 6) {
            
            equipe.push(id);

        } else {
            alert("Sua equipe está cheia! Selecione um espaço para substituir.");
            return;
        }

        document.getElementById("mensagem").textContent =
            "Equipe atualizada com sucesso!";

        atualizarPagina();
    }

    function removerPokebier(indice) {
        equipe.splice(indice, 1);
        slotSelecionado = null;

        atualizarPagina();
    }

    function atualizarPagina() {
        mostrarEquipe();
        mostrarPokebiers();
    }


const botaoIniciar = document.getElementById("botao-iniciar");
const telaInicial = document.getElementById("tela-inicial");
const paginaEquipe = document.getElementById("pagina-equipe");
const musica = document.getElementById("musica");

botaoIniciar.addEventListener("click", function() {

    // Começa a música
    musica.play();

    // Esconde a tela inicial
    telaInicial.style.display = "none";

    // Mostra a montagem de equipe
    paginaEquipe.style.display = "block";

});


    // Inicializa a página
    atualizarPagina();

</script>

</body>
</html>
