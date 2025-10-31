<?php
namespace App\Controllers;

use App\Models\Produtos;

class ProdutoController{
   private $pridutoModel;
    public function __construct()
    {
        $this->produtoModel = new Produtos();
    }

    public function handleRequest(){
        $metodo = $_SERVER['REQUEST_METHOD'];

        switch ($metodo) {
            case 'GET':
                $this->get();
            break;
            case 'POST':
            break;
            case 'PUT':
            break;
            case 'DELETE':
            break;
            default:
                http_response_code(405);
                echo json_encode(['error'=>'Metodo não habilitado']);
            break;
        }
    }
    private function get(){
        $rs = $this->produtoModel->listarTudo();
        echo json_encode([
            'status'=>200,
            'data'=>$rs,
            'total' => count($rs)
        ]);
    }
    
    public function listarProdutos(){
        $rs = $this->produtoModel->listarTudo();
        return  json_encode([
            'status'=>200,
            'data'=>$rs,
            'total' => count($rs)
        ]);
    }

    private function post(){}
    private function delete(){}

    public function listarTudo(){
        $mApp = new Produtos();
          return $mApp->listarTudo();
            //echo json_encode(['controller/InicioController']);
    }
    public function pesquisar($pesquisa){

    }
    public function excluir($id){
        $mApp = new Produtos();
            return $mApp->excluir($id);
    }
    public function editar($dados){
         $mApp = new Produtos();
            return $mApp->editar($dados);
    }
    public function cadastrar($dados){
                 $mApp = new Produtos();
                    return $mApp->cadastrar($dados);
    }
}

