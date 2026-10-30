<?php
class Item{
    require_once '../conexao.php';

    private $nome;

    public function __construct(){
        $this->nome = $nome;
    }

    public function ListarTodos(){
        $stmt = $this->pdo->query("SELECT nome FROM itens_cura");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

echo 'DEBUG: Item';