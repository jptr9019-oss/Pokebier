<?php
require_once __DIR__ . '/../../conexao.php';
require_once __DIR__ . '/Ataques.php';
require_once __DIR__ . '/Itens.php';
require_once __DIR__ . '/Item_evo.php';
require_once __DIR__ . '/Item_cura.php';

class Pokemon {
    private $nome;
    private $hp;
    private $maxhp;
    private $velocidade;
    private $decs;
    private $tipo;
    private $subtipo;
    private $endimagem;
    private $atk1;
    private $atk2;
    private $atk3;
    private $atk4;
    private $atk_special;
    private $id;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getHp() { return $this->hp; }
    public function getMaxHp() { return $this->maxhp; }
    public function getVelocidade() { return $this->velocidade; }
    public function getDecs() { return $this->decs; }
    public function getTipo() { return $this->tipo; }
    public function getSubtipo() { return $this->subtipo; }
    public function getEndImagem() { return $this->endimagem; }
    public function getAtk1() { return $this->atk1; }
    public function getAtk2() { return $this->atk2; }
    public function getAtk3() { return $this->atk3; }
    public function getAtk4() { return $this->atk4; }
    public function getAtkSpecial() { return $this->atk_special; }

    public function getAtaquesDisponiveis() {
    $ataques = [
        1 => $this->atk1,
        2 => $this->atk2,
        3 => $this->atk3,
        4 => $this->atk4,
        5 => $this->atk_special
    ];

    $disponiveis = [];

    foreach ($ataques as $numero => $idAtaque) {
        if (!empty($idAtaque)) {
            $disponiveis[] = $numero;
        }
    }

    return $disponiveis;
}

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setHp($hp) {
        $hp = max(0, (int)$hp);
        if ($this->maxhp !== null) $hp = min($hp, (int)$this->maxhp);
        $this->hp = $hp;
    }
    public function setVelocidade($velocidade) { $this->velocidade = $velocidade; }
    public function setDecs($decs) { $this->decs = $decs; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function setSubtipo($subtipo) { $this->subtipo = $subtipo; }
    public function setEndImagem($endimagem) { $this->endimagem = $endimagem; }
    public function setAtk1($atk1) { $this->atk1 = $atk1; }
    public function setAtk2($atk2) { $this->atk2 = $atk2; }
    public function setAtk3($atk3) { $this->atk3 = $atk3; }
    public function setAtk4($atk4) { $this->atk4 = $atk4; }
    public function setAtkSpecial($atk_special) { $this->atk_special = $atk_special; }

    private function preencherDeDados($dados) {
        $this->id = $dados['id_pb'];
        $this->nome = $dados['nome'];
        $this->maxhp = (int)$dados['hp'];
        $this->hp = (int)$dados['hp'];
        $this->velocidade = $dados['velocidade'];
        $this->decs = $dados['decs'];
        $this->tipo = $dados['id_tipo'];
        $this->subtipo = $dados['id_subtipo'];
        $this->endimagem = $dados['endimagem'];
        $this->atk1 = $dados['id_atk1'];
        $this->atk2 = $dados['id_atk2'];
        $this->atk3 = $dados['id_atk3'];
        $this->atk4 = $dados['id_atk4'];
        $this->atk_special = $dados['id_atk_s'];
    }

    public function insert() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO pokebiers
             (nome, hp, velocidade, decs, id_tipo, id_subtipo, endimagem,
              id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s)
             VALUES (:nome, :hp, :velocidade, :decs, :tipo, :subtipo, :imagem,
                     :atk1, :atk2, :atk3, :atk4, :atk_special)"
        );
        $ok = $stmt->execute([
            ':nome' => $this->nome, ':hp' => $this->maxhp ?? $this->hp,
            ':velocidade' => $this->velocidade, ':decs' => $this->decs,
            ':tipo' => $this->tipo, ':subtipo' => $this->subtipo,
            ':imagem' => $this->endimagem, ':atk1' => $this->atk1,
            ':atk2' => $this->atk2, ':atk3' => $this->atk3,
            ':atk4' => $this->atk4, ':atk_special' => $this->atk_special
        ]);
        if ($ok) $this->id = $pdo->lastInsertId();
        return $ok;
    }

    public function update() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "UPDATE pokebiers SET nome = :nome, hp = :hp, velocidade = :velocidade,
             decs = :decs, id_tipo = :tipo, id_subtipo = :subtipo, endimagem = :imagem,
             id_atk1 = :atk1, id_atk2 = :atk2, id_atk3 = :atk3, id_atk4 = :atk4,
             id_atk_s = :atk_special WHERE id_pb = :id"
        );
        return $stmt->execute([
            ':nome' => $this->nome, ':hp' => $this->maxhp ?? $this->hp,
            ':velocidade' => $this->velocidade, ':decs' => $this->decs,
            ':tipo' => $this->tipo, ':subtipo' => $this->subtipo,
            ':imagem' => $this->endimagem, ':atk1' => $this->atk1,
            ':atk2' => $this->atk2, ':atk3' => $this->atk3,
            ':atk4' => $this->atk4, ':atk_special' => $this->atk_special,
            ':id' => $this->id
        ]);
    }

    public function delete() {
        if (!$this->id) return false;
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM pokebiers WHERE id_pb = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public function load() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pokebiers WHERE id_pb = :id");
        $stmt->execute([':id' => $this->id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) return false;
        $this->preencherDeDados($dados);
        return true;
    }

    public static function getOne($id) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pokebiers WHERE id_pb = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) return null;

        $pokemon = new Pokemon();
        $pokemon->preencherDeDados($dados);
        return $pokemon;
    }

    public static function getALL() {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM pokebiers");
        $pokemons = [];
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pokemon = new Pokemon();
            $pokemon->preencherDeDados($dados);
            $pokemons[] = $pokemon;
        }
        return $pokemons;
    }

    // $numero: 1 a 4 para ataques comuns; 5 para o ataque assinatura.
    public function ataque($numero, $alvo) {
        if (!$alvo || $this->hp <= 0 || $alvo->getHp() <= 0) return false;

        $ataques = [
            1 => $this->atk1,
            2 => $this->atk2,
            3 => $this->atk3,
            4 => $this->atk4,
            5 => $this->atk_special
        ];

        if (!isset($ataques[$numero]) || !$ataques[$numero]) return false;

        $ataque = Ataques::getOne($ataques[$numero]);
        if (!$ataque) return false;

        $alvo->setHp($alvo->getHp() - $ataque->getDamage());
        return true;
    }

    public function evoluir($idItem, $pokemon = null) {
        $pokemon = $pokemon ?? $this;
        $itemGeral = Itens::getOne($idItem);
        if (!$itemGeral || !$itemGeral->getIdItemEvo()) return null;

        $itemEvo = Item_evo::getOne($itemGeral->getIdItemEvo());
        if (!$itemEvo || $itemEvo->getIdPbBase() != $pokemon->getId()) return null;

        $pokemon->setId($itemEvo->getIdPbEvoluido());
        return $pokemon->load() ? $pokemon : null;
    }

    public function curar($idItem, Pokemon $pokemon = null) {
        $pokemon = $pokemon ?? $this;
        $itemGeral = Itens::getOne($idItem);
        if (!$itemGeral || !$itemGeral->getIdItemCura()) return null;

        $itemCura = Item_cura::getOne($itemGeral->getIdItemCura());
        if (!$itemCura) return null;

        // A regra de reviver Pokebiers derrotados ainda não foi definida.
        if ($pokemon->getHp() <= 0) return false;

        $pokemon->setHp($pokemon->getHp() + (int)$itemCura->getQntCura());
        return $pokemon;
    }
}
?>
