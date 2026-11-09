<?php
require_once ('../../conexao.php');
class Pokemon{

    private $nome;
    private $hp;
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


    // public function __construct($nome, $hp, $velocidade, $decs, $tipo, $subtipo, $endimagem, $atk1, $atk2, $atk3, $atk4, $atk_special){
    //     $this->nome = $nome;
    //     $this->hp = $hp;
    //     $this->velocidade = $velocidade;
    //     $this->decs = $decs;
    //     $this->tipo = $tipo;
    //     $this->subtipo = $subtipo;
    //     $this->endimagem = $endimagem;
    //     $this->atk1 = $atk1;
    //     $this->atk2 = $atk2;
    //     $this->atk3 = $atk3;
    //     $this->atk4 = $atk4;
    //     $this->atk_special = $atk_special;
    // }

    public function getId(){
        return $this->id;
    }
    
    public function getNome(){
        return $this->nome;
    }
    public function getHp(){
        return $this->hp;
    }
    public function getVelocidade(){
        return $this->velocidade;
    }
    public function getDecs(){
        return $this->decs;
    }
    public function getTipo(){
        return $this->tipo;
    }
    public function getSubtipo(){
        return $this->subtipo;
    }
    public function getEndImagem(){
        return $this->imagem;
    }
    public function getAtk1(){
        return $this->atk1;
    }
    public function getAtk2(){
        return $this->atk2;
    }
    public function getAtk3(){
        return $this->atk3;
    }
    public function getAtk4(){
        return $this->atk4;
    }
    public function getAtkSpecial(){
        return $this->atk_special;
    }

    public function setId($id){
        $this->id = $id;
    }
    public function setNome($nome){
        $this->nome = $nome;
    }
    public function setHp($hp){
        $this->hp = $hp;
    }
    public function setVelocidade($velocidade){
        $this->velocidade = $velocidade;
    }
    public function setDecs($decs){
        $this->decs = $decs;
    }
    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
    public function setSubtipo($subtipo){
        $this->subtipo = $subtipo;
    }
    public function setEndImagem($endimagem){
        $this->endimagem = $endimagem;
    }
    public function setAtk1($atk1){
        $this->atk1 = $atk1;
    }
    public function setAtk2($atk2){
        $this->atk2 = $atk2;
    }
    public function setAtk3($atk3){
        $this->atk3 = $atk3;
    }
    public function setAtk4($atk4){
        $this->atk4 = $atk4;
    }
    public function setAtkSpecial($atk_special){
        $this->atk_special = $atk_special;
    }

    public function insert(){
        $pdo = getConnection();
        $sql = "INSERT INTO pokebiers (nome, hp, velocidade, decs, tipo, subtipo, endimagem, atk1, atk2, atk3, atk4, atk_special) VALUES (:n, :hp, :v, :d, :t, :st, :img, :a1, :a2, :a3, :a4, :ats)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            ':n' => $this->nome,
            ':hp' => $this->hp,
            ':v' => $this->velocidade,
            ':d' => $this->decs,
            ':t' => $this->tipo,
            ':st' => $this->subtipo,
            ':img' => $this->endimagem,
            ':a1' => $this->atk1,
            ':a2' => $this->atk2,
            ':a3' => $this->atk3,
            ':a4' => $this->atk4,
            ':ats' => $this->atk_special
        ]);
        if($ok){
            $this->id = $pdo->lastInsertId();
        }
        return $ok;
    }

    public function update(){
        $pdo = getConnection();
        $sql = "UPDATE pokebiers SET nome = :n, hp = :hp, velocidade = :v, decs = :d, tipo = :t, subtipo = :st, endimagem = :img, atk1 = :a1, atk2 = :a2, atk3 = :a3, atk4 = :a4, atk_special = :ats WHERE id_pb = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':n' => $this->nome,
            ':hp' => $this->hp,
            ':v' => $this->velocidade,
            ':d' => $this->decs,
            ':t' => $this->tipo,
            ':st' => $this->subtipo,
            ':img' => $this->endimagem,
            ':a1' => $this->atk1,
            ':a2' => $this->atk2,
            ':a3' => $this->atk3,
            ':a4' => $this->atk4,
            ':ats' => $this->atk_special,
            ':id' => $this->id
        ]);
    }

    public function delete(){
        $pdo = getConnection();
        if(!$this->id) return false;
        $stmt = $pdo->prepare("DELETE FROM pokebiers WHERE id_pb = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public function load(){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pokebiers WHERE id = :id");
        $stmt->execute([':id' => $this->id]);
        if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->nome = $dados['nome'];
            $this->hp = $dados['hp'];
            $this->velocidade = $dados['velocidade'];
            $this->decs = $dados['decs'];
            $this->tipo = $dados['tipo'];
            $this->subtipo = $dados['subtipo'];
            $this->endimagem = $dados['endimagem'];
            $this->atk1 = $dados['atk1'];
            $this->atk2 = $dados['atk2'];
            $this->atk3 = $dados['atk3'];
            $this->atk4 = $dados['atk4'];
            $this->atk_special = $dados['atk_special'];
            return true;
        }
        return false;
    }

    public static function getOne($id){
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pokebiers WHERE id_pb = :id");
        $stmt->execute([':id' => $id]);
        if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $p = new Pokemon();
            $p->setId($dados['id_pb']);
            $p->setNome($dados['nome']);
            $p->setHp($dados['hp']);
            $p->setVelocidade($dados['velocidade']);
            $p->setDecs($dados['decs']);
            $p->setTipo($dados['tipo']);
            $p->setSubtipo($dados['subtipo']);
            $p->setEndImagem($dados['endimagem']);
            $p->setAtk1($dados['atk1']);
            $p->setAtk2($dados['atk2']);
            $p->setAtk3($dados['atk3']);
            $p->setAtk4($dados['atk4']);
            $p->setAtkSpecial($dados['atk_special']);
            return $p;
        }
        return null;
    }

    public static function getALL(){
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM pokebiers");
        $pokemons = [];
        while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $p = new Pokemon();
            $p->setId($dados['id_pb']);
            $p->setNome($dados['nome']);
            $p->setHp($dados['hp']);
            $p->setVelocidade($dados['velocidade']);
            $p->setDecs($dados['decs']);
            $p->setTipo($dados['tipo']);
            $p->setSubtipo($dados['subtipo']);
            $p->setEndImagem($dados['endimagem']);
            $p->setAtk1($dados['atk1']);
            $p->setAtk2($dados['atk2']);
            $p->setAtk3($dados['atk3']);
            $p->setAtk4($dados['atk4']);
            $p->setAtkSpecial($dados['atk_special']);
            $pokemons[] = $p;
        }
        return $pokemons;
    }
}

$p = new Pokemon(); 
$p = Pokemon::getOne(2);
//echo 'DEBUG: Pokemon';
var_dump($p);