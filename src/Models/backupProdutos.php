<?php

namespace App\Models;
use PDO;
use App\Models\Constantes;
use PDOException;

class Produtos{
    private $pdo;
    
    public function __construct()
    {
         $this->pdo = new PDO("sqlite:".Constantes::DB_PATH);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                         
    }

    public function listarTudo(){
        try {
            $stmt = $this->pdo->query("SELECT name,price,barcode FROM products");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['satatus'=>500,'msg'=>$e->getMessage()]);
        }
    }
    public function buscarPorId($id){
        try {
            $stmt = $this->pdo->prepare("SELECT id, name, price, barcode FROM products WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['status'=>500,'msg'=>$e->getMessage()]);
            return false;
        }
    }

    public function cadastrar($dados){
        try {
            // Validar dados obrigatórios
            if (!isset($dados['name']) || !isset($dados['price']) || !isset($dados['barcode'])) {
                return ['status' => 400, 'msg' => 'Dados incompletos. Campos obrigatórios: name, price, barcode'];
            }

            // Verificar se barcode já existe
            $stmt = $this->pdo->prepare("SELECT id FROM products WHERE barcode = :barcode");
            $stmt->bindValue(':barcode', $dados['barcode']);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                return ['status' => 409, 'msg' => 'Código de barras já existe'];
            }

            // Inserir novo produto
            $stmt = $this->pdo->prepare("
                INSERT INTO products (name, price, barcode) 
                VALUES (:name, :price, :barcode)
            ");
            
            $stmt->bindValue(':name', trim($dados['name']));
            $stmt->bindValue(':price', floatval($dados['price']));
            $stmt->bindValue(':barcode', trim($dados['barcode']));
            
            if ($stmt->execute()) {
                $lastId = $this->pdo->lastInsertId();
                return [
                    'status' => 201, 
                    'msg' => 'Produto cadastrado com sucesso',
                    'id' => $lastId,
                    'product' => $this->buscarPorId($lastId)
                ];
            } else {
                return ['status' => 500, 'msg' => 'Erro ao cadastrar produto'];
            }
            
        } catch (PDOException $e) {
            return ['status' => 500, 'msg' => $e->getMessage()];
        }
    }

    public function editar($dados){
        try {
            // Validar dados obrigatórios
            if (!isset($dados['id'])) {
                return ['status' => 400, 'msg' => 'ID do produto é obrigatório'];
            }

            // Verificar se produto existe
            $produtoExistente = $this->buscarPorId($dados['id']);
            if (!$produtoExistente) {
                return ['status' => 404, 'msg' => 'Produto não encontrado'];
            }

            // Verificar se barcode já existe em outro produto
            if (isset($dados['barcode'])) {
                $stmt = $this->pdo->prepare("SELECT id FROM products WHERE barcode = :barcode AND id != :id");
                $stmt->bindValue(':barcode', $dados['barcode']);
                $stmt->bindValue(':id', $dados['id'], PDO::PARAM_INT);
                $stmt->execute();
                
                if ($stmt->fetch()) {
                    return ['status' => 409, 'msg' => 'Código de barras já existe em outro produto'];
                }
            }

            // Construir query dinamicamente baseada nos campos fornecidos
            $campos = [];
            $valores = [':id' => $dados['id']];

            if (isset($dados['name'])) {
                $campos[] = "name = :name";
                $valores[':name'] = trim($dados['name']);
            }
            
            if (isset($dados['price'])) {
                $campos[] = "price = :price";
                $valores[':price'] = floatval($dados['price']);
            }
            
            if (isset($dados['barcode'])) {
                $campos[] = "barcode = :barcode";
                $valores[':barcode'] = trim($dados['barcode']);
            }

            if (empty($campos)) {
                return ['status' => 400, 'msg' => 'Nenhum campo para atualizar'];
            }

            $sql = "UPDATE products SET " . implode(', ', $campos) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($valores as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            if ($stmt->execute()) {
                return [
                    'status' => 200, 
                    'msg' => 'Produto atualizado com sucesso',
                    'product' => $this->buscarPorId($dados['id'])
                ];
            } else {
                return ['status' => 500, 'msg' => 'Erro ao atualizar produto'];
            }
            
        } catch (PDOException $e) {
            return ['status' => 500, 'msg' => $e->getMessage()];
        }
    }

    public function excluir($id){
        try {
            // Verificar se produto existe
            $produtoExistente = $this->buscarPorId($id);
            if (!$produtoExistente) {
                return ['status' => 404, 'msg' => 'Produto não encontrado'];
            }

            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return [
                    'status' => 200, 
                    'msg' => 'Produto excluído com sucesso',
                    'deleted_product' => $produtoExistente
                ];
            } else {
                return ['status' => 500, 'msg' => 'Erro ao excluir produto'];
            }
            
        } catch (PDOException $e) {
            return ['status' => 500, 'msg' => $e->getMessage()];
        }
    }

    // Método auxiliar para criar a tabela se não existir
    public function criarTabela(){
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    price REAL NOT NULL,
                    barcode TEXT UNIQUE NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ";
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            echo json_encode(['status'=>500,'msg'=>$e->getMessage()]);
            return false;
        }
    }

    // Método para popular com dados de exemplo
    public function popularDadosExemplo(){
        try {
            $produtosExemplo = [
                ['name' => 'Notebook Dell', 'price' => 2500.00, 'barcode' => '1234567890123'],
                ['name' => 'Mouse Logitech', 'price' => 89.90, 'barcode' => '1234567890124'],
                ['name' => 'Teclado Mecânico', 'price' => 299.99, 'barcode' => '1234567890125']
            ];

            foreach ($produtosExemplo as $produto) {
                $stmt = $this->pdo->prepare("
                    INSERT OR IGNORE INTO products (name, price, barcode) 
                    VALUES (:name, :price, :barcode)
                ");
                $stmt->execute($produto);
            }

            return ['status' => 200, 'msg' => 'Dados de exemplo inseridos'];
        } catch (PDOException $e) {
            return ['status' => 500, 'msg' => $e->getMessage()];
        }
    }


}
