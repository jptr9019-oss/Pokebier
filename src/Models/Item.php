<?php
require_once '../../conexao.php';
class Item{

    private $nome;
    private $id;
    private $qnt_cura;

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

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setQntCura($qntCura){
        $this->qnt_cura = $qntCura;
    }

    
    public static function ListarTodos(){
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM itens_cura");
        $itens_cura = [];
        while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $p = new Item();
            $p->setId($dados["id_it_cura"]);
            $p->setQntCura($dados["qnt_cura_hp"]);
            $p->setNome($dados["nome"]);
            $itens_cura[] = $p;
        }
        return $itens_cura;
    }
}

$i = new Item();
$i = Item::ListarTodos();
var_dump($i);
//echo 'DEBUG: Item';