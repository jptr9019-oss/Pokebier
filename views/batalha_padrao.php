
<?php
require_once __DIR__ . '/../autoload.php';

// Carrega explicitamente a classe Batalha antes de recuperar a sessão.
require_once __DIR__ . '/../src/Models/Batalha.php';

// Depois de carregar as classes, verifica o login.
require_once __DIR__ . '/../src/controllers/verificar_login.php';

require_once __DIR__ . '/../conexao.php';

$pdo = getConnection();

// Se o jogador entrou pelo menu para começar uma nova batalha,
// apaga a batalha anterior.
if (isset($_GET['nova']) && $_GET['nova'] === '1') {
    unset($_SESSION['batalha_padrao']);

    unset($_SESSION['recompensa_batalha_paga']);

    header('Location: batalha_padrao.php?reiniciar_musica=1');
    exit;
}

// --------------------------------------------------
// FUNÇÕES AUXILIARES
// --------------------------------------------------

// Retorna os ataques que o Pokebier realmente possui.
function obterAtaquesDisponiveis($pokemon) {
    $ataques = [
        1 => $pokemon->getAtk1(),
        2 => $pokemon->getAtk2(),
        3 => $pokemon->getAtk3(),
        4 => $pokemon->getAtk4(),
        5 => $pokemon->getAtkSpecial()
    ];

    $disponiveis = [];

    foreach ($ataques as $numero => $idAtaque) {
        if (!empty($idAtaque)) {
            $dadosAtaque = Ataques::getOne($idAtaque);

            if ($dadosAtaque) {
                $disponiveis[$numero] = $dadosAtaque;
            }
        }
    }

    return $disponiveis;
}

// Monta o caminho da imagem.
function caminhoImagemPokemon($imagem) {
    if (empty($imagem)) {
        return '';
    }

    if (
        str_starts_with($imagem, 'http://') ||
        str_starts_with($imagem, 'https://') ||
        str_starts_with($imagem, '../') ||
        str_starts_with($imagem, './') ||
        str_starts_with($imagem, '/')
    ) {
        return $imagem;
    }

    if (str_starts_with($imagem, 'public/')) {
        return '../' . $imagem;
    }

    if (str_starts_with($imagem, 'assets/')) {
        return '../public/' . $imagem;
    }

    return '../public/assets/imagens/' . $imagem;
}

