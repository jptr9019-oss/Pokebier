<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oi!</title>

    <style>
        body {
    background-image: url("../public/assets/imagens/flower_cliffs.png");
    background-repeat: no-repeat;
    background-position: center;
    background-size: auto;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
        #tela-inicial {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            background-image: url("../public/assets/imagens/undertale_nintendo_switch_trailer_border.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        #tela-inicial button {
            border: none;
            background: none;
            cursor: pointer;
        }

        #flowery {
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            height: auto;
            width: 100px;
            image-rendering: pixelated;
            display: none;
        }

#imagem-esquerda {
    position: absolute;
    top: 35%;
    left: calc(50% - 180px);
    transform: translate(-50%, -50%);
    z-index: 5;
    width: 150px;
    max-width: 25vw;
    height: auto;
    image-rendering: pixelated;
    display: none;
    object-fit: contain;
}

@media (max-width: 600px) {
    #imagem-esquerda {
        left: 15%;
        width: 100px;
    }




    #flowery {
        width: 80px;
    }
}

        #caixa-dialogo {
            position: absolute;
            top: 70%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            height: auto;
            width: 500px;
            display: none;
        }

        #btn-avancar {
            position: absolute;
            top: 85%;
            transform: translateY(-50%);
            padding: 10px 25px;
            font-size: 1.2em;
            border-radius: 5px;
            cursor: pointer;
            background-color: rgba(68, 68, 68, 0.8);
            color: white;
            border: 2px solid white;
            display: none;
        }

        #btn-avancar:hover {
            background-color: rgba(100, 100, 100, 0.8);
        }

        #vamos_la {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 15px 35px;
            font-size: 1.5em;
            border-radius: 8px;
            cursor: pointer;
            background-color: rgba(68, 68, 68, 0.8);
            color: white;
            border: 3px solid white;
            display: none;
            z-index: 20;
            text-decoration: none;
        }

        #vamos_la:hover {
            background-color: rgba(100, 100, 100, 0.8);
        }
    </style>
</head>

