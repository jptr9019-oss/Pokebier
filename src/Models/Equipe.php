<?php
require_once __DIR__ . '/../../conexao.php';

class Equipe {
    private $id;
    private $id_pokemon1;
    private $id_pokemon2;
    private $id_pokemon3;
    private $id_pokemon4;
    private $id_pokemon5;
    private $id_pokemon6;

    public function getId() { return $this->id; }
    public function getIdPokemon1() { return $this->id_pokemon1; }
    public function getIdPokemon2() { return $this->id_pokemon2; }
    public function getIdPokemon3() { return $this->id_pokemon3; }
    public function getIdPokemon4() { return $this->id_pokemon4; }
    public function getIdPokemon5() { return $this->id_pokemon5; }
    public function getIdPokemon6() { return $this->id_pokemon6; }

    public function setId($id) { $this->id = $id; }
    public function setIdPokemon1($id) { $this->id_pokemon1 = $id; }
    public function setIdPokemon2($id) { $this->id_pokemon2 = $id; }
    public function setIdPokemon3($id) { $this->id_pokemon3 = $id; }
    public function setIdPokemon4($id) { $this->id_pokemon4 = $id; }
    public function setIdPokemon5($id) { $this->id_pokemon5 = $id; }
    public function setIdPokemon6($id) { $this->id_pokemon6 = $id; }

    public function insert() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO equipe (id_pb, id_pb_2, id_pb_3, id_pb_4, id_pb_5, id_pb_6)
             VALUES (:p1, :p2, :p3, :p4, :p5, :p6)"
        );
        $ok = $stmt->execute([
            ':p1' => $this->id_pokemon1, ':p2' => $this->id_pokemon2,
            ':p3' => $this->id_pokemon3, ':p4' => $this->id_pokemon4,
            ':p5' => $this->id_pokemon5, ':p6' => $this->id_pokemon6
        ]);
        if ($ok) $this->id = $pdo->lastInsertId();
        return $ok;
    }

    public function update() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "UPDATE equipe SET id_pb = :p1, id_pb_2 = :p2, id_pb_3 = :p3,
             id_pb_4 = :p4, id_pb_5 = :p5, id_pb_6 = :p6
             WHERE id_equipe = :id"
        );
        return $stmt->execute([
            ':p1' => $this->id_pokemon1, ':p2' => $this->id_pokemon2,
            ':p3' => $this->id_pokemon3, ':p4' => $this->id_pokemon4,
            ':p5' => $this->id_pokemon5, ':p6' => $this->id_pokemon6,
            ':id' => $this->id
        ]);
    }

    private static function criarDeDados($dados) {
        $equipe = new Equipe();
        $equipe->setId($dados['id_equipe']);
        $equipe->setIdPokemon1($dados['id_pb']);
        $equipe->setIdPokemon2($dados['id_pb_2']);
        $equipe->setIdPokemon3($dados['id_pb_3']);
        $equipe->setIdPokemon4($dados['id_pb_4']);
        $equipe->setIdPokemon5($dados['id_pb_5']);
        $equipe->setIdPokemon6($dados['id_pb_6']);
        return $equipe;
    }

    public function load() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM equipe WHERE id_equipe = :id");
        $stmt->execute([':id' => $this->id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) return false;

        $equipe = self::criarDeDados($dados);
        $this->id_pokemon1 = $equipe->getIdPokemon1();
        $this->id_pokemon2 = $equipe->getIdPokemon2();
        $this->id_pokemon3 = $equipe->getIdPokemon3();
        $this->id_pokemon4 = $equipe->getIdPokemon4();
        $this->id_pokemon5 = $equipe->getIdPokemon5();
        $this->id_pokemon6 = $equipe->getIdPokemon6();
        return true;
    }

    public function delete() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM equipe WHERE id_equipe = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getOne($id) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM equipe WHERE id_equipe = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? self::criarDeDados($dados) : null;
    }

    public static function getAll() {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM equipe");
        $equipes = [];
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $equipes[] = self::criarDeDados($dados);
        }
        return $equipes;
    }

    public static function getByUsuario($idUsuario) {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "SELECT e.*
             FROM equipe e
             INNER JOIN usuario u ON u.id_equipe = e.id_equipe
             WHERE u.id_usuario = :id_usuario"
        );
        $stmt->execute([':id_usuario' => $idUsuario]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? self::criarDeDados($dados) : null;
    }
}
?>
