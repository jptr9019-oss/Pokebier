<?php
    require_once '../../conexao.php';
    class Mochila {

        private $id;
        private $id_item1;
        private $id_item2;
        private $id_item3;
        private $id_item4;
        private $id_item5;

        public function getId(){
            return $this->id;
        }
        public function getIdItem1(){
            return $this->id_item1;
        }
        public function getIdItem2(){
            return $this->id_item2;
        }
        public function getIdItem3(){
            return $this->id_item3;
        }
        public function getIdItem4(){
            return $this->id_item4;
        }
        public function getIdItem5(){
            return $this->id_item5;
        }

        public function setId($id){
            $this->id = $id;
        }
        public function setIdItem1($id_item1){
            $this->id_item1 = $id_item1;
        }
        public function setIdItem2($id_item2){
            $this->id_item2 = $id_item2;
        }
        public function setIdItem3($id_item3){
            $this->id_item3 = $id_item3;
        }
        public function setIdItem4($id_item4){
            $this->id_item4 = $id_item4;
        }
        public function setIdItem5($id_item5){
            $this->id_item5 = $id_item5;
        }

        public function insert(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("INSERT INTO mochila (id_itens, id_itens_2, id_itens_3, id_itens_4, id_itens_5) VALUES (:id_item1, :id_item2, :id_item3, :id_item4, :id_item5)");
            $ok = $stmt->execute([
                ':id_item1' => $this->id_item1,
                ':id_item2' => $this->id_item2,
                ':id_item3' => $this->id_item3,
                ':id_item4' => $this->id_item4,
                ':id_item5' => $this->id_item5
            ]);
            if($ok){
                $this->id = $pdo->lastInsertId();
            }
            return $ok;
        }

        public function update(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("UPDATE mochila SET id_itens = :id_item1, id_itens_2 = :id_item2, id_itens_3 = :id_item3, id_itens_4 = :id_item4, id_itens_5 = :id_item5 WHERE id_mochi = :id");
            $ok = $stmt->execute([
                ':id_item1' => $this->id_item1,
                ':id_item2' => $this->id_item2,
                ':id_item3' => $this->id_item3,
                ':id_item4' => $this->id_item4,
                ':id_item5' => $this->id_item5,
                ':id' => $this->id
            ]);
            return $ok;
        }

        public function load(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM mochila WHERE id_mochi = :id");
            $stmt->execute([':id' => $this->id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->id_item1 = $dados['id_itens'];
                $this->id_item2 = $dados['id_itens_2'];
                $this->id_item3 = $dados['id_itens_3'];
                $this->id_item4 = $dados['id_itens_4'];
                $this->id_item5 = $dados['id_itens_5'];
                return true;
            }
            return false;
        }

        public function delete(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("DELETE FROM mochila WHERE id_mochi = :id");
            return $stmt->execute([':id' => $this->id]);
        }

        public static function getOne($id){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM mochila WHERE id_mochi = :id");
            $stmt->execute([':id' => $id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $mochila = new Mochila();
                $mochila->setId($dados['id_mochi']);
                $mochila->setIdItem1($dados['id_itens']);
                $mochila->setIdItem2($dados['id_itens_2']);
                $mochila->setIdItem3($dados['id_itens_3']);
                $mochila->setIdItem4($dados['id_itens_4']);
                $mochila->setIdItem5($dados['id_itens_5']);
                return $mochila;
            }
            return null;
        }

        public static function getAll(){
            $pdo = getConnection();
            $stmt = $pdo->query("SELECT * FROM mochila");
            $mochilas = [];
            while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $mochila = new Mochila();
                $mochila->setId($dados['id_mochi']);
                $mochila->setIdItem1($dados['id_itens']);
                $mochila->setIdItem2($dados['id_itens_2']);
                $mochila->setIdItem3($dados['id_itens_3']);
                $mochila->setIdItem4($dados['id_itens_4']);
                $mochila->setIdItem5($dados['id_itens_5']);
                $mochilas[] = $mochila;
            }
            return $mochilas;
        }
    }
?>