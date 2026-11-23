<?php
require_once __DIR__ . '/../src/controllers/verificar_login.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Batalha</title>
    
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #171717;
    color: white;
    font-family: Arial, sans-serif;
}

.container-batalha {
    width: 90%;
    max-width: 800px;
    text-align: center;
}

h1 {
    margin-bottom: 40px;
}

.opcoes-batalha {
    display: flex;
    justify-content: center;
}

.opcao {
    width: 100%;
    max-width: 400px;
    padding: 30px;
    border: 2px solid #f1c40f;
    border-radius: 15px;
    background-color: #292929;
}

.opcao h2 {
    margin-bottom: 20px;
}

.opcao p {
    margin-bottom: 25px;
    line-height: 1.5;
}

button {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    background-color: #f1c40f;
    color: #171717;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background-color: #d4ac0d;
}

.botao-voltar {
    margin-top: 30px;
    background-color: #555;
    color: white;
}

.botao-voltar:hover {
    background-color: #777;
}
    </style>

</head>
<body>

    <main class="container-batalha">
        <h1>Escolha sua batalha</h1>

        <section class="opcoes-batalha">
            <div class="opcao">
                <h2>Batalha Padrão</h2>

                <p>
                    Enfrente uma equipe fixa de seis Pokebiers!
                    Prepare sua equipe e mostre sua força!
                </p>

                <button onclick="location.href='batalha.php'">
                    Jogar
                </button>
            </div>
        </section>

        <button class="botao-voltar" onclick="history.back()">
            Voltar
        </button>
    </main>

</body>
</html>