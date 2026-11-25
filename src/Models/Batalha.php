<?php
class Batalha {
    private $equipeJogador;
    private $equipeIA;
    private $indiceJogador = 0;
    private $indiceIA = 0;
    private $turno = 1;
    private $encerrada = false;
    private $vencedor = null;

    public function __construct($equipeJogador, $equipeIA) {
        $this->equipeJogador = array_values($equipeJogador);
        $this->equipeIA = array_values($equipeIA);
        $this->ajustarAtivoInicial('jogador');
        $this->ajustarAtivoInicial('ia');
        $this->verificarVencedor();
    }

    public function getPokebierJogador() {
        return $this->equipeJogador[$this->indiceJogador] ?? null;
    }

    public function getPokebierIA() {
        return $this->equipeIA[$this->indiceIA] ?? null;
    }

    public function getEquipeJogador() { return $this->equipeJogador; }
    public function getEquipeIA() { return $this->equipeIA; }
    public function getTurno() { return $this->turno; }
    public function getVencedor() { return $this->vencedor; }
    public function isEncerrada() { return $this->encerrada; }

    private function temPokebierVivo($equipe) {
        foreach ($equipe as $pokemon) {
            if ($pokemon && $pokemon->getHp() > 0) return true;
        }
        return false;
    }

    private function ajustarAtivoInicial($lado) {
        $equipe = ($lado === 'jogador') ? $this->equipeJogador : $this->equipeIA;
        foreach ($equipe as $i => $pokemon) {
            if ($pokemon && $pokemon->getHp() > 0) {
                if ($lado === 'jogador') $this->indiceJogador = $i;
                else $this->indiceIA = $i;
                return true;
            }
        }
        return false;
    }

    private function avancarPokebier($lado) {
        $equipe = ($lado === 'jogador') ? $this->equipeJogador : $this->equipeIA;
        $atual = ($lado === 'jogador') ? $this->indiceJogador : $this->indiceIA;

        for ($i = 0; $i < count($equipe); $i++) {
            if ($i !== $atual && $equipe[$i] && $equipe[$i]->getHp() > 0) {
                if ($lado === 'jogador') $this->indiceJogador = $i;
                else $this->indiceIA = $i;
                return true;
            }
        }
        return false;
    }

    private function executarAtaque($atacante, $alvo, $numeroAtaque) {
        if (!$atacante || !$alvo) return false;
        if ($atacante->getHp() <= 0 || $alvo->getHp() <= 0) return false;
        return $atacante->ataque($numeroAtaque, $alvo);
    }

    private function verificarVencedor() {
        $jogadorVivo = $this->temPokebierVivo($this->equipeJogador);
        $iaViva = $this->temPokebierVivo($this->equipeIA);

        if (!$jogadorVivo) {
            $this->encerrada = true;
            $this->vencedor = 'IA';
        } elseif (!$iaViva) {
            $this->encerrada = true;
            $this->vencedor = 'jogador';
        }
    }

    // Executa um turno de ataques. O controlador deve validar os números 1 a 5.
    public function executarTurno($ataqueJogador, $ataqueIA) {
        if ($this->encerrada) return false;

        $jogador = $this->getPokebierJogador();
        $ia = $this->getPokebierIA();
        if (!$jogador || !$ia) return false;

        $velocidadeJogador = (int) $jogador->getVelocidade();
$velocidadeIA = (int) $ia->getVelocidade();

// Decide quem ataca primeiro
if ($velocidadeJogador > $velocidadeIA) {
    $jogadorPrimeiro = true;

} elseif ($velocidadeIA > $velocidadeJogador) {
    $jogadorPrimeiro = false;

} else {
    // Velocidades iguais: sorteia quem começa
    $jogadorPrimeiro = (random_int(0, 1) === 0);
}

// Executa os ataques na ordem definida
if ($jogadorPrimeiro) {
    $this->executarAtaque($jogador, $ia, $ataqueJogador);

    if ($ia->getHp() > 0) {
        $this->executarAtaque($ia, $jogador, $ataqueIA);
    }

} else {
    $this->executarAtaque($ia, $jogador, $ataqueIA);

    if ($jogador->getHp() > 0) {
        $this->executarAtaque($jogador, $ia, $ataqueJogador);
    }
}

        if ($jogador->getHp() <= 0) $this->avancarPokebier('jogador');
        if ($ia->getHp() <= 0) $this->avancarPokebier('ia');

        $this->verificarVencedor();
        $this->turno++;
        return true;
    }

    public function trocarPokebier($indice) {
    if ($this->encerrada) {
        return false;
    }

    $indice = (int) $indice;

    // Verifica se o índice existe.
    if (!isset($this->equipeJogador[$indice])) {
        return false;
    }

    $novoPokebier = $this->equipeJogador[$indice];

    // Não permite trocar por um Pokebier derrotado.
    if ($novoPokebier->getHp() <= 0) {
        return false;
    }

    // Não permite escolher o Pokebier que já está ativo.
    if ($indice === $this->indiceJogador) {
        return false;
    }

    // Realiza a troca.
    $this->indiceJogador = $indice;

    return true;
}

public function executarTurnoTroca($indice, $ataqueIA) {
    if ($this->encerrada) {
        return false;
    }

    // Primeiro, tenta realizar a troca.
    if (!$this->trocarPokebier($indice)) {
        return false;
    }

    // Obtém os Pokebiers que estão ativos após a troca.
    $jogador = $this->getPokebierJogador();
    $ia = $this->getPokebierIA();

    // A IA ataca o novo Pokebier do jogador.
    $this->executarAtaque($ia, $jogador, $ataqueIA);

    // Se o Pokebier do jogador for derrotado, avança para o próximo.
    if ($jogador->getHp() <= 0) {
        $this->avancarPokebier('jogador');
    }

    // Verifica se a batalha terminou.
    $this->verificarVencedor();

    // Finaliza o turno.
    $this->turno++;

    return true;
}

public function executarTurnoItem($ataqueIA) {
    if ($this->encerrada) {
        return false;
    }

    $jogador = $this->getPokebierJogador();
    $ia = $this->getPokebierIA();

    if (
        !$jogador ||
        !$ia ||
        $jogador->getHp() <= 0 ||
        $ia->getHp() <= 0
    ) {
        return false;
    }

    // A IA ataca o Pokebier que está ativo.
    $this->executarAtaque($ia, $jogador, $ataqueIA);

    // Se o Pokebier ativo for derrotado, avança para o próximo.
    if ($jogador->getHp() <= 0) {
        $this->avancarPokebier('jogador');
    }

    // Verifica se a batalha terminou.
    $this->verificarVencedor();

    // Finaliza o turno.
    $this->turno++;

    return true;
}

}
?>
