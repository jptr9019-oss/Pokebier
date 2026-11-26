<?php
require_once __DIR__ . '/../src/controllers/verificar_login.php';
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../autoload.php';

$pdo = getConnection();

$idUsuario = $_SESSION['usuario_id'];

// Botão de debug: adiciona dinheiro ao usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'debug_dinheiro') {

    header('Content-Type: application/json; charset=utf-8');

    try {
        $sucesso = Usuario::debugGanhaDinheiro($idUsuario, 3000);

        if (!$sucesso) {
            throw new Exception('Não foi possível adicionar o dinheiro.');
        }

        // Busca o saldo atualizado
        $stmt = $pdo->prepare("
            SELECT dinheiro
            FROM Usuario
            WHERE id_usuario = :id_usuario
        ");

        $stmt->execute([':id_usuario' => $idUsuario]);
        $novoDinheiro = $stmt->fetchColumn();

        if ($novoDinheiro === false) {
            throw new Exception('Usuário não encontrado.');
        }

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Você recebeu 3000 moedas!',
            'dinheiro' => (int) $novoDinheiro
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => $e->getMessage()
        ]);
    }

    exit;
}


// Processa a compra de um item de cura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'comprar_cura') {

    header('Content-Type: application/json; charset=utf-8');

    $idItem = filter_input(INPUT_POST, 'id_itens', FILTER_VALIDATE_INT);

    if (!$idItem) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Item inválido.'
        ]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Busca e bloqueia o usuário durante a compra
        $stmt = $pdo->prepare("
            SELECT dinheiro, id_mochi
            FROM Usuario
            WHERE id_usuario = :id_usuario
            FOR UPDATE
        ");

        $stmt->execute([':id_usuario' => $idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            throw new Exception('Usuário não encontrado.');
        }

        // Confere se o item é de cura e busca seu preço
        $stmt = $pdo->prepare("
            SELECT i.preco
            FROM Itens i
            INNER JOIN Itens_cura ic
                ON i.id_it_cura = ic.id_it_cura
            WHERE i.id_itens = :id_item
        ");

        $stmt->execute([':id_item' => $idItem]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new Exception('Esse não é um item de cura.');
        }

        $preco = (int) $item['preco'];
        $dinheiro = (int) $usuario['dinheiro'];

        // Verifica se o jogador tem dinheiro suficiente
        if ($dinheiro < $preco) {
            throw new Exception('Você não tem moedas suficientes!');
        }

        // Busca e bloqueia a mochila
        $stmt = $pdo->prepare("
            SELECT *
            FROM Mochila
            WHERE id_mochi = :id_mochi
            FOR UPDATE
        ");

        $stmt->execute([':id_mochi' => $usuario['id_mochi']]);
        $mochila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mochila) {
            throw new Exception('Mochila não encontrada.');
        }

        // Procura o primeiro espaço vazio
        $colunas = [
            'id_itens',
            'id_itens_2',
            'id_itens_3',
            'id_itens_4',
            'id_itens_5'
        ];

        $colunaVazia = null;

        foreach ($colunas as $coluna) {
            if (
                $mochila[$coluna] === null ||
                (int) $mochila[$coluna] === 0
            ) {
                $colunaVazia = $coluna;
                break;
            }
        }

        if ($colunaVazia === null) {
            throw new Exception('Sua mochila está cheia!');
        }

        // Coloca o item no primeiro espaço disponível
        $stmt = $pdo->prepare("
            UPDATE Mochila
            SET $colunaVazia = :id_item
            WHERE id_mochi = :id_mochi
        ");

        $stmt->execute([
            ':id_item' => $idItem,
            ':id_mochi' => $usuario['id_mochi']
        ]);

        // Desconta o preço do item
        $stmt = $pdo->prepare("
            UPDATE Usuario
            SET dinheiro = dinheiro - :preco
            WHERE id_usuario = :id_usuario
        ");

        $stmt->execute([
            ':preco' => $preco,
            ':id_usuario' => $idUsuario
        ]);

        $novoDinheiro = $dinheiro - $preco;

        $pdo->commit();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Item de cura comprado com sucesso!',
            'dinheiro' => $novoDinheiro
        ]);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo json_encode([
            'sucesso' => false,
            'mensagem' => $e->getMessage()
        ]);
    }

    exit;
}



// Processa a compra de um item de evolução
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'comprar_evolucao') {

    header('Content-Type: application/json; charset=utf-8');

    $idItem = filter_input(INPUT_POST, 'id_itens', FILTER_VALIDATE_INT);

    if (!$idItem) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Item inválido.'
        ]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Bloqueia o registro do usuário durante a compra
        $stmt = $pdo->prepare("
            SELECT dinheiro
            FROM Usuario
            WHERE id_usuario = :id_usuario
            FOR UPDATE
        ");

        $stmt->execute([':id_usuario' => $idUsuario]);
        $dinheiro = $stmt->fetchColumn();

        if ($dinheiro === false) {
            throw new Exception('Usuário não encontrado.');
        }

        // Busca o preço e o Pokebier que será desbloqueado
        $stmt = $pdo->prepare("
            SELECT i.preco, ie.id_pb_evo
            FROM Itens i
            INNER JOIN Itens_evos ie
                ON i.id_it_evo = ie.id_it_evo
            WHERE i.id_itens = :id_item
        ");

        $stmt->execute([':id_item' => $idItem]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new Exception('Esse item não é um item de evolução.');
        }

        $idPbEvo = $item['id_pb_evo'];
        $preco = (int) $item['preco'];

        // Verifica se o Pokebier já está desbloqueado
        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM p_u
            WHERE id_usuario = :id_usuario
              AND id_pb = :id_pb
        ");

        $stmt->execute([
            ':id_usuario' => $idUsuario,
            ':id_pb' => $idPbEvo
        ]);

        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Você já desbloqueou esse Pokebier!');
        }

        // Verifica se o jogador tem dinheiro suficiente
        if ($dinheiro < $preco) {
            throw new Exception('Você não tem moedas suficientes!');
        }

        // Registra o Pokebier evoluído como desbloqueado
        $stmt = $pdo->prepare("
            INSERT INTO p_u (id_usuario, id_pb)
            VALUES (:id_usuario, :id_pb)
        ");

        $stmt->execute([
            ':id_usuario' => $idUsuario,
            ':id_pb' => $idPbEvo
        ]);

        // Desconta o preço do item
        $stmt = $pdo->prepare("
            UPDATE Usuario
            SET dinheiro = dinheiro - :preco
            WHERE id_usuario = :id_usuario
        ");

        $stmt->execute([
            ':preco' => $preco,
            ':id_usuario' => $idUsuario
        ]);

        $novoDinheiro = $dinheiro - $preco;

        $pdo->commit();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Pokebier desbloqueado com sucesso!',
            'dinheiro' => $novoDinheiro
        ]);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo json_encode([
            'sucesso' => false,
            'mensagem' => $e->getMessage()
        ]);
    }

    exit;
}


