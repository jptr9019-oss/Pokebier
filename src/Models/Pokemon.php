<?php
class Pokemon{
    require_once '../conexao.php';

    private $nome;
    private $hp;
    private $velocidade;
    private $decs;
    private $tipo;
    private $subtipo;
    private $imagem;
    private $atk1;
    private $atk2;
    private $atk3;
    private $atk4;
    private $atk_special;

    private $id;


    public function __construct($nome, $hp, $velocidade, $decs, $tipo, $subtipo, $imagem, $atk1, $atk2, $atk3, $atk4, $atk_special){
        $this->nome = $nome;
        $this->hp = $hp;
        $this->velocidade = $velocidade;
        $this->decs = $decs;
        $this->tipo = $tipo;
        $this->subtipo = $subtipo;
        $this->imagem = $imagem;
        $this->atk1 = $atk1;
        $this->atk2 = $atk2;
        $this->atk3 = $atk3;
        $this->atk4 = $atk4;
        $this->atk_special = $atk_special;
    }

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
    public function getImagem(){
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

    public function insert(){

    }

    public function update(){

    }

    public function delete(){

    }

    public function load(){

    }

    public static function getOne($id){

    }

    public static function getALL(){
        return [];
    }
}

$p = new Pokemon(); 
$p2 = Pokemon::getOne(1);
echo 'DEBUG: Pokemon';