// Carrega os itens de cura que estão na mochila do jogador.
function carregarItensMochila($pdo, $idUsuario) {

    $stmt = $pdo->prepare("
        SELECT m.*
        FROM Mochila m
        INNER JOIN Usuario u
            ON u.id_mochi = m.id_mochi
        WHERE u.id_usuario = :id_usuario
    ");

    $stmt->execute([
        ':id_usuario' => $idUsuario
    ]);

    $mochila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mochila) {
        return [];
    }

    // Relação entre o número do espaço e a coluna do banco.
    $colunas = [
        1 => 'id_itens',
        2 => 'id_itens_2',
        3 => 'id_itens_3',
        4 => 'id_itens_4',
        5 => 'id_itens_5'
    ];

    $itens = [];

    foreach ($colunas as $numeroSlot => $coluna) {

        $idItem = (int) ($mochila[$coluna] ?? 0);

        if ($idItem <= 0) {
            continue;
        }

        $stmtItem = $pdo->prepare("
            SELECT
                i.id_itens,
                ic.nome,
                ic.qnt_cura_hp,
                ic.revive
            FROM Itens i
            INNER JOIN Itens_cura ic
                ON i.id_it_cura = ic.id_it_cura
            WHERE i.id_itens = :id_item
        ");

        $stmtItem->execute([
            ':id_item' => $idItem
        ]);

        $dadosItem = $stmtItem->fetch(PDO::FETCH_ASSOC);

        if ($dadosItem) {
            $dadosItem['slot'] = $numeroSlot;
            $itens[] = $dadosItem;
        }
    }

    return $itens;
}

// --------------------------------------------------
// CARREGAMENTO DAS EQUIPES
// --------------------------------------------------

$idUsuario = $_SESSION['usuario_id'];

// Busca a equipe do jogador.
$equipeDados = Equipe::getByUsuario($idUsuario);

if (!$equipeDados) {
    die('Não foi possível encontrar sua equipe.');
}

// Obtém os seis IDs da equipe do jogador.
$idsJogador = [
    $equipeDados->getIdPokemon1(),
    $equipeDados->getIdPokemon2(),
    $equipeDados->getIdPokemon3(),
    $equipeDados->getIdPokemon4(),
    $equipeDados->getIdPokemon5(),
    $equipeDados->getIdPokemon6()
];

// Equipe fixa da IA.
$idsIA = [1, 2, 3, 4, 5, 6];

// Transforma os IDs em objetos Pokemon.
function carregarEquipe($ids) {
    $equipe = [];

    foreach ($ids as $id) {
        if (!empty($id)) {
            $pokemon = Pokemon::getOne($id);

            if ($pokemon) {
                $equipe[] = $pokemon;
            }
        }
    }

    return $equipe;
}

// --------------------------------------------------
// INICIALIZAÇÃO DA BATALHA
// --------------------------------------------------

// Permite começar uma nova batalha.
if (isset($_POST['acao']) && $_POST['acao'] === 'reiniciar') {
    unset($_SESSION['batalha_padrao']);
    unset($_SESSION['recompensa_batalha_paga']);

    header('Location: batalha_padrao.php');
    exit;
}

// Se ainda não existe uma batalha na sessão, cria uma.
if (!isset($_SESSION['batalha_padrao'])) {

    $equipeJogador = carregarEquipe($idsJogador);
    $equipeIA = carregarEquipe($idsIA);

    if (empty($equipeJogador) || empty($equipeIA)) {
        die('Uma das equipes não possui Pokebiers válidos.');
    }

    $_SESSION['batalha_padrao'] = new Batalha(
        $equipeJogador,
        $equipeIA
    );
}

$batalha = $_SESSION['batalha_padrao'];

// --------------------------------------------------
// PROCESSAMENTO DO ATAQUE DO JOGADOR
// --------------------------------------------------

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'atacar'
) {
    if (!$batalha->isEncerrada()) {

        $pokemonJogador = $batalha->getPokebierJogador();
        $pokemonIA = $batalha->getPokebierIA();

        $numeroAtaque = (int) ($_POST['ataque'] ?? 0);

        $ataquesJogador = obterAtaquesDisponiveis($pokemonJogador);
        $ataquesIA = obterAtaquesDisponiveis($pokemonIA);

        // Confere se o ataque escolhido existe.
        if (
            isset($ataquesJogador[$numeroAtaque]) &&
            !empty($ataquesIA)
        ) {
            // A IA escolhe aleatoriamente um ataque disponível.
            $numeroAtaqueIA = array_rand($ataquesIA);

            // Executa o turno.
            $batalha->executarTurno(
                $numeroAtaque,
                $numeroAtaqueIA
            );

            $_SESSION['batalha_padrao'] = $batalha;
        }
    }

    // Evita repetir o ataque ao atualizar a página.
    header('Location: batalha_padrao.php');
    exit;
}

// --------------------------------------------------
// PROCESSAMENTO DA TROCA DE POKEBIER
// --------------------------------------------------

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'trocar'
) {
    if (!$batalha->isEncerrada()) {

        // Índice do Pokebier escolhido pelo jogador.
        $indice = (int) ($_POST['indice'] ?? -1);

        // Obtém os ataques disponíveis para a IA.
        $pokemonIA = $batalha->getPokebierIA();
        $ataquesIA = obterAtaquesDisponiveis($pokemonIA);

        if (!empty($ataquesIA)) {

            // A IA escolhe um ataque aleatório.
            $numeroAtaqueIA = array_rand($ataquesIA);

            // Realiza a troca e o ataque da IA.
            $batalha->executarTurnoTroca(
                $indice,
                $numeroAtaqueIA
            );

            $_SESSION['batalha_padrao'] = $batalha;
        }
    }

    // Evita repetir a troca ao atualizar a página.
    header('Location: batalha_padrao.php');
    exit;
}

