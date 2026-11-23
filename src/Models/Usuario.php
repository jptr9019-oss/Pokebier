<?php
    require_once __DIR__ . '/../../conexao.php';
    class Usuario {

        private $id;
        private $apelido;
        private $senha;
        private $dinheiro;
        private $id_mochila;
        private $id_equipe;

        public function getId(){
            return $this->id;
        }
        public function getApelido(){
            return $this->apelido;
        }
        public function getSenha(){
            return $this->senha;
        }
        public function getDinheiro(){
            return $this->dinheiro;
        }
        public function getIdMochila(){
            return $this->id_mochila;
        }
        public function getIdEquipe(){
            return $this->id_equipe;
        }

        public function setId($id){
            $this->id = $id;
        }
        public function setApelido($apelido){
            $this->apelido = $apelido;
        }
        public function setSenha($senha){
            $this->senha = $senha;
        }
        public function setDinheiro($dinheiro){
            $this->dinheiro = $dinheiro;
        }
        public function setIdMochila($id_mochila){
            $this->id_mochila = $id_mochila;
        }
        public function setIdEquipe($id_equipe){
            $this->id_equipe = $id_equipe;
        }

        public function insert($pdo = null){
            // Se não receber uma conexão, cria uma normalmente
            $pdo = $pdo ?? getConnection();

            $stmt = $pdo->prepare("
                INSERT INTO Usuario
                (apelido, senha, dinheiro, id_mochi, id_equipe)
                VALUES
                (:apelido, :senha, :dinheiro, :id_mochila, :id_equipe)
            ");

            $ok = $stmt->execute([
                ':apelido' => $this->apelido,
                ':senha' => $this->senha,
                ':dinheiro' => $this->dinheiro,
                ':id_mochila' => $this->id_mochila,
                ':id_equipe' => $this->id_equipe
            ]);

            if($ok){
                $this->id = $pdo->lastInsertId();
            }

            return $ok;
        }

        public function update(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("UPDATE usuario SET apelido = :apelido, senha = :senha, dinheiro = :dinheiro, id_mochi = :id_mochila, id_equipe = :id_equipe WHERE id_usuario = :id");
            $ok = $stmt->execute([
                ':apelido' => $this->apelido,
                ':senha' => $this->senha,
                ':dinheiro' => $this->dinheiro,
                ':id_mochila' => $this->id_mochila,
                ':id_equipe' => $this->id_equipe,
                ':id' => $this->id
            ]);
            return $ok;
        }

        public function load(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
            $stmt->execute([':id' => $this->id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->apelido = $dados['apelido'];
                $this->senha = $dados['senha'];
                $this->dinheiro = $dados['dinheiro'];
                $this->id_mochila = $dados['id_mochi'];
                $this->id_equipe = $dados['id_equipe'];
                return true;
            }
            return false;
        }

        public function delete(){
            $pdo = getConnection();
            $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = :id");
            return $stmt->execute([':id' => $this->id]);
        }

        public static function getOne($id){
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
            $stmt->execute([':id' => $id]);
            if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $usuario = new Usuario();
                $usuario->setId($dados['id_usuario']);
                $usuario->setApelido($dados['apelido']);
                $usuario->setSenha($dados['senha']);
                $usuario->setDinheiro($dados['dinheiro']);
                $usuario->setIdMochila($dados['id_mochi']);
                $usuario->setIdEquipe($dados['id_equipe']);
                return $usuario;
            }
            return null;
        }

        public static function getAll(){
            $pdo = getConnection();
            $stmt = $pdo->query("SELECT * FROM usuario");
            $usuarios = [];
            while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                $usuario = new Usuario();
                $usuario->setId($dados['id_usuario']);
                $usuario->setApelido($dados['apelido']);
                $usuario->setSenha($dados['senha']);
                $usuario->setDinheiro($dados['dinheiro']);
                $usuario->setIdMochila($dados['id_mochi']);
                $usuario->setIdEquipe($dados['id_equipe']);
                $usuarios[] = $usuario;
            }
            return $usuarios;
        }

        public static function ganhaDinheiro($id_usuario, $quantia){
            $pdo = getConnection();
            $stmt = $pdo->prepare("UPDATE usuario SET dinheiro = dinheiro + :quantia WHERE id_usuario = :id");
            return $stmt->execute([
                ':quantia' => $quantia,
                ':id' => $id_usuario
            ]);
        }

        public static function gastaDinheiro($id_usuario, $quantia){
            $pdo = getConnection();
            $stmt = $pdo->prepare("UPDATE usuario SET dinheiro = dinheiro - :quantia WHERE id_usuario = :id");
            return $stmt->execute([
                ':quantia' => $quantia,
                ':id' => $id_usuario
            ]);
        }
    }
    
?>