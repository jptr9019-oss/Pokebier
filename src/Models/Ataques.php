<?php
require_once __DIR__ . '/../../conexao.php';

class Ataques {
    private $id;
    private $nome;
    private $damage;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getDamage() { return $this->damage; }

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setDamage($damage) { $this->damage = $damage; }

    public function insert() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO atks (nome, dam) VALUES (:nome, :damage)");
        $ok = $stmt->execute([
            ':nome' => $this->nome,
            ':damage' => $this->damage
        ]);
        if ($ok) $this->id = $pdo->lastInsertId();
        return $ok;
    }

    public function update() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "UPDATE atks SET nome = :nome, dam = :damage WHERE id_atk = :id"
        );
        return $stmt->execute([
            ':nome' => $this->nome,
            ':damage' => $this->damage,
            ':id' => $this->id
        ]);
    }

    public function load() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM atks WHERE id_atk = :id");
        $stmt->execute([':id' => $this->id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return false;

        $this->nome = $dados['nome'];
        $this->damage = $dados['dam'];
        return true;
    }

    public function delete() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM atks WHERE id_atk = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getOne($id) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM atks WHERE id_atk = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return null;

        $atk = new Ataques();
        $atk->setId($dados['id_atk']);
        $atk->setNome($dados['nome']);
        $atk->setDamage($dados['dam']);
        return $atk;
    }

    public static function getAll() {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM atks");
        $atks = [];

        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $atk = new Ataques();
            $atk->setId($dados['id_atk']);
            $atk->setNome($dados['nome']);
            $atk->setDamage($dados['dam']);
            $atks[] = $atk;
        }
        return $atks;
    }
}
?>
