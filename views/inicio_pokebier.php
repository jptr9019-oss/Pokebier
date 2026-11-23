<?php
session_start();

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../conexao.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    if ($acao === 'cadastro') {

        $apelido = trim($_POST['apelido'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $pdo = getConnection();

        if ($apelido === '' || $senha === '') {

            $mensagem = "Preencha todos os campos!";

        } else {

            $pdo = getConnection();

            try {

                $pdo->beginTransaction();

                // 1. Verifica se o apelido já existe
                $stmt = $pdo->prepare(
                    "SELECT id_usuario FROM Usuario WHERE apelido = :apelido"
                );

                $stmt->execute([':apelido' => $apelido]);

                if ($stmt->fetch()) {
                    throw new Exception("Esse apelido já está sendo utilizado!");
                }

                // 2. Cria uma mochila vazia
                $pdo->exec("INSERT INTO Mochila () VALUES ()");

                $idMochila = $pdo->lastInsertId();

                // 3. Cria a equipe com os seis Pokébiers padrão
                $stmt = $pdo->prepare("
                    INSERT INTO Equipe
                    (id_pb, id_pb_2, id_pb_3, id_pb_4, id_pb_5, id_pb_6)
                    VALUES (1, 2, 3, 4, 5, 6)
                ");

                $stmt->execute();

                $idEquipe = $pdo->lastInsertId();

                // 4. Cria o usuário
                $usuario = new Usuario();

                $usuario->setApelido($apelido);

                // Protege a senha
                $usuario->setSenha(
                    password_hash($senha, PASSWORD_DEFAULT)
                );

                $usuario->setDinheiro(0);
                $usuario->setIdMochila($idMochila);
                $usuario->setIdEquipe($idEquipe);

                // Utiliza a mesma conexão da transação
                $usuario->insert($pdo);

            
                $pdo->commit();

// Inicia a sessão do jogador
$_SESSION['usuario_id'] = $usuario->getId();
$_SESSION['usuario_apelido'] = $usuario->getApelido();

// Entra no jogo
header("Location: pokebier_menu.php");
exit;

            } catch (Exception $e) {

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $mensagem = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    } elseif ($acao === 'login') {

    $apelido = trim($_POST['apelido'] ?? '');
    $senha = $_POST['senha'] ?? '';

     $pdo = getConnection();

    $stmt = $pdo->prepare(
        "SELECT * FROM Usuario WHERE apelido = :apelido"
    );

    $stmt->execute([':apelido' => $apelido]);

    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados && password_verify($senha, $dados['senha'])) {

        // Login correto
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $dados['id_usuario'];
        $_SESSION['usuario_apelido'] = $dados['apelido'];

        header("Location: pokebier_menu.php");
        exit;

    } else {

        $mensagem = "Apelido ou senha incorretos!";
    }
}
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POKEBIER - Entrar</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background-color: black;
            font-family: sans-serif;
            color: white;
        }

        /* Tela inicial */

        #tela-inicial {
    position: fixed;
    inset: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

        #botao-iniciar {
            padding: 18px 45px;
            font-size: 1.5em;
            color: white;
            background-color: #292929;
            border: 3px solid white;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        #botao-iniciar:hover {
            background-color: #555;
            transform: scale(1.05);
        }

       #aviso-piscante {
    color: white;
    font-size: 14px;
    text-align: center;
    margin-top: 20px;
    max-width: 350px;
    line-height: 1.5;
    text-shadow: 2px 2px 4px black;
}

#aviso-volume {
    color: white;
    font-size: 14px;
    text-align: center;
    margin-top: 5px;
    max-width: 350px;
    line-height: 1.5;
    text-shadow: 2px 2px 4px black;
}

        /* Imagens que piscam */

        #flash-image {
    position: fixed;
    width: 300px;
    height: 300px;
    object-fit: contain;

    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

    image-rendering: pixelated;

    z-index: 5;
    display: none;
}

        /* Tela de login e cadastro */

        #tela-autenticacao {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            background-image: url("../public/assets/imagens/starboard.gif");
            background-size: auto;
            background-position: auto;
            background-repeat: repeat;
            padding: 20px;
        }

        /* Caixa dos formulários */

        #caixa-autenticacao {
            width: 400px;
            max-width: 100%;
            padding: 35px;
            background-color: rgba(0, 0, 0, 0.85);
            border: 3px solid white;
            border-radius: 15px;
            text-align: center;
        }

        #caixa-autenticacao h1 {
            margin-top: 0;
            margin-bottom: 25px;
        }

        /* Alternância entre login e cadastro */

        .botoes-tipo {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .botoes-tipo button {
            flex: 1;
            padding: 10px;
            background-color: #444;
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            cursor: pointer;
        }

        .botoes-tipo button.ativo {
            background-color: #8b2525;
        }

        /* Campos */

        .formulario {
            display: none;
        }

        .formulario.ativo {
            display: block;
        }

        .formulario input {
            display: block;
            width: 100%;
            padding: 13px;
            margin-bottom: 15px;
            border: 1px solid #aaa;
            border-radius: 5px;
            font-size: 1em;
        }

        .formulario button {
            width: 100%;
            padding: 13px;
            margin-top: 5px;
            background-color: #8b2525;
            color: white;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
        }

        .formulario button:hover {
            background-color: #ad3636;
        }

        @media (max-width: 480px) {
            #caixa-autenticacao {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Música da introdução -->
    <audio id="musica-intro"
        src="../public/assets/imagens/flower_castle.mp3" loop>
    </audio>

    <!-- Tela inicial -->
    <div id="tela-inicial">
        <button id="botao-iniciar">
            INICIAR
        </button>

        <p id="aviso-piscante">
        AVISO: Ao clicar em "INICIAR", imagens/luzes piscantes irão aparecer!
        </p>

        <p id="aviso-volume">
        RECOMENDAÇÃO: Mantenha o volume ligado para uma experiência melhor.
        </p>
    </div>


    <!-- Imagem utilizada nos flashes -->
    <img id="flash-image" alt="Imagem da introdução">

    <!-- Tela de cadastro e login -->
    <div id="tela-autenticacao">

        <div id="caixa-autenticacao">

            <h1>POKEBIER</h1>

            <?php if ($mensagem !== ''): ?>
    <p><?= htmlspecialchars($mensagem) ?></p>
<?php endif; ?>

            <div class="botoes-tipo">

                <button type="button" id="btn-login" class="ativo">
                    Login
                </button>

                <button type="button" id="btn-cadastro">
                    Cadastro
                </button>

            </div>

            <!-- Formulário de login -->
            <form id="form-login" class="formulario ativo"
                method="POST" action="">

                <input type="hidden" name="acao" value="login">

                <input 
    type="text" 
    name="apelido" 
    placeholder="Apelido" 
    required
>
                <input
                    type="password"
                    name="senha"
                    placeholder="Senha"
                    required
                >

                <button type="submit">
                    Entrar
                </button>

            </form>

            <!-- Formulário de cadastro -->
            <form id="form-cadastro" class="formulario"
                method="POST" action="">

                <input type="hidden" name="acao" value="cadastro">

                <input
                    type="text"
                    name="apelido"
                    placeholder="Crie um Apelido"
                    required
                >

                <input
                    type="password"
                    name="senha"
                    placeholder="Crie uma senha"
                    required
                >

                <button type="submit">
                    Cadastrar
                </button>

            </form>

        </div>

    </div>

    <script>

        const telaInicial = document.getElementById("tela-inicial");
        const botaoIniciar = document.getElementById("botao-iniciar");

        const musicaIntro = document.getElementById("musica-intro");
        const flashImage = document.getElementById("flash-image");

        const telaAutenticacao =
            document.getElementById("tela-autenticacao");

        const btnLogin = document.getElementById("btn-login");
        const btnCadastro = document.getElementById("btn-cadastro");

        const formLogin = document.getElementById("form-login");
        const formCadastro = document.getElementById("form-cadastro");

        // Imagens utilizadas durante a introdução
        const imagens = [
            "../public/assets/imagens/yellow_flower.png",
            "../public/assets/imagens/aqua_flower.png",
            "../public/assets/imagens/blue_flower.png",
            "../public/assets/imagens/seth_flower.png",
            "../public/assets/imagens/orange_flower.png",
            "../public/assets/imagens/green_flower.png",
            "../public/assets/imagens/yellow_flower.png",
            "../public/assets/imagens/aqua_flower.png",
            "../public/assets/imagens/blue_flower.png",
            "../public/assets/imagens/seth_flower.png",
            "../public/assets/imagens/orange_flower.png",
            "../public/assets/imagens/green_flower.png",
        ];

        // BPM da introdução
const bpm = 135;

// Intervalo entre cada flash
const intervalo = 60000 / bpm;

// Duração de cada imagem
const duracaoFlash = 250;

// Momentos dos 12 flashes
const momentos = imagens.map((imagem, indice) => {
    return indice * intervalo;
});

        botaoIniciar.addEventListener("click", async () => {

            // Esconde a tela inicial
            telaInicial.style.display = "none";

            // Tenta iniciar a música
            musicaIntro.currentTime = 0;

            musicaIntro.play().catch(err => {
                console.error("Não foi possível tocar a música:", err);
            });

            // Exibe cada imagem no momento programado
            imagens.forEach((imagem, indice) => {

                setTimeout(() => {

                    flashImage.src = imagem;
                    flashImage.style.display = "block";

                    setTimeout(() => {
                        flashImage.style.display = "none";
                    }, duracaoFlash);

                }, momentos[indice]);

            });

            // Após 7 segundos, exibe login e cadastro
            setTimeout(() => {

                flashImage.style.display = "none";
                telaAutenticacao.style.display = "flex";

            }, 7350);

        });

        // Alternar para login
        btnLogin.addEventListener("click", () => {

            formLogin.classList.add("ativo");
            formCadastro.classList.remove("ativo");

            btnLogin.classList.add("ativo");
            btnCadastro.classList.remove("ativo");

        });

        // Alternar para cadastro
        btnCadastro.addEventListener("click", () => {

            formCadastro.classList.add("ativo");
            formLogin.classList.remove("ativo");

            btnCadastro.classList.add("ativo");
            btnLogin.classList.remove("ativo");

        });

    </script>

</body>
</html>