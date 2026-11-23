<?php
require_once __DIR__ . '/../src/controllers/verificar_login.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>

    <style>
        /* Configuração geral */
/* Configuração geral */
body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: black;
    font-family: Arial, sans-serif;
    overflow: hidden;
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

/* Botão Acessar o Menu */
#botao-acessar-menu {
    padding: 15px 35px;
    font-size: 20px;
    color: white;
    background: rgba(20, 20, 20, 0.9);
    border: 2px solid rgba(255, 255, 255, 0.6);
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

#botao-acessar-menu:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: white;
    transform: scale(1.05);
}

/* Menu principal */
#menu-principal {
    display: none;
    position: fixed;
    inset: 0;
    flex-direction: column;
    align-items: center;
    padding-top: 40px;

    background-image: url("../public/assets/imagens/flower_kingdom.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Logo */
#logo-jogo {
    width: 400px;
    max-width: 80%;
    height: auto;
    object-fit: contain;
    margin-bottom: 50px;
}

/* Área dos botões */
#botoes-menu {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 18px;
}

/* Botões do menu */
#botoes-menu button {
    width: 270px;
    padding: 15px;

    color: white;
    font-size: 20px;
    font-weight: bold;

    background: rgba(10, 10, 10, 0.85);
    border: 2px solid rgba(255, 255, 255, 0.6);
    border-radius: 8px;

    cursor: pointer;
    transition: 0.3s;
}

/* Efeito ao passar o mouse */
#botoes-menu button:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: white;
    transform: scale(1.05);
}
    </style>
</head>
<body>
    <!-- Música do menu -->
<audio id="musica-menu" src="../public/assets/imagens/menu_song.mp3" loop></audio>

<!-- Tela inicial -->
<div id="tela-inicial">
    <button id="botao-acessar-menu">Acessar o Menu</button>
</div>

<!-- Menu principal -->
<div id="menu-principal">

    <!-- Logo do jogo -->
    <img 
        id="logo-jogo" 
        src="../public/assets/imagens/logo_monstrobolso.png" 
        alt="Logo Monstrobolso"
    >

    <!-- Botões do menu -->
    <div id="botoes-menu">

        <button onclick="location.href='jogar.php'">
            Jogar
        </button>

        <button onclick="location.href='flowery_intro.php'">
            Tutorial
        </button>

         <button onclick="location.href='loja.php'">
            Loja
        </button>

        <button onclick="location.href='montagem_de_equipe.php'">
            Montagem de Equipe
        </button>

        <button onclick="location.href='creditos_e_mais.php'">
            Créditos e Mais
        </button>

        <button onclick="location.href='../src/controllers/logout.php'">
            Sair
        </button>

    </div>

</div>

<script>
const botaoAcessarMenu = document.getElementById("botao-acessar-menu");
const telaInicial = document.getElementById("tela-inicial");
const menuPrincipal = document.getElementById("menu-principal");
const musicaMenu = document.getElementById("musica-menu");

botaoAcessarMenu.addEventListener("click", () => {

    // Esconde a tela inicial preta
    telaInicial.style.display = "none";

    // Mostra o menu com a imagem de fundo
    menuPrincipal.style.display = "flex";

    // Inicia a música em loop
    musicaMenu.currentTime = 0;

    musicaMenu.play().catch(erro => {
        console.error("Não foi possível tocar a música:", erro);
    });

});
</script>
</body>
</html>