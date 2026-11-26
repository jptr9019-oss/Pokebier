<?php
require_once __DIR__ . '/../../conexao.php';

class ItemEvo extends Item {
    //private $nome;
    //private $id;
    private $id_pb_base;
    private $id_pb_evoluido;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getIdPbBase() { return $this->id_pb_base; }
    public function getIdPbEvoluido() { return $this->id_pb_evoluido; }

    public function setNome($nome) { $this->nome = $nome; }
    public function setId($id) { $this->id = $id; }
    public function setIdPbBase($id_pb_base) { $this->id_pb_base = $id_pb_base; }
    public function setIdPbEvoluido($id_pb_evoluido) { $this->id_pb_evoluido = $id_pb_evoluido; }

    public function insert() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO itens_evos (nome, id_pb, id_pb_evo)
             VALUES (:nome, :id_pb_base, :id_pb_evoluido)"
        );
        $ok = $stmt->execute([
            ':nome' => $this->nome,
            ':id_pb_base' => $this->id_pb_base,
            ':id_pb_evoluido' => $this->id_pb_evoluido
        ]);
        if ($ok) $this->id = $pdo->lastInsertId();
        return $ok;
    }

    public function update() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "UPDATE itens_evos
             SET nome = :nome, id_pb = :id_pb_base, id_pb_evo = :id_pb_evoluido
             WHERE id_it_evo = :id"
        );
        return $stmt->execute([
            ':nome' => $this->nome,
            ':id_pb_base' => $this->id_pb_base,
            ':id_pb_evoluido' => $this->id_pb_evoluido,
            ':id' => $this->id
        ]);
    }

    public function load() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_evos WHERE id_it_evo = :id");
        $stmt->execute([':id' => $this->id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return false;

        $this->nome = $dados['nome'];
        $this->id_pb_base = $dados['id_pb'];
        $this->id_pb_evoluido = $dados['id_pb_evo'];
        return true;
    }

    public function delete() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM itens_evos WHERE id_it_evo = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getOne($id) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_evos WHERE id_it_evo = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return null;

        $item = new ItemEvo();
        $item->setId($dados['id_it_evo']);
        $item->setNome($dados['nome']);
        $item->setIdPbBase($dados['id_pb']);
        $item->setIdPbEvoluido($dados['id_pb_evo']);
        return $item;
    }

    public static function getAll() {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM itens_evos");
        $itens = [];

        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new ItemEvo();
            $item->setId($dados['id_it_evo']);
            $item->setNome($dados['nome']);
            $item->setIdPbBase($dados['id_pb']);
            $item->setIdPbEvoluido($dados['id_pb_evo']);
            $itens[] = $item;
        }
        return $itens;
    }
}
?>
