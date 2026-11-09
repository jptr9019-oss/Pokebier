<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja</title>

    <style>
        
/* Configuração geral */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: black;
    color: white;
    font-family: Arial, sans-serif;
}

/* Tela inicial */
#tela-inicial {
    position: fixed;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background: black;
    z-index: 10;
}

/* Botão inicial */
#botao-acessar-loja {
    padding: 18px 40px;
    color: white;
    font-size: 22px;
    font-weight: bold;
    background: #292929;
    border: 3px solid white;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}

#botao-acessar-loja:hover {
    background: #555;
    transform: scale(1.05);
}

/* Loja */
#loja {
    display: none;
    position: fixed;
    inset: 0;
    overflow-y: auto;
    padding: 30px;

    background-image: url("../public/assets/imagens/shop_background.png");
    image-rendering: pixelated;
    background-position: center;
    background-size: cover; 
    background-repeat: no-repeat;
}

/* Título */
#titulo-loja {
    text-align: center;
    font-size: 36px;
    margin: 15px 0 40px;
    text-shadow: 3px 3px 5px black;
}

#instrucoes-loja {
    position: fixed;
    top: 50%;
    right: 150px;
    transform: translateY(-50%);

    width: 230px;
    padding: 20px;

    color: white;
    font-size: 15px;
    line-height: 1.5;
    text-align: center;

    background: rgba(0, 0, 0, 0.85);
    border: 2px solid white;
    border-radius: 10px;

    text-shadow: 1px 1px 3px black;
    z-index: 5;
}

#instrucoes-loja p {
    margin: 10px 0;
}

/* Organização dos itens */
#itens-loja {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 30px;
}

/* Cartão de cada item */
.item {
    width: 230px;
    padding: 20px;
    text-align: center;

    background: rgba(0, 0, 0, 0.85);
    border: 2px solid rgba(255, 255, 255, 0.7);
    border-radius: 12px;
}

/* Área da imagem */
.imagem-item {
    position: relative;
    width: 100%;
    height: 170px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Imagem do item */
.imagem-item img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    cursor: help;
}

/* Descrição escondida inicialmente */
.descricao-item {
    position: absolute;
    inset: 0;

    display: flex;
    justify-content: center;
    align-items: center;

    padding: 15px;
    color: white;
    font-size: 14px;
    line-height: 1.5;
    text-align: center;

    background: rgba(0, 0, 0, 0.92);
    border: 1px solid white;
    border-radius: 8px;

    opacity: 0;
    visibility: hidden;
    transition: 0.25s;
}

/* Mostra a descrição ao passar o mouse */
.imagem-item:hover .descricao-item,
.imagem-item:focus-within .descricao-item {
    opacity: 1;
    visibility: visible;
}

.item h2 {
    font-size: 21px;
    margin: 15px 0 8px;
}

.item p {
    font-size: 16px;
}

/* Botão de compra */
.botao-comprar {
    width: 100%;
    padding: 12px;

    color: white;
    font-size: 17px;
    font-weight: bold;

    background: rgba(139, 37, 37, 0.9);
    border: 2px solid white;
    border-radius: 7px;

    cursor: pointer;
    transition: 0.3s;
}

.botao-comprar:hover {
    background: #ad3636;
    transform: scale(1.04);
}

/* Ajuste para telas menores */
@media (max-width: 600px) {
    #loja {
        padding: 20px 10px;
    }

    #titulo-loja {
        font-size: 28px;
    }
}

    </style>
</head>
<body>
    
<!-- Música da loja -->
<audio id="musica-loja" src="../public/assets/imagens/shop_theme.mp3" loop></audio>

<!-- Tela inicial -->
<div id="tela-inicial">
    <button id="botao-acessar-loja">Acessar a Loja</button>
</div>

<!-- Loja -->
<div id="loja">

    <h1 id="titulo-loja">POKEBIER - LOJA</h1>

    <div id="instrucoes-loja">
    <p>Passe o cursor sobre a imagem de um item para visualizar sua descrição!</p>
    <p>Role a página para baixo para encontrar mais itens na loja!</p>
</div>

    <div id="itens-loja">

        <!-- Item 1 -->
        <div class="item">
            <div class="imagem-item">
                <img src="../public/assets/imagens/item1.png" alt="Poção">
                <div class="descricao-item">
                    Uma poção especial que recupera a energia do seu monstro.
                </div>
            </div>

            <h2>Poção</h2>
            <p>100 moedas</p>
            <button class="botao-comprar" data-item="Poção">
                Comprar
            </button>
        </div>

        <!-- Item 2 -->
        <div class="item">
            <div class="imagem-item">
                <img src="../public/assets/imagens/item2.png" alt="Super Poção">
                <div class="descricao-item">
                    Uma poção mais poderosa, capaz de recuperar bastante energia.
                </div>
            </div>

            <h2>Super Poção</h2>
            <p>250 moedas</p>
            <button class="botao-comprar" data-item="Super Poção">
                Comprar
            </button>
        </div>

        <!-- Item 3 -->
        <div class="item">
            <div class="imagem-item">
                <img src="../public/assets/imagens/item3.png" alt="Pokébola">
                <div class="descricao-item">
                    Uma cápsula utilizada para capturar novos monstros.
                </div>
            </div>

            <h2>Pokébola</h2>
            <p>150 moedas</p>
            <button class="botao-comprar" data-item="Pokébola">
                Comprar
            </button>
        </div>

    </div>
</div>

<script>

const botaoAcessarLoja = document.getElementById("botao-acessar-loja");
const telaInicial = document.getElementById("tela-inicial");
const loja = document.getElementById("loja");
const musicaLoja = document.getElementById("musica-loja");

// Acessar a loja
botaoAcessarLoja.addEventListener("click", () => {

    // Esconde a tela inicial
    telaInicial.style.display = "none";

    // Mostra a loja
    loja.style.display = "block";

    // Inicia a música
    musicaLoja.currentTime = 0;

    musicaLoja.play().catch(erro => {
        console.error("Não foi possível tocar a música:", erro);
    });

});

// Botões de compra
const botoesComprar = document.querySelectorAll(".botao-comprar");

botoesComprar.forEach(botao => {

    botao.addEventListener("click", () => {
        const item = botao.dataset.item;

        alert("Você selecionou: " + item);
    });

});

</script>

</body>
</html>