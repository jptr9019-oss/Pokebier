<?php
require_once __DIR__ . '/../../conexao.php';

class Itens {
    private $id;
    private $id_item_cura;
    private $id_item_evo;
    private $preco;
    private $imagem;

    public function getId() { return $this->id; }
    public function getIdItemCura() { return $this->id_item_cura; }
    public function getIdItemEvo() { return $this->id_item_evo; }
    public function getPreco() { return $this->preco; }
    public function getImagem() { return $this->imagem; }

    public function setId($id) { $this->id = $id; }
    public function setIdItemCura($id_item_cura) { $this->id_item_cura = $id_item_cura; }
    public function setIdItemEvo($id_item_evo) { $this->id_item_evo = $id_item_evo; }
    public function setPreco($preco) { $this->preco = $preco; }
    public function setImagem($imagem) { $this->imagem = $imagem; }

    public function insert() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO itens (id_it_evo, id_it_cura, preco, imagem)
             VALUES (:id_item_evo, :id_item_cura, :preco, :imagem)"
        );
        $ok = $stmt->execute([
            ':id_item_evo' => $this->id_item_evo,
            ':id_item_cura' => $this->id_item_cura,
            ':preco' => $this->preco,
            ':imagem' => $this->imagem
        ]);
        if ($ok) $this->id = $pdo->lastInsertId();
        return $ok;
    }

    public function update() {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            "UPDATE itens
             SET id_it_evo = :id_item_evo, id_it_cura = :id_item_cura,
                 preco = :preco, imagem = :imagem
             WHERE id_itens = :id"
        );
        return $stmt->execute([
            ':id_item_evo' => $this->id_item_evo,
            ':id_item_cura' => $this->id_item_cura,
            ':preco' => $this->preco,
            ':imagem' => $this->imagem,
            ':id' => $this->id
        ]);
    }

    public function load() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens WHERE id_itens = :id");
        $stmt->execute([':id' => $this->id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return false;

        $this->id_item_cura = $dados['id_it_cura'];
        $this->id_item_evo = $dados['id_it_evo'];
        $this->preco = $dados['preco'];
        $this->imagem = $dados['imagem'];
        return true;
    }

    public function delete() {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM itens WHERE id_itens = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getOne($id) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens WHERE id_itens = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return null;

        $item = new Itens();
        $item->setId($dados['id_itens']);
        $item->setIdItemCura($dados['id_it_cura']);
        $item->setIdItemEvo($dados['id_it_evo']);
        $item->setPreco($dados['preco']);
        $item->setImagem($dados['imagem']);
        return $item;
    }

    public static function getAll() {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM itens");
        $itens = [];

        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new Itens();
            $item->setId($dados['id_itens']);
            $item->setIdItemCura($dados['id_it_cura']);
            $item->setIdItemEvo($dados['id_it_evo']);
            $item->setPreco($dados['preco']);
            $item->setImagem($dados['imagem']);
            $itens[] = $item;
        }
        return $itens;
    }
}
?>