// Busca o dinheiro do usuário
$stmt = $pdo->prepare(
    "SELECT dinheiro FROM Usuario WHERE id_usuario = :id_usuario"
);
$stmt->execute([':id_usuario' => $idUsuario]);

$dinheiro = $stmt->fetchColumn();

// Busca os itens, colocando os de cura primeiro
$stmt = $pdo->query("
    SELECT
        i.id_itens,
        i.preco,
        i.imagem,
        ic.nome AS nome_cura,
        ic.qnt_cura_hp,
        ie.nome AS nome_evo,
        ie.id_pb,
        ie.id_pb_evo
    FROM Itens i
    LEFT JOIN Itens_cura ic ON i.id_it_cura = ic.id_it_cura
    LEFT JOIN Itens_evos ie ON i.id_it_evo = ie.id_it_evo
    ORDER BY
        CASE WHEN i.id_it_cura IS NOT NULL THEN 0 ELSE 1 END,
        i.id_itens
");

$itens = $stmt->fetchAll();
?>

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

#voltar-menu {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1000;

    padding: 12px 20px;
    background-color: #222;
    color: white;
    border: 2px solid white;
    border-radius: 10px;

    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

#voltar-menu:hover {
    background-color: #555;
    transform: scale(1.05);
}

/* Título */
#titulo-loja {
    text-align: center;
    font-size: 36px;
    margin: 15px 0 40px;
    text-shadow: 3px 3px 5px black;
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

#saldo-jogador {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
    padding: 12px 20px;
    background: rgba(0, 0, 0, 0.9);
    color: #ffd700;
    border: 2px solid white;
    border-radius: 10px;
    font-size: 18px;
    font-weight: bold;
}

#botao-debug {
    position: fixed;
    bottom: 85px;
    left: 20px;
    z-index: 1000;

    padding: 12px 20px;
    background: #6b238e;
    color: white;
    border: 2px solid white;
    border-radius: 10px;

    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

#botao-debug:hover {
    background: #9135b8;
    transform: scale(1.05);
}

#botao-debug:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

<button id="voltar-menu" onclick="window.location.href='pokebier_menu.php'">
    ← Voltar ao menu
