<?php
    require_once '../../conexao.php';
    class Ataques {

        private $id;
        private $nome;
        private $damage;
        
        public function getId(){
            return $this->id;
        }
        public function getNome(){
            return $this->nome;
        }
        public function getDamage(){
            return $this->damage;
        }

        public function setId($id){
            $this->id = $id;
        }
        public function setNome($nome){
            $this->nome = $nome;
        }
        public function setDamage($damage){
            $this->damage = $damage;
        }

        public function insert(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("INSERT INTO atks (nome, dam) VALUES (:nome, :damage)");
            $ok = $stmt->execute([
                ':nome' => $this->nome,
                ':damage' => $this->damage
            ]);
            if($ok){
                $this->id = $pdo->lastInsertId();
            }
            return $ok;
        }

        public function update(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("UPDATE atks SET nome = :nome, dam = :damage WHERE id_atk = :id");
            $ok = $stmt->execute([
                ':nome' => $this->nome,
                ':damage' => $this->damage,
                ':id' => $this->id
            ]);
            return $ok;
        }

        public function load(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM atks WHERE id_atk = :id");
            $stmt->execute([':id' => $this->id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->nome = $dados['nome'];
                $this->damage = $dados['dam'];
                return true;
            }
            return false;
        }

        public function delete(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("DELETE FROM atks WHERE id_atk = :id");
            return $stmt->execute([':id' => $this->id]);
        }

        public static function getOne($id){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM atks WHERE id_atk = :id");
            $stmt->execute([':id' => $id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $atk = new Ataques();
                $atk->setId($dados['id_atk']);
                $atk->setNome($dados['nome']);
                $atk->setDamage($dados['dam']);
                return $atk;
            }
            return null;
        }

        public static function getAll(){
            $pdo = getConnection();
            $stmt = $pdo->query("SELECT * FROM atks");
            $atks = [];
            while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $atk = new Ataques();
                $atk->setId($dados['id_atk']);
                $atk->setNome($dados['nome']);
                $atk->setDamage($dados['dam']);
                $atks[] = $atk;
            }
            return $atks;
        }
    }

    $i = new Ataques();
    $i = Ataques::getAll();
    var_dump($i);
?>