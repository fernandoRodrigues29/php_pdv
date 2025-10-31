<?php
namespace App\Api;
use App\Controllers\ProdutoController;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class apiProdutos{
    
    public function listar(){
        try {
            $pd = new ProdutoController();
              return $pd->listarProdutos();
        } catch (\Throwable $th) {
            echo json_encode(['code'=>500,'msg'=>'error:'.$th->getMessage()]);
        }
    }
    
    public function atualizar(){
        try {
            $pd = new ProdutoController();
                $dados = json_decode(file_get_contents('php://input'), true);  
                    return $pd->editar($dados);
        } catch (\Throwable $th) {
            echo json_encode(['code'=>500,'msg'=>'error:'.$th->getMessage()]);
        }
    }
    
    public function cadastrar(){
        try {
            $pd = new ProdutoController();
              $dados = json_decode(file_get_contents('php://input'), true);  
                // var_dump($dados);
                return $pd->cadastrar($dados);
        } catch (\Throwable $th) {
            echo json_encode(['code'=>500,'msg'=>'error:'.$th->getMessage()]);
        }
    }

    public function excluir(){
        try {
            $pd = new ProdutoController();
              $dados = json_decode(file_get_contents('php://input'), true);  
                // var_dump($dados);
                return $pd->excluir($dados['id']);
        } catch (\Throwable $th) {
            echo json_encode(['code'=>500,'msg'=>'error:'.$th->getMessage()]);
        }
    }

}

//checar
