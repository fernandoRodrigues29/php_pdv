<?php

namespace App\Models;

use PDO;
use App\Models\Constantes;
use PDOException;

class Produtos
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:" . Constantes::DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function listarTudo()
    {
        try {
            $stmt = $this->pdo->query("SELECT id,name,price,barcode FROM products");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['satatus' => 500, 'msg' => $e->getMessage()]);
        }
    }

    public function cadastrar($dados)
    {
        //verificar
        if ($this->verificarCadastroUnico($dados['barcode'])) {
            try {
                //insert prepare
                $stmt = $this->pdo->prepare('
                INSERT INTO products (name, price, barcode)
                VALUES (:name , :price, :barcode)
            ');
                $stmt->bindValue(':name', trim($dados['name']));
                $stmt->bindValue(':price', floatval($dados['price']));
                $stmt->bindValue(':barcode', trim($dados['barcode']));

                if ($stmt->execute()) {
                    $lastId = $this->pdo->lastInsertId();
                    return [
                        'status' => 201,
                        'msg' => 'Produto cadastrado com sucesso',
                        'id' => $lastId,
                    ];
                } else {
                    return [
                        'status' => 500,
                        'msg' => 'Erro ao cadastrar produto'
                    ];
                }
            } catch (PDOException $pe) {
                return [
                    'status' => 500,
                    'msg' => 'Erro:' . $pe->getMessage()
                ];
            }
        }
    }
    public function pesquisar($pesquisa)
    {
        try {
            //insert prepare
            $stmt = $this->pdo->prepare('SELECT * FROM products  WHERE name like :name');
            $stmt->bindValue(':name', trim($pesquisa));
            if ($stmt->execute()) {

                return [
                    'status' => 201,
                    'msg' => 'listar',
                    'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                ];
            } else {
                return [
                    'status' => 500,
                    'msg' => 'Erro ao pesquisar produto'
                ];
            }
        } catch (PDOException $pe) {
            return [
                'status' => 500,
                'msg' => 'Erro:' . $pe->getMessage()
            ];
        }
    }
    public function listarUm($id)
    {
        try {
            //insert prepare
            $stmt = $this->pdo->prepare('SELECT * FROM products  WHERE id =:id');
            $stmt->bindValue(':id', intval($id));
            if ($stmt->execute()) {

                return [
                    'status' => 201,
                    'msg' => 'resgatar um',
                    'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                ];
            } else {
                return [
                    'status' => 500,
                    'msg' => 'Erro ao pesquisar produto'
                ];
            }
        } catch (PDOException $pe) {
            return [
                'status' => 500,
                'msg' => 'Erro:' . $pe->getMessage()
            ];
        }
    }
    public function editar($dados)
    {
        //verificar
        if ($this->verificarCadastroUnico($dados['barcode'])) {
            try {
                $campos = [];
                $valores = [':id' => $dados['id']];

                if (isset($dados['name'])) {
                    $campos[] = "name = :name";
                    $valores[':name'] = trim($dados['name']);
                }

                if (isset($dados['price'])) {
                    $campos[] = "price = :price";
                    $valores[':price'] = trim($dados['price']);
                }

                if (isset($dados['barcode'])) {
                    $campos[] = "barcode = :barcode";
                    $valores[':barcode'] = trim($dados['barcode']);
                }

                $sql = "UPDATE products SET " . implode(', ', $campos) . " WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);

                foreach ($valores as $key => $value) {
                    $stmt->bindValue($key, $value);
                }

                if ($stmt->execute()) {
                    return [
                        'status' => 200,
                        'msg' => 'Produto atualizado com sucesso'
                    ];
                } else {
                    return [
                        'status' => 500,
                        'msg' => 'Error ao atualizar o produto'
                    ];
                }
            } catch (PDOException $pe) {
                return [
                    'status' => 500,
                    'msg' => 'Erro:' . $pe->getMessage()
                ];
            }
        }
    }
    public function excluir($id)
    {
        try {
            //insert prepare
            $stmt = $this->pdo->prepare('DELETE  FROM products WHERE id =:id');
            $stmt->bindValue(':id', intval($id));
            if ($stmt->execute()) {

                return [
                    'status' => 201,
                    'msg' => 'Produto Excluido com sucesso'
                ];
            } else {
                return [
                    'status' => 500,
                    'msg' => 'Erro ao excluir o produto'
                ];
            }
        } catch (PDOException $pe) {
            return [
                'status' => 500,
                'msg' => 'Erro:' . $pe->getMessage()
            ];
        }
    }

    public function verificarCadastroUnico($barcode)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM products WHERE barcode = :barcode");
        $stmt->bindValue(':barcode', $barcode);
        $stmt->execute();

        if ($stmt->fetch()) {
            return false;
        }
        return true;
    }
}
