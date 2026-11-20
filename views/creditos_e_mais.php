
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créditos e Mais</title>

    <link rel="stylesheet" href="../public/assets/css/creditos.css">
</head>
<body>

    <!-- Tela inicial -->
    <div id="tela-inicial">
        <button id="botao-iniciar">INICIAR</button>
    </div>

    <!-- Música da página -->
    <audio id="musica" loop>
        <source src="../public/assets/audios/musica_creditos.mp3" type="audio/mpeg">
    </audio>

    <!-- Conteúdo da página -->
    <div id="pagina-creditos">

        <a href="menu.php" class="botao-voltar">VOLTAR</a>

        <h1>CRÉDITOS E MAIS</h1>

        <div class="caixa-texto">
            <h2>Desenvolvedores</h2>
            <p>
                Coloque aqui os nomes dos integrantes do projeto.
            </p>
        </div>

        <div class="caixa-texto">
            <h2>Sobre o projeto</h2>
            <p>
                Coloque aqui informações sobre o Monstrobolso.
            </p>
        </div>

        <div class="caixa-texto">
            <h2>Agradecimentos</h2>
            <p>
                Coloque aqui seus agradecimentos.
            </p>
        </div>

        <div class="caixa-texto">
            <h2>Mais informações</h2>
            <p>
                Coloque aqui outras informações que desejar.
            </p>
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
