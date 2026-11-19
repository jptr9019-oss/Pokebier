<?php
    require_once '../../conexao.php';
    class Equipe {
        
        private $id;
        private $id_pokemon1;
        private $id_pokemon2;
        private $id_pokemon3;
        private $id_pokemon4;
        private $id_pokemon5;
        private $id_pokemon6;

        public function getId(){
            return $this->id;
        }
        public function getIdPokemon1(){
            return $this->id_pokemon1;
        }
        public function getIdPokemon2(){
            return $this->id_pokemon2;
        }
        public function getIdPokemon3(){
            return $this->id_pokemon3;
        }
        public function getIdPokemon4(){
            return $this->id_pokemon4;
        }
        public function getIdPokemon5(){
            return $this->id_pokemon5;
        }
        public function getIdPokemon6(){
            return $this->id_pokemon6;
        }

        public function setId($id){
            $this->id = $id;
        }
        public function setIdPokemon1($id_pokemon1){
            $this->id_pokemon1 = $id_pokemon1;
        }
        public function setIdPokemon2($id_pokemon2){
            $this->id_pokemon2 = $id_pokemon2;
        }
        public function setIdPokemon3($id_pokemon3){
            $this->id_pokemon3 = $id_pokemon3;
        }
        public function setIdPokemon4($id_pokemon4){
            $this->id_pokemon4 = $id_pokemon4;
        }
        public function setIdPokemon5($id_pokemon5){
            $this->id_pokemon5 = $id_pokemon5;
        }
        public function setIdPokemon6($id_pokemon6){
            $this->id_pokemon6 = $id_pokemon6;
        }

        public function insert(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("INSERT INTO equipe (id_pb, id_pb_2, id_pb_3, id_pb_4, id_pb_5, id_pb_6) VALUES (:id_pokemon1, :id_pokemon2, :id_pokemon3, :id_pokemon4, :id_pokemon5, :id_pokemon6)");
            $ok = $stmt->execute([
                ':id_pokemon1' => $this->id_pokemon1,
                ':id_pokemon2' => $this->id_pokemon2,
                ':id_pokemon3' => $this->id_pokemon3,
                ':id_pokemon4' => $this->id_pokemon4,
                ':id_pokemon5' => $this->id_pokemon5,
                ':id_pokemon6' => $this->id_pokemon6
            ]);
            if($ok){
                $this->id = $pdo->lastInsertId();
            }
            return $ok;
        }

        public function update(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("UPDATE equipe SET id_pb = :id_pokemon1, id_pb_2 = :id_pokemon2, id_pb_3 = :id_pokemon3, id_pb_4 = :id_pokemon4, id_pb_5 = :id_pokemon5, id_pb_6 = :id_pokemon6 WHERE id_equipe = :id");
            $ok = $stmt->execute([
                ':id_pokemon1' => $this->id_pokemon1,
                ':id_pokemon2' => $this->id_pokemon2,
                ':id_pokemon3' => $this->id_pokemon3,
                ':id_pokemon4' => $this->id_pokemon4,
                ':id_pokemon5' => $this->id_pokemon5,
                ':id_pokemon6' => $this->id_pokemon6,
                ':id' => $this->id
            ]);
            return $ok;
        }

        public function load(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM equipe WHERE id_equipe = :id");
            $stmt->execute([':id' => $this->id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->id_pokemon1 = $dados['id_pb'];
                $this->id_pokemon2 = $dados['id_pb_2'];
                $this->id_pokemon3 = $dados['id_pb_3'];
                $this->id_pokemon4 = $dados['id_pb_4'];
                $this->id_pokemon5 = $dados['id_pb_5'];
                $this->id_pokemon6 = $dados['id_pb_6'];
                return true;
            }
            return false;
        }

        public function delete(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("DELETE FROM equipe WHERE id_equipe = :id");
            $ok = $stmt->execute([
                ':id' => $this->id
            ]);
            return $ok;
        }

        public static function getOne($id){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM equipe WHERE id_equipe = :id");
            $stmt->execute([':id' => $id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
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
            return null;
        }

        public static function getAll(){
            $pdo = getConnection();
            $stmt = $pdo->query("SELECT * FROM equipe");
            $equipes = [];
            while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $equipe = new Equipe();
                $equipe->setId($dados['id_equipe']);
                $equipe->setIdPokemon1($dados['id_pb']);
                $equipe->setIdPokemon2($dados['id_pb_2']);
                $equipe->setIdPokemon3($dados['id_pb_3']);
                $equipe->setIdPokemon4($dados['id_pb_4']);
                $equipe->setIdPokemon5($dados['id_pb_5']);
                $equipe->setIdPokemon6($dados['id_pb_6']);
                $equipes[] = $equipe;
            }
            return $equipes;
        }
    }

    $i = new Equipe();
    $i = Equipe::getAll();
    var_dump($i);
?>