
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créditos e Mais</title>

    <style>

/* Configurações gerais */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    color: white;
    background: black;
    text-align: center;
}

/* Tela inicial */

#tela-inicial {
    position: fixed;
    inset: 0;
    z-index: 1000;

    background: black;

    display: flex;
    justify-content: center;
    align-items: center;
}

#botao-iniciar {
    padding: 20px 50px;
    font-size: 24px;
    font-weight: bold;

    color: white;
    background: #292929;

    border: 2px solid white;
    border-radius: 10px;

    cursor: pointer;
    transition: 0.3s;
}

#botao-iniciar:hover {
    background: #555;
    transform: scale(1.08);
}

/* Página de créditos */

#pagina-creditos {
    display: none;

    min-height: 100vh;
    padding: 40px 20px;

    background: black;
}

/* Título */

h1 {
    font-size: 40px;
    margin-bottom: 40px;
    text-shadow: 3px 3px 5px black;
}

/* Caixas de texto */

.caixa-texto {
    width: 80%;
    max-width: 800px;

    margin: 25px auto;
    padding: 25px;

    background: rgba(0, 0, 0, 0.65);

    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;

    text-align: left;
}

.caixa-texto h2 {
    margin-top: 0;
    color: #ffe68a;
}

.caixa-texto p {
    font-size: 17px;
    line-height: 1.6;
}

.caixa-texto img {
    display: block;
    max-width: 200%;
    width: 600px;
    height: auto;

    margin: 15px auto;
    border-radius: 10px;
}

/* Botão voltar */

.botao-voltar {
    display: inline-block;

    position: absolute;
    top: 20px;
    left: 20px;

    padding: 10px 20px;

    color: white;
    background: rgba(0, 0, 0, 0.7);

    border: 1px solid white;
    border-radius: 8px;

    text-decoration: none;
}

.botao-voltar:hover {
    background: rgba(80, 80, 80, 0.8);
}

/* Ajuste para telas menores */

@media (max-width: 600px) {

    h1 {
        font-size: 28px;
        margin-top: 60px;
    }

    .caixa-texto {
        width: 95%;
        padding: 18px;
    }

}

    </style>
</head>
<body>

    <!-- Tela inicial -->
    <div id="tela-inicial">
        <button id="botao-iniciar">Acessar Créditos e Mais</button>
    </div>

    <!-- Música da página -->
    <audio id="musica" loop>
        <source src="../public/assets/imagens/my_castle_town.mp3" type="audio/mpeg">
    </audio>

    <!-- Conteúdo da página -->
    <div id="pagina-creditos">

        <a href="pokebier_menu.php" class="botao-voltar">VOLTAR</a>

        <h1>CRÉDITOS E MAIS</h1>

        <div class="caixa-texto">
            <h2>Desenvolvedores</h2>
            <p>
                João Pedro Teixeira Ribeiro, Rafael Bierhals Pereira e Victor Appel Dilli.
            </p>
        </div>

        <div class="caixa-texto">
            <h2>Sobre o Projeto</h2>
            <p>
                Este projeto utiliza diversas criações e ideias de Toby Fox (e seu time), "Rain (orbitaldot)", Horror Skunx, One Piece, Looney Tunes, "Yu-Gi-Oh!" e mais.
                <br><br>
                O site "https://www.demirramon.com/generators/undertale_text_box_generator" foi usado para gerar "textboxes" no estilo do videogame "DELTARUNE".
                <br><br>
                A introdução da tela de cadastro/login (aquela com as flores) foi inspirada por um vídeo de Toby Fox, mas talvez também tenha sido inspirada por uma animação de fã do jogo "DELTARUNE".
            </p>
        </div>

        <div class="caixa-texto">
            <h2>Tipos dos Pokebiers</h2>
            <p>
                Spamton: vírus
                <br>
                Flowery: planta
                <br>
                Barriguinha Mole: meme
                <br>
                Dark Magician: fogo
                <br>
                Mesmer: lutador
                <br>
                Chiquinho Gavião: cartoon
                <br>
                Verity: hacker
                <br>
                Inspetor Shepherd: peak
                <br>
                Giorno Giovanna: meme
                <br>
                Eric: leader
                <br>
                Spamton NEO: vírus e metal
                <br>
                OMEGA Flowery: planta e omega
                <br>
                Barriguinha Mole e Dollynho: meme e propaganda
                <br>
                Dark Magician of Chaos: fogo e ilusionista
                <br>
                Mesmer (Despertada): lutador e ilusionista
                <br>
                Chiquinho Gavião Satisfeito: cartoon e saudável
                <br>
                Lord Verity: hacker e admin
                <br>
                Con D. Oriano: peak e D.
                <br>
                Giorno Giovanna (Requiem): meme e bizarro
                <br>
                Evil Eric: leader e evil
            </p>
        </div>

        <div class="caixa-texto">
            <h2>Efetividades dos Tipos de Pokebier</h2>
            <p>
                As efetividades não fazem muito sentido, mas você pode checar elas nessa tabela:
            </p>
            <img src="../public/assets/imagens/tabela_efetividades.png" alt="Tabela de efetividades dos tipos de pokebier">
        </div>

    </div>

    <script>
        const botaoIniciar = document.getElementById("botao-iniciar");
        const telaInicial = document.getElementById("tela-inicial");
        const paginaCreditos = document.getElementById("pagina-creditos");
        const musica = document.getElementById("musica");

        botaoIniciar.addEventListener("click", function() {

            telaInicial.style.display = "none";
            paginaCreditos.style.display = "block";

            musica.play();

        });
    </script>

</body>
</html>