</button>

    <h1 id="titulo-loja">POKEBIER - LOJA</h1>


    <div id="itens-loja">


        <div id="itens-loja">

    <?php foreach ($itens as $item): ?>

        <?php
        // Verifica se o item é de cura ou de evolução
        $ehCura = !empty($item['nome_cura']);

        if ($ehCura) {
    $nome = $item['nome_cura'];

    // Descrições dos itens de cura
    switch ($item['id_itens']) {
        case 11:
            $descricao = "Cura 25 de HP na batalha.";
            break;

        case 12:
            $descricao = "Cura 50 de HP na batalha.";
            break;

        case 13:
            $descricao = "Cura 75 de HP na batalha.";
            break;

        case 14:
            $descricao = "Cura 100 de HP na batalha.";
            break;

        case 15:
            $descricao = "Cura 50 de HP e levanta um Pokebier derrotado na batalha (se for usado em um Pokebier que não foi derrotado, só cura 50 de HP).";
            break;


        default:
            $descricao = "Item de cura.";
    }

} else {
    $nome = $item['nome_evo'];

    // Descrições dos itens de evolução
    switch ($item['id_itens']) {
        case 1:
            $descricao = "Desbloqueia a evolução do Spamton.";
            break;

        case 2:
            $descricao = "Desbloqueia a evolução do Flowery.";
            break;

        case 3:
            $descricao = "Desbloqueia a evolução do Barriguinha Mole.";
            break;

         case 4:
            $descricao = "Desbloqueia a evolução do Dark Magician.";
            break;

        case 5:
            $descricao = "Desbloqueia a evolução da Mesmer.";
            break;

        case 6:
            $descricao = "Desbloqueia a evolução do Chiquinho Gavião.";
            break;

        case 7:
            $descricao = "Desbloqueia a evolução do Verity.";
            break;

        case 8:
            $descricao = "Desbloqueia a evolução do Inspetor Shepherd.";
            break;

        case 9:
            $descricao = "Desbloqueia a evolução do Giorno Giovanna.";
            break;

        case 10:
            $descricao = "Desbloqueia a evolução do Eric.";
            break;

        default:
            $descricao = "Item de evolução.";
    }
}
        ?>

        <div class="item">

            <img
                src="<?= htmlspecialchars($item['imagem']) ?>"
                alt="<?= htmlspecialchars($nome) ?>"
                class="imagem-item"
            >

            <h3><?= htmlspecialchars($nome) ?></h3>

            <div class="descricao">
                <?= htmlspecialchars($descricao) ?>
            </div>

            <p>
    <?= htmlspecialchars((string) $item['preco']) ?>

    <?= $item['preco'] == 1 ? 'moeda' : 'moedas' ?>
</p>

            <button
    class="botao-comprar"
    data-item="<?= (int) $item['id_itens'] ?>"
    data-tipo="<?= $ehCura ? 'cura' : 'evolucao' ?>"
>
    Comprar
</button>

        </div>

    <?php endforeach; ?>

</div>

    </div>
    <div id="saldo-jogador">
    Dinheiro: <?= htmlspecialchars((string) $dinheiro) ?>
    <?= $dinheiro == 1 ? 'moeda' : 'moedas' ?>
</div>

<button id="botao-debug">
    DEBUG: +3000 moedas
</button>

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

    botao.addEventListener("click", async () => {

        const idItem = botao.dataset.item;

        botao.disabled = true;

        try {
            const resposta = await fetch(window.location.href, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
    acao: botao.dataset.tipo === "cura"
        ? "comprar_cura"
        : "comprar_evolucao",

    id_itens: idItem
})
            });

            const resultado = await resposta.json();

            alert(resultado.mensagem);

            if (resultado.sucesso) {

                // Atualiza o saldo na página
                const saldo = document.querySelector("#saldo-jogador");

                if (saldo) {
                    saldo.textContent =
                        "Dinheiro: " + resultado.dinheiro +
                        (resultado.dinheiro == 1 ? " moeda" : " moedas");
                }
            }

        } catch (erro) {
            alert("Não foi possível realizar a compra. Tente novamente.");
            console.error(erro);

        } finally {
            botao.disabled = false;
        }

    });

});

// Botão de debug para adicionar dinheiro
const botaoDebug = document.getElementById("botao-debug");

botaoDebug.addEventListener("click", async () => {

    botaoDebug.disabled = true;

    try {
        const resposta = await fetch(window.location.href, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                acao: "debug_dinheiro"
            })
        });

        const resultado = await resposta.json();

        alert(resultado.mensagem);

        if (resultado.sucesso) {
            const saldo = document.querySelector("#saldo-jogador");

            saldo.textContent =
                "Dinheiro: " + resultado.dinheiro +
                (resultado.dinheiro == 1 ? " moeda" : " moedas");
        }

    } catch (erro) {
        alert("Não foi possível adicionar o dinheiro.");
        console.error(erro);

    } finally {
        botaoDebug.disabled = false;
    }
});

</script>


</body>
</html>