<body>

    <div id="tela-inicial">
        <button id="botao_porta">
            <img
                src="../public/assets/imagens/realistic_door.png"
                alt="Abrir a porta"
            >
        </button>
    </div>

    <audio
        id="flowery_theme"
        src="../public/assets/imagens/flowery_theme.mp3"
        loop>
    </audio>

    <img id="imagem-esquerda" alt="Imagem adicional">

    <img id="flowery" alt="Flowery" style="display: none;">

    <img id="caixa-dialogo" src="" alt="Caixa de diálogo">

    <button id="btn-avancar">Avançar</button>

    <a id="vamos_la" href="pokebier_menu.php">
        Vamos lá!
    </a>

    <script>
        const telaInicial = document.getElementById("tela-inicial");
        const botaoPorta = document.getElementById("botao_porta");
        const floweryTheme = document.getElementById("flowery_theme");

        const flowery = document.getElementById("flowery");
        const imagemEsquerda = document.getElementById("imagem-esquerda");
        const caixaDialogo = document.getElementById("caixa-dialogo");
        const btnAvancar = document.getElementById("btn-avancar");
        const vamosLa = document.getElementById("vamos_la");

        const audio = new Audio();

        const dialogos = [

            {
                flowery_sprite: "../public/assets/imagens/flowery_default.png",
                png: "../public/assets/imagens/flowery_dialogue1.png",
                audio: "../public/assets/imagens/flowery_audio1.mp3",
                volumeBoost: 1.0
            },

            {
                flowery_sprite: "../public/assets/imagens/flowery_default.png",
                png: "../public/assets/imagens/flowery_dialogue2.png",
            },

            {
                flowery_sprite: "../public/assets/imagens/flowery_default.png",
                png: "../public/assets/imagens/flowery_dialogue3.png",
                audio: "../public/assets/imagens/flowery_audio2.mp3",
                volumeBoost: 1.0
            },

            {
                flowery_sprite: "../public/assets/imagens/flowery_mao_no_cabelo.png",
                png: "../public/assets/imagens/flowery_dialogue4.png",
                audio: "../public/assets/imagens/flowery_audio3.mp3",
                volumeBoost: 1.0
            },

            {
                flowery_sprite: "../public/assets/imagens/flowery_pernas_afastadas.png",
                png: "../public/assets/imagens/flowery_dialogue5.png",
                audio: "../public/assets/imagens/flowery_audio3.mp3",
                volumeBoost: 1.0
            },

            {
                flowery_sprite: "../public/assets/imagens/flowery_agachado.png",
                png: "../public/assets/imagens/flowery_dialogue6.png",
                imagem_esquerda: "../public/assets/imagens/flowery_horse.png"
            },

            {
                flowery_sprite: "../public/assets/imagens/flowery_orgulhoso.png",
                png: "../public/assets/imagens/flowery_dialogue7.png",
            },

            {
        flowery_sprite: "../public/assets/imagens/flowery_apontando_com_capa.png",
        png: "../public/assets/imagens/flowery_dialogue8.png",
        audio: "../public/assets/imagens/flowery_audio_plano.mp3"
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_segurando_copo_laranja.png",
        png: "../public/assets/imagens/flowery_dialogue9.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_enchendo_copo.png",
        png: "../public/assets/imagens/flowery_dialogue10.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue11.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue12.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue13.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue14.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue15.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue16.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_rindo_capa.png",
        png: "../public/assets/imagens/flowery_dialogue17.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue18.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue19.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue20.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_skate.png",
        png: "../public/assets/imagens/flowery_dialogue21.png",
        imagem_esquerda: "../public/assets/imagens/cool_skate.gif"
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue22.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue23.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue24.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue25.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue26.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue27.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue28.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_maos_levantadas_perna_levantada.png",
        png: "../public/assets/imagens/flowery_dialogue29.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_maos_levantadas_perna_levantada.png",
        png: "../public/assets/imagens/flowery_dialogue30.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue31.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_tchau.png",
        png: "../public/assets/imagens/flowery_dialogue32.png",
        audio: "../public/assets/imagens/flowery_audio_vou_eu.mp3",
        volumeBoost: 1.0

    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_assustado.png",
        png: "../public/assets/imagens/flowery_dialogue33.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue34.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue35.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue36.png",
    },
    {
        flowery_sprite: "../public/assets/imagens/flowery_default.png",
        png: "../public/assets/imagens/flowery_dialogue37.png",
    }
];

        let indiceDialogo = 0;

        function mostrarDialogo(indice) {
            const dialogo = dialogos[indice];

            if (!dialogo) return;

            if (dialogo.flowery_sprite) {
                flowery.src = dialogo.flowery_sprite;
            }

            if (dialogo.imagem_esquerda) {
        imagemEsquerda.src = dialogo.imagem_esquerda;
        imagemEsquerda.style.display = "block";
    } else {
        imagemEsquerda.removeAttribute("src");
        imagemEsquerda.style.display = "none";
    }

            if (dialogo.png) {
                caixaDialogo.src = dialogo.png;
            }

            flowery.style.display = "block";
            caixaDialogo.style.display = "block";
            btnAvancar.style.display = "block";

            if (dialogo.audio) {
                audio.pause();
                audio.src = dialogo.audio;
                audio.volume = 1.0;

                audio.play().catch(err => {
                    console.error("Erro ao tocar áudio do diálogo:", err);
                });
            }
        }

        botaoPorta.addEventListener("click", async () => {
            telaInicial.style.display = "none";
            flowery.style.display = "block";

            try {
                await floweryTheme.play();
            } catch (err) {
                console.error("Erro ao tocar a música de fundo:", err);
            }

            if (dialogos.length > 0) {
                mostrarDialogo(indiceDialogo);
            } else {
                vamosLa.style.display = "block";
            }
        });

        btnAvancar.addEventListener("click", () => {
            indiceDialogo++;

            if (indiceDialogo < dialogos.length) {
                mostrarDialogo(indiceDialogo);
            } else {
                audio.pause();
                flowery.style.display = "none";
                caixaDialogo.style.display = "none";
                btnAvancar.style.display = "none";
                imagemEsquerda.style.display = "none";
                vamosLa.style.display = "block";
            }
        });
    </script>
</body>
</html>