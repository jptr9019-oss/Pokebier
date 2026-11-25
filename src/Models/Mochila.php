<?php
require_once __DIR__ . '/../../conexao.php';

class Mochila {
    private $id;
    private $id_item1;
    private $id_item2;
    private $id_item3;
    private $id_item4;
    private $id_item5;

    public function getId() { return $this->id; }
    public function getIdItem1() { return $this->id_item1; }
    public function getIdItem2() { return $this->id_item2; }
    public function getIdItem3() { return $this->id_item3; }
    public function getIdItem4() { return $this->id_item4; }
    public function getIdItem5() { return $this->id_item5; }

    public function setId($id) { $this->id = $id; }
    public function setIdItem1($id) { $this->id_item1 = $id; }
    public function setIdItem2($id) { $this->id_item2 = $id; }
    public function setIdItem3($id) { $this->id_item3 = $id; }
    public function setIdItem4($id) { $this->id_item4 = $id; }
    public function setIdItem5($id) { $this->id_item5 = $id; }

    public function insert() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO mochila (id_itens, id_itens_2, id_itens_3, id_itens_4, id_itens_5)
             VALUES (:item1, :item2, :item3, :item4, :item5)"
        );
        $ok = $stmt->execute([
            ':item1' => $this->id_item1,
            ':item2' => $this->id_item2,
            ':item3' => $this->id_item3,
            ':item4' => $this->id_item4,
            ':item5' => $this->id_item5
        ]);
        if ($ok) $this->id = $pdo->lastInsertId();
        return $ok;
    }

    public function update() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "UPDATE mochila
             SET id_itens = :item1, id_itens_2 = :item2, id_itens_3 = :item3,
                 id_itens_4 = :item4, id_itens_5 = :item5
             WHERE id_mochi = :id"
        );
        return $stmt->execute([
            ':item1' => $this->id_item1,
            ':item2' => $this->id_item2,
            ':item3' => $this->id_item3,
            ':item4' => $this->id_item4,
            ':item5' => $this->id_item5,
            ':id' => $this->id
        ]);
    }

    public function load() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM mochila WHERE id_mochi = :id");
        $stmt->execute([':id' => $this->id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return false;

        $this->id_item1 = $dados['id_itens'];
        $this->id_item2 = $dados['id_itens_2'];
        $this->id_item3 = $dados['id_itens_3'];
        $this->id_item4 = $dados['id_itens_4'];
        $this->id_item5 = $dados['id_itens_5'];
        return true;
    }

    public function delete() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM mochila WHERE id_mochi = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    private static function criarDeDados($dados) {
        $mochila = new Mochila();
        $mochila->setId($dados['id_mochi']);
        $mochila->setIdItem1($dados['id_itens']);
        $mochila->setIdItem2($dados['id_itens_2']);
        $mochila->setIdItem3($dados['id_itens_3']);
        $mochila->setIdItem4($dados['id_itens_4']);
        $mochila->setIdItem5($dados['id_itens_5']);
        return $mochila;
    }

    public static function getOne($id) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM mochila WHERE id_mochi = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? self::criarDeDados($dados) : null;
    }

    public static function getAll() {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM mochila");
        $mochilas = [];

        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mochilas[] = self::criarDeDados($dados);
        }
        return $mochilas;
    }

    public static function getByUsuario($idUsuario) {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "SELECT m.*
             FROM mochila m
             INNER JOIN usuario u ON u.id_mochi = m.id_mochi
             WHERE u.id_usuario = :id_usuario"
        );
        $stmt->execute([':id_usuario' => $idUsuario]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? self::criarDeDados($dados) : null;
    }
}
?>