// --------------------------------------------------
// RECOMPENSA DA BATALHA
// --------------------------------------------------

if (
    $batalha->isEncerrada() &&
    empty($_SESSION['recompensa_batalha_paga'])
) {
    if ($batalha->getVencedor() === 'jogador') {
        $recompensa = 50;
    } else {
        $recompensa = 25;
    }

    if (Usuario::ganhaDinheiro($idUsuario, $recompensa)) {
        $_SESSION['recompensa_batalha_paga'] = true;
    }
}

// --------------------------------------------------
// PROCESSAMENTO DO USO DE ITEM
// --------------------------------------------------

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'usar_item'
) {

    if (!$batalha->isEncerrada()) {

        $slot = (int) ($_POST['slot'] ?? 0);
        $indicePokemon = (int) ($_POST['indice_pokemon'] ?? -1);

        // Colunas permitidas da mochila.
        $colunasMochila = [
            1 => 'id_itens',
            2 => 'id_itens_2',
            3 => 'id_itens_3',
            4 => 'id_itens_4',
            5 => 'id_itens_5'
        ];

        try {

            if (!isset($colunasMochila[$slot])) {
                throw new Exception('Espaço da mochila inválido.');
            }

            $equipe = $batalha->getEquipeJogador();

            if (!isset($equipe[$indicePokemon])) {
                throw new Exception('Pokebier selecionado inválido.');
            }

            $pokemonAlvo = $equipe[$indicePokemon];

            // Confere se a IA possui algum ataque disponível.
            $pokemonIA = $batalha->getPokebierIA();
            $ataquesIA = obterAtaquesDisponiveis($pokemonIA);

            if (empty($ataquesIA)) {
                throw new Exception('A IA não possui ataques disponíveis.');
            }

            $pdo->beginTransaction();

            // Busca e bloqueia a mochila do jogador.
            $stmt = $pdo->prepare("
                SELECT m.*
                FROM Mochila m
                INNER JOIN Usuario u
                    ON u.id_mochi = m.id_mochi
                WHERE u.id_usuario = :id_usuario
                FOR UPDATE
            ");

            $stmt->execute([
                ':id_usuario' => $idUsuario
            ]);

            $mochila = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mochila) {
                throw new Exception('Mochila não encontrada.');
            }

            $coluna = $colunasMochila[$slot];
            $idItem = (int) ($mochila[$coluna] ?? 0);

            if ($idItem <= 0) {
                throw new Exception('Esse espaço da mochila está vazio.');
            }

            // Busca os dados do item.
            $stmt = $pdo->prepare("
                SELECT
                    i.id_itens,
                    ic.nome,
                    ic.qnt_cura_hp,
                    ic.revive
                FROM Itens i
                INNER JOIN Itens_cura ic
                    ON i.id_it_cura = ic.id_it_cura
                WHERE i.id_itens = :id_item
            ");

            $stmt->execute([
                ':id_item' => $idItem
            ]);

            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$item) {
                throw new Exception('Item de cura não encontrado.');
            }

            $quantidadeCura = (int) $item['qnt_cura_hp'];

            // Aceita diferentes formatos de valor verdadeiro no banco.
            $valorRevive = strtolower(trim((string) $item['revive']));

            $podeReviver = in_array(
                $valorRevive,
                ['1', 'sim', 's', 'true', 'yes'],
                true
            );

            $hpAtual = (int) $pokemonAlvo->getHp();
            $hpMaximo = (int) $pokemonAlvo->getMaxHp();

            // Não permite usar item comum em Pokebier derrotado.
            if ($hpAtual <= 0 && !$podeReviver) {
                throw new Exception(
                    'Esse item não pode levantar um Pokebier derrotado!'
                );
            }

            // Não permite desperdiçar item em Pokebier com HP máximo.
            if ($hpAtual > 0 && $hpAtual >= $hpMaximo) {
                throw new Exception(
                    'Esse Pokebier já está com o HP máximo!'
                );
            }

            if ($hpAtual <= 0 && $podeReviver) {

                // Revive com 50 HP.
                $pokemonAlvo->setHp(50);

                $mensagem = $pokemonAlvo->getNome()
                    . ' levantou com '
                    . $pokemonAlvo->getHp()
                    . ' HP!';

            } else {

                // Cura usando a quantidade cadastrada no banco.
                if ($quantidadeCura <= 0) {
                    throw new Exception('A quantidade de cura do item é inválida.');
                }

                $pokemonAlvo->setHp(
                    $hpAtual + $quantidadeCura
                );

                $mensagem = $pokemonAlvo->getNome()
                    . ' recuperou '
                    . ($pokemonAlvo->getHp() - $hpAtual)
                    . ' HP!';
            }

            // Remove o item da mochila.
            $stmt = $pdo->prepare("
                UPDATE Mochila
                SET $coluna = NULL
                WHERE id_mochi = :id_mochi
            ");

            $stmt->execute([
                ':id_mochi' => $mochila['id_mochi']
            ]);

            // A IA escolhe um ataque aleatório.
            $numeroAtaqueIA = array_rand($ataquesIA);

            // A IA realiza seu ataque após o uso do item.
            if (!$batalha->executarTurnoItem($numeroAtaqueIA)) {
                throw new Exception('Não foi possível finalizar o turno.');
            }

            $pdo->commit();

            $_SESSION['batalha_padrao'] = $batalha;

            $_SESSION['mensagem_batalha'] =
                $mensagem . ' O oponente realizou seu ataque!';

        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $_SESSION['mensagem_batalha'] = $e->getMessage();
        }
    }

    // Evita repetir o uso do item ao atualizar a página.
    header('Location: batalha_padrao.php?tela=mochila');
    exit;
}

// --------------------------------------------------
// DADOS PARA EXIBIR NA TELA
// --------------------------------------------------

$pokemonJogador = $batalha->getPokebierJogador();
$pokemonIA = $batalha->getPokebierIA();

$encerrada = $batalha->isEncerrada();
$vencedor = $batalha->getVencedor();

$ataquesJogador = $pokemonJogador
    ? obterAtaquesDisponiveis($pokemonJogador)
    : [];

$equipeJogadorTela = $batalha->getEquipeJogador();

// Itens disponíveis na mochila.
$itensMochila = carregarItensMochila($pdo, $idUsuario);

// Mensagem de retorno de uma ação da batalha.
$mensagemBatalha = $_SESSION['mensagem_batalha'] ?? '';
unset($_SESSION['mensagem_batalha']);

$imagemJogador = $pokemonJogador
    ? caminhoImagemPokemon($pokemonJogador->getEndImagem())
    : '';

$imagemIA = $pokemonIA
    ? caminhoImagemPokemon($pokemonIA->getEndImagem())
    : '';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Batalha Padrão - Pokebier</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-image: url('../public/assets/imagens/plant_border.png');
            background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1100px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .campo-batalha {
    position: relative;
    width: 100%;
    height: 480px;
    overflow: hidden;
    border: 4px solid #21124A;
    border-radius: 15px;

    background-image: url('../public/assets/imagens/battle_background.gif');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.base-pokemon {
    position: absolute;
    width: 280px;
    height: 100px;
    object-fit: contain;
    z-index: 1;
}

.base-ia {
    top: 230px;
    right: 100px;
}

.base-jogador {
    bottom: 30px;
    left: 100px;
}

.imagem-pokemon {
    z-index: 2;
}

        .informacoes {
            position: absolute;
            width: 280px;
            padding: 15px;
            background: rgba(0, 0, 0, 0.85);
            border: 2px solid white;
            border-radius: 10px;
            color: white;
            z-index: 2;
        }

        .informacoes h2 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .informacoes p {
            margin-bottom: 8px;
        }

        .info-ia {
            bottom: 20px;
            right: 20px;
        }

        .info-jogador {
            top: 20px;
            left: 20px;
        }

        .imagem-pokemon {
            position: absolute;
            width: 260px;
            height: 260px;
            object-fit: contain;
            image-rendering: pixelated;
            z-index: 1;
        }

        .pokemon-ia {
            top: 40px;
            right: 110px;
        }

        .pokemon-jogador {
            bottom: 65px;
            left: 110px;
        }

        .barra-hp {
            width: 100%;
            height: 13px;
            background: #555;
            border: 1px solid white;
            border-radius: 10px;
            overflow: hidden;
        }

        .hp-atual {
            height: 100%;
            background: #38c95a;
            transition: width 0.3s;
        }

        .area-comandos {
            margin-top: 20px;
            padding: 20px;
            background: #292929;
            border: 2px solid #21124A;
            border-radius: 12px;
        }

        .area-comandos h2 {
            margin-bottom: 15px;
        }

        .botoes-ataques {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .botao-ataque {
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: white;
            color: #171717;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .botao-ataque:hover {
            background: #d4ac0d;
        }

        .botoes-menu {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.botao-menu-batalha {
    padding: 18px;
    border: 2px solid white;
    border-radius: 8px;
    background: #21124A;
    color: white;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

.botao-menu-batalha:hover {
    background: #49318a;
}

.lista-pokebiers {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.cartao-pokebier {
    padding: 15px;
    background: #171717;
    border: 2px solid #555;
    border-radius: 8px;
}

.cartao-pokebier p {
    margin-bottom: 8px;
}

.botao-trocar {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    border: none;
    border-radius: 6px;
    background: white;
    color: #171717;
    font-weight: bold;
    cursor: pointer;
}

.botao-trocar:disabled {
    background: #555;
    color: #aaa;
    cursor: not-allowed;
}

.seletor-pokebier {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    margin-bottom: 8px;
    border: 1px solid white;
    border-radius: 6px;
    background: #292929;
    color: white;
    font-size: 15px;
}

.botao-voltar {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    background: #555;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

@media (max-width: 650px) {
    .botoes-menu,
    .lista-pokebiers {
        grid-template-columns: 1fr;
    }
}

        .resultado {
            text-align: center;
            padding: 25px;
            background: #292929;
            border: 2px solid #f1c40f;
            border-radius: 10px;
        }

        .resultado h2 {
            margin-bottom: 15px;
        }

        .botao-reiniciar,
        .botao-menu {
            display: inline-block;
            margin: 10px 5px 0;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #f1c40f;
            color: #171717;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .botao-menu {
            background: #555;
            color: white;
        }

        .turno {
            text-align: center;
            margin-bottom: 15px;
            font-size: 18px;
        }

    #tela-iniciar-batalha {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999999;
}

#btn-iniciar-batalha {
    padding: 18px 35px;
    font-size: 22px;
    cursor: pointer;
    border: 3px solid white;
    border-radius: 10px;
    background: #222;
    color: white;
}

#btn-iniciar-batalha:hover {
    background: #444;
}
        @media (max-width: 650px) {
            .campo-batalha {
                height: 400px;
            }

            .imagem-pokemon {
                width: 170px;
                height: 170px;
            }

            .pokemon-ia {
                top: 100px;
                right: 10px;
            }

            .pokemon-jogador {
                bottom: 100px;
                left: 10px;
            }

            .informacoes {
                width: 210px;
                padding: 10px;
            }

            .informacoes h2 {
                font-size: 16px;
            }

            .botoes-ataques {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<audio id="musica-batalha" loop>
    <source src="../public/assets/imagens/ruder_buster.mp3" type="audio/mpeg">
</audio>

<div id="tela-iniciar-batalha">
    <button id="btn-iniciar-batalha">
        INICIAR BATALHA
    </button>
</div>

<main class="container">
    
    <p class="turno">
        Turno <?= (int) $batalha->getTurno() ?>
    </p>

    <section class="campo-batalha">

     <img src="../public/assets/imagens/base_batalha.png"
         class="base-pokemon base-ia">

    <img src="../public/assets/imagens/base_batalha.png"
         class="base-pokemon base-jogador">

        <!-- Informações da IA -->
        <?php if ($pokemonIA): ?>
            <div class="informacoes info-ia">

                <h2>
                    <?= htmlspecialchars($pokemonIA->getNome()) ?>
                </h2>

                <p>
                    HP:
                    <?= (int) $pokemonIA->getHp() ?> /
                    <?= (int) $pokemonIA->getMaxHp() ?>
                </p>

                <div class="barra-hp">
                    <div
                        class="hp-atual"
                        style="width: <?= $pokemonIA->getMaxHp() > 0
                            ? max(0, min(100, ($pokemonIA->getHp() / $pokemonIA->getMaxHp()) * 100))
                            : 0 ?>%">
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <!-- Imagem da IA -->
        <?php if ($imagemIA): ?>
            <img
                src="<?= htmlspecialchars($imagemIA) ?>"
                class="imagem-pokemon pokemon-ia"
                alt="<?= htmlspecialchars($pokemonIA->getNome()) ?>"
            >
        <?php endif; ?>


        <!-- Informações do jogador -->
        <?php if ($pokemonJogador): ?>
            <div class="informacoes info-jogador">

                <h2>
                    <?= htmlspecialchars($pokemonJogador->getNome()) ?>
                </h2>

                <p>
                    HP:
                    <?= (int) $pokemonJogador->getHp() ?> /
                    <?= (int) $pokemonJogador->getMaxHp() ?>
                </p>

                <div class="barra-hp">
                    <div
                        class="hp-atual"
                        style="width: <?= $pokemonJogador->getMaxHp() > 0
                            ? max(0, min(100, ($pokemonJogador->getHp() / $pokemonJogador->getMaxHp()) * 100))
                            : 0 ?>%">
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <!-- Imagem do jogador -->
        <?php if ($imagemJogador): ?>
            <img
                src="<?= htmlspecialchars($imagemJogador) ?>"
                class="imagem-pokemon pokemon-jogador"
                alt="<?= htmlspecialchars($pokemonJogador->getNome()) ?>"
            >
        <?php endif; ?>

    </section>


    <!-- Comandos da batalha -->
    <!-- Comandos da batalha -->
<section class="area-comandos">

    <?php if (!$encerrada): ?>

    <?php if (!empty($mensagemBatalha)): ?>

    <p style="
        margin-bottom: 15px;
        padding: 12px;
        background: #171717;
        border: 1px solid white;
        border-radius: 8px;
    ">
        <?= htmlspecialchars($mensagemBatalha) ?>
    </p>

<?php endif; ?>

        <?php
        $tela = $_GET['tela'] ?? 'menu';
        ?>

        <?php if ($tela === 'menu'): ?>

            <h2>
                O que <?= htmlspecialchars($pokemonJogador->getNome()) ?> fará?
            </h2>

            <div class="botoes-menu">

                <a href="batalha_padrao.php?tela=atacar">
                    <button type="button" class="botao-menu-batalha">
                        Atacar
                    </button>
                </a>

                <a href="batalha_padrao.php?tela=mochila">
                    <button type="button" class="botao-menu-batalha">
                        Mochila
                    </button>
                </a>

                <a href="batalha_padrao.php?tela=trocar">
                    <button type="button" class="botao-menu-batalha">
                        Trocar Pokebier
                    </button>
                </a>

            </div>

        <?php elseif ($tela === 'atacar'): ?>

            <h2>Escolha um ataque:</h2>

            <div class="botoes-ataques">

                <?php foreach ($ataquesJogador as $numero => $ataque): ?>

                    <form method="POST">

                        <input type="hidden" name="acao" value="atacar">

                        <input
                            type="hidden"
                            name="ataque"
                            value="<?= (int) $numero ?>"
                        >

                        <button type="submit" class="botao-ataque">
                            <?= htmlspecialchars($ataque->getNome()) ?>
                        </button>

                    </form>

                <?php endforeach; ?>

            </div>

            <a href="batalha_padrao.php" class="botao-voltar">
                Voltar
            </a>

        <?php elseif ($tela === 'trocar'): ?>

            <h2>Escolha um Pokebier para trocar:</h2>

            <div class="lista-pokebiers">

                <?php foreach ($equipeJogadorTela as $indice => $pokebier): ?>

                    <?php
                    $hpAtual = $pokebier->getHp();
                    $hpMaximo = $pokebier->getMaxHp();

                    $estaAtivo = ($pokebier === $pokemonJogador);
                    $estaDerrotado = ($hpAtual <= 0);
                    ?>

                    <div class="cartao-pokebier">

                        <h3>
                            <?= htmlspecialchars($pokebier->getNome()) ?>
                        </h3>

                        <p>
                            HP: <?= (int) $hpAtual ?> /
                            <?= (int) $hpMaximo ?>
                        </p>

                        <div class="barra-hp">
                            <div
                                class="hp-atual"
                                style="width: <?= $hpMaximo > 0
                                    ? max(0, min(100, ($hpAtual / $hpMaximo) * 100))
                                    : 0 ?>%">
                            </div>
                        </div>

                        <?php if ($estaAtivo): ?>

                            <p>Pokebier atual</p>

                        <?php elseif ($estaDerrotado): ?>

                            <p>Perdeu</p>

                        <?php else: ?>

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="acao"
                                    value="trocar"
                                >

                                <input
                                    type="hidden"
                                    name="indice"
                                    value="<?= (int) $indice ?>"
                                >

                                <button
                                    type="submit"
                                    class="botao-trocar"
                                >
                                    Escolher
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>

            <a href="batalha_padrao.php" class="botao-voltar">
                Voltar
            </a>

        <?php elseif ($tela === 'mochila'): ?>

    <h2>Mochila</h2>

    <?php if (empty($itensMochila)): ?>

        <p>Sua mochila está vazia!</p>

    <?php else: ?>

        <div class="lista-pokebiers">

            <?php foreach ($itensMochila as $item): ?>

                <?php
                $valorRevive = strtolower(
                    trim((string) $item['revive'])
                );

                $podeReviver = in_array(
                    $valorRevive,
                    ['1', 'sim', 's', 'true', 'yes'],
                    true
                );
                ?>

                <div class="cartao-pokebier">

                    <h3>
                        <?= htmlspecialchars($item['nome']) ?>
                    </h3>

                    <?php if ($podeReviver): ?>

                        <p>
                            Levanta um Pokebier derrotado com 50 HP
                            ou cura um Pokebier vivo.
                        </p>

                    <?php else: ?>

                        <p>
                            Recupera <?= (int) $item['qnt_cura_hp'] ?> HP.
                        </p>

                    <?php endif; ?>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="acao"
                            value="usar_item"
                        >

                        <input
                            type="hidden"
                            name="slot"
                            value="<?= (int) $item['slot'] ?>"
                        >

                        <label>
                            Escolha o Pokebier:
                        </label>

                        <select
                            name="indice_pokemon"
                            class="seletor-pokebier"
                            required
                        >

                            <option value="">
                                Selecione um Pokebier
                            </option>

                            <?php foreach ($equipeJogadorTela as $indice => $pokebier): ?>

                                <?php
                                $hp = (int) $pokebier->getHp();
                                $maxHp = (int) $pokebier->getMaxHp();

                                // Itens comuns só podem ser usados em vivos.
                                // Itens que revivem também podem selecionar derrotados.
                                if ($hp <= 0) {
                                    $elegivel = $podeReviver;
                                } else {
                                    $elegivel = ($hp < $maxHp);
                                }
                                ?>

                                <?php if ($elegivel): ?>

                                    <option value="<?= (int) $indice ?>">

                                        <?= htmlspecialchars($pokebier->getNome()) ?>

                                        - HP: <?= $hp ?>/<?= $maxHp ?>

                                    </option>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </select>

                        <?php
                        // Verifica se existe algum Pokebier que possa receber o item.
                        $temAlvo = false;

                        foreach ($equipeJogadorTela as $pokebier) {

                            $hp = (int) $pokebier->getHp();
                            $maxHp = (int) $pokebier->getMaxHp();

                            if ($hp <= 0 && $podeReviver) {
                                $temAlvo = true;
                            } elseif ($hp > 0 && $hp < $maxHp) {
                                $temAlvo = true;
                            }
                        }
                        ?>

                        <?php if ($temAlvo): ?>

                            <button
                                type="submit"
                                class="botao-trocar"
                            >
                                Usar item
                            </button>

                        <?php else: ?>

                            <p>
                                Nenhum Pokebier pode receber esse item agora.
                            </p>

                        <?php endif; ?>

                    </form>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <a href="batalha_padrao.php" class="botao-voltar">
        Voltar
    </a>

        <?php endif; ?>

    <?php else: ?>

        <div class="resultado">

            <h2>
                <?php if ($vencedor === 'jogador'): ?>
                    Você venceu a batalha!
                <?php else: ?>
                    Você perdeu a batalha!
                <?php endif; ?>
            </h2>

            <p>
                <?php if ($vencedor === 'jogador'): ?>
                    Todos os Pokebiers adversários foram derrotados!
                <?php else: ?>
                    Todos os seus Pokebiers foram derrotados!
                <?php endif; ?>
            </p>

            <form method="POST">

                <input type="hidden" name="acao" value="reiniciar">

                <button class="botao-reiniciar" type="submit">
                    Jogar novamente
                </button>

            </form>

            <a href="pokebier_menu.php" class="botao-menu">
                Voltar ao menu
            </a>

        </div>

    <?php endif; ?>

</section>

</main>

<script>
const musica = document.getElementById('musica-batalha');
const telaInicial = document.getElementById('tela-iniciar-batalha');
const botao = document.getElementById('btn-iniciar-batalha');

const parametros = new URLSearchParams(window.location.search);

if (parametros.get('reiniciar_musica') === '1') {
    sessionStorage.removeItem('musicaBatalhaIniciada');
    sessionStorage.removeItem('tempoMusicaBatalha');

    // Limpa o parâmetro da URL sem recarregar a página.
    window.history.replaceState({}, '', 'batalha_padrao.php');
}

// Verifica se a música já havia sido iniciada nesta sessão.
const musicaIniciada = sessionStorage.getItem('musicaBatalhaIniciada');

if (musicaIniciada === 'sim') {
    telaInicial.style.display = 'none';

    const tempoSalvo = sessionStorage.getItem('tempoMusicaBatalha');

    if (tempoSalvo !== null) {
        musica.currentTime = Number(tempoSalvo);
    }

    musica.play().catch(() => {
        telaInicial.style.display = 'flex';
        botao.textContent = 'CONTINUAR MÚSICA';
    });
}

botao.addEventListener('click', function() {
    musica.play().then(() => {
        sessionStorage.setItem('musicaBatalhaIniciada', 'sim');
        telaInicial.style.display = 'none';
    }).catch(erro => {
        console.error('Não foi possível iniciar a música:', erro);
    });
});

// Salva a posição da música antes de recarregar a página.
window.addEventListener('pagehide', function() {
    if (!musica.paused) {
        sessionStorage.setItem(
            'tempoMusicaBatalha',
            musica.currentTime
        );
    }
});
</script>

</body>
</html>
