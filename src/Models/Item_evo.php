<?php
require_once '../../conexao.php';
class Item_evo{
    
    private $nome;
    private $id;
    private $id_pb_base;
    private $id_pb_evoluido;

    public function getId(){
        return $this->id;
    }
    public function getNome(){
        return $this->nome;
    }
    public function getIdPbBase(){
        return $this->id_pb_base;
    }
    public function getIdPbEvoluido(){
        return $this->id_pb_evoluido;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function setIdPbBase($id_pb_base){
        $this->id_pb_base = $id_pb_base;
    }
    public function setIdPbEvoluido($id_pb_evoluido){
        $this->id_pb_evoluido = $id_pb_evoluido;
    }

    public function insert(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO itens_evos (nome, id_pb, id_pb_evo) VALUES (:nome, :id_pb_base, :id_pb_evoluido)");
        $ok = $stmt->execute([
            ':nome' => $this->nome,
            ':id_pb_base' => $this->id_pb_base,
            ':id_pb_evoluido' => $this->id_pb_evoluido
        ]);
        if($ok){
            $this->id = $pdo->lastInsertId();
        }
        return $ok;
    }

    public function update(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE itens_evos SET nome = :nome, id_pb = :id_pb_base, id_pb_evo = :id_pb_evoluido WHERE id_it_evo = :id");
        $ok = $stmt->execute([
            ':nome' => $this->nome,
            ':id_pb_base' => $this->id_pb_base,
            ':id_pb_evoluido' => $this->id_pb_evoluido,
            ':id' => $this->id
        ]);
        return $ok;
    }

    public function load(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_evos WHERE id_it_evo = :id");
        $stmt->execute([':id' => $this->id]);
        if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->nome = $dados['nome'];
            $this->id_pb_base = $dados['id_pb'];
            $this->id_pb_evoluido = $dados['id_pb_evo'];
            return true;
        }
        return false;
    }

    public function delete(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM itens_evos WHERE id_it_evo = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getOne($id){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_evos WHERE id_it_evo = :id");
        $stmt->execute([':id' => $id]);
        if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $item_evo = new Item_evo();
            $item_evo->setId($dados['id_it_evo']);
            $item_evo->setNome($dados['nome']);
            $item_evo->setIdPbBase($dados['id_pb']);
            $item_evo->setIdPbEvoluido($dados['id_pb_evo']);
            return $item_evo;
        }
        return null;
    }

    public static function getAll(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_evos");
        $stmt->execute();
        $itens_evos = [];
        while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $item_evo = new Item_evo();
            $item_evo->setId($dados['id_it_evo']);
            $item_evo->setNome($dados['nome']);
            $item_evo->setIdPbBase($dados['id_pb']);
            $item_evo->setIdPbEvoluido($dados['id_pb_evo']);
            $itens_evos[] = $item_evo;
        }
        return $itens_evos;
    }
}

$i = new Item_evo();
$i = Item_evo::getAll();
var_dump($i);
?>