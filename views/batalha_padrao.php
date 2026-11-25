
<?php
require_once __DIR__ . '/../autoload.php';

// Carrega explicitamente a classe Batalha antes de recuperar a sessão.
require_once __DIR__ . '/../src/Models/Batalha.php';

// Depois de carregar as classes, verifica o login.
require_once __DIR__ . '/../src/controllers/verificar_login.php';

// Se o jogador entrou pelo menu para começar uma nova batalha,
// apaga a batalha anterior.
if (isset($_GET['nova']) && $_GET['nova'] === '1') {
    unset($_SESSION['batalha_padrao']);

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
// DADOS PARA EXIBIR NA TELA
// --------------------------------------------------

$pokemonJogador = $batalha->getPokebierJogador();
$pokemonIA = $batalha->getPokebierIA();

$encerrada = $batalha->isEncerrada();
$vencedor = $batalha->getVencedor();

$ataquesJogador = $pokemonJogador
    ? obterAtaquesDisponiveis($pokemonJogador)
    : [];

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
            background: #171717;
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
            border: 4px solid #f1c40f;
            border-radius: 15px;
            background:
                linear-gradient(
                    to bottom,
                    #a9d6e5 0%,
                    #d9ed92 60%,
                    #7cb342 60%,
                    #386641 100%
                );
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
            top: 20px;
            left: 20px;
        }

        .info-jogador {
            bottom: 20px;
            right: 20px;
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
            top: 65px;
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
            border: 2px solid #f1c40f;
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
            background: #f1c40f;
            color: #171717;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .botao-ataque:hover {
            background: #d4ac0d;
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
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
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

    <h1>POKEBIER - BATALHA PADRÃO</h1>

    <p class="turno">
        Turno <?= (int) $batalha->getTurno() ?>
    </p>

    <section class="campo-batalha">

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
    <section class="area-comandos">

        <?php if (!$encerrada): ?>

            <h2>
                O que <?= htmlspecialchars($pokemonJogador->getNome()) ?> fará?
            </h2>

            <div class="botoes-ataques">

                <?php foreach ($ataquesJogador as $numero => $ataque): ?>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="acao"
                            value="atacar"
                        >

                        <input
                            type="hidden"
                            name="ataque"
                            value="<?= (int) $numero ?>"
                        >

                        <button
                            type="submit"
                            class="botao-ataque"
                        >
                            <?= htmlspecialchars($ataque->getNome()) ?>
                        </button>

                    </form>

                <?php endforeach; ?>

            </div>

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

                    <input
                        type="hidden"
                        name="acao"
                        value="reiniciar"
                    >

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
