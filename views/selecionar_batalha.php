
<?php
require_once __DIR__ . '/../src/controllers/verificar_login.php';
require_once __DIR__ . '/../conexao.php';

$pdo = getConnection();
$idUsuario = (int) $_SESSION['usuario_id'];

// Busca os outros usuários que possuem uma equipe cadastrada.
$stmt = $pdo->prepare("
    SELECT
        u.id_usuario,
        u.apelido,
        e.id_equipe
    FROM usuario u
    INNER JOIN equipe e
        ON u.id_equipe = e.id_equipe
    WHERE u.id_usuario <> :id_usuario
    ORDER BY u.apelido ASC
");

$stmt->execute([
    ':id_usuario' => $idUsuario
]);

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            padding: 30px 15px;
        }

        .container-batalha {
            width: 90%;
            max-width: 850px;
            text-align: center;
        }

        h1 {
            margin-bottom: 35px;
        }

        .opcoes-batalha {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .opcao {
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

        button,
        .botao {
            display: inline-block;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            background-color: #f1c40f;
            color: #171717;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        button:hover,
        .botao:hover {
            background-color: #d4ac0d;
        }

        .lista-usuarios {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .usuario {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: #171717;
            border: 1px solid #555;
            border-radius: 8px;
        }

        .usuario h3 {
            overflow-wrap: anywhere;
        }

        .mensagem-vazia {
            line-height: 1.5;
            color: #ccc;
        }

        .botao-voltar {
            margin-top: 30px;
            background-color: #555;
            color: white;
        }

        .botao-voltar:hover {
            background-color: #777;
        }

        @media (max-width: 650px) {
            .opcoes-batalha {
                grid-template-columns: 1fr;
            }

            .usuario {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <main class="container-batalha">

        <h1>Escolha sua batalha</h1>

        <section class="opcoes-batalha">

            <!-- BATALHA PADRÃO -->
            <div class="opcao">

                <h2>Batalha Padrão</h2>

                <p>
                    Enfrente uma equipe fixa de seis Pokebiers!
                </p>

                <a
                    href="batalha_padrao.php?nova=1"
                    class="botao"
                >
                    Jogar
                </a>

            </div>

            <!-- BATALHA CONTRA OUTRO USUÁRIO -->
            <div class="opcao">

                <h2>Batalha contra usuário</h2>

                <p>
                    Escolha outro usuário e enfrente
                    a equipe de Pokebiers dele!
                </p>

                <?php if (empty($usuarios)): ?>

                    <p class="mensagem-vazia">
                        Ainda não existem outros usuários
                        com equipes cadastradas.
                    </p>

                <?php else: ?>

                    <div class="lista-usuarios">

                        <?php foreach ($usuarios as $usuario): ?>

                            <div class="usuario">

                                <h3>
                                    <?= htmlspecialchars($usuario['apelido']) ?>
                                </h3>

                                <a
                                    href="batalha_usuario.php?id_adversario=<?= (int) $usuario['id_usuario'] ?>&nova=1"
                                    class="botao"
                                >
                                    Enfrentar
                                </a>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        </section>

        <button
            class="botao-voltar"
            onclick="history.back()"
        >
            Voltar
        </button>

    </main>

</body>
</html>
