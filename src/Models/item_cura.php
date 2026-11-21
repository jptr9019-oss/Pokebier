<?php
require_once '../../conexao.php';
class Item_cura{

    private $nome;
    private $id;
    private $qnt_cura;
    private $revive;

    // public function __construct(){
    //     $this->nome = $nome;
    // }

    public function getId(){
        return $this->id;
    }
    public function getNome(){
        return $this->nome;
    }
    public function getQntCura(){
        return $this->qnt_cura;
    }
    public function getRevive(){
        return $this->revive;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setQntCura($qntCura){
        $this->qnt_cura = $qntCura;
    }
    public function setRevive($revive){
        $this->revive = $revive;
    }

    public function insert(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO itens_cura (nome, qnt_cura_hp, revive) VALUES (:nome, :qnt_cura_hp, :revive)");
        $ok = $stmt->execute([
            ':nome' => $this->nome,
            ':qnt_cura_hp' => $this->qnt_cura,
            ':revive' => $this->revive
        ]);
        if($ok){
            $this->id = $pdo->lastInsertId();
        }
        return $ok;
    }

    public function update(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE itens_cura SET nome = :nome, qnt_cura_hp = :qnt_cura_hp, revive = :revive WHERE id_it_cura = :id");
        $ok = $stmt->execute([
            ':nome' => $this->nome,
            ':qnt_cura_hp' => $this->qnt_cura,
            ':revive' => $this->revive,
            ':id' => $this->id
        ]);
        return $ok;
    }

    public function delete(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM itens_cura WHERE id_it_cura = :id");
        $ok = $stmt->execute([
            ':id' => $this->id
        ]);
        return $ok;
    }

    public function load(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_cura WHERE id_it_cura = :id");
        $stmt->execute([
            ':id' => $this->id
        ]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dados){
            $this->nome = $dados["nome"];
            $this->qnt_cura = $dados["qnt_cura_hp"];
            $this->revive = $dados["revive"];
            return true;
        }
        return false;
    }

    public static function getOne($id){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itens_cura WHERE id_it_cura = :id");
        $stmt->execute([
            ':id' => $id
        ]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dados){
            $item = new Item_cura();
            $item->setId($dados["id_it_cura"]);
            $item->setNome($dados["nome"]);
            $item->setQntCura($dados["qnt_cura_hp"]);
            $item->setRevive($dados["revive"]);
            return $item;
        }
        return null;
    }
    
    public static function getAll(){
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM itens_cura");
        $itens_cura = [];
        while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $p = new Item_cura();
            $p->setId($dados["id_it_cura"]);
            $p->setQntCura($dados["qnt_cura_hp"]);
            $p->setNome($dados["nome"]);
            $p->setRevive($dados["revive"]);
            $itens_cura[] = $p;
        }
        return $itens_cura;
    }
}

$i = new Item_cura();
$i = Item_cura::getAll();
var_dump($i);
//echo 'DEBUG: Item';