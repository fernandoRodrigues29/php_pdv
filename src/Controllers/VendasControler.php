<?php
namespace App\Controllers;

use App\Models\Sales;

class VendasController{

    public $vendaModel;
    public function __construct()
    {
        $this->vendaModel = new Sales();
    }
    public function listar(){
        $rs = $this->vendaModel->listarTudo();
        return  json_encode([
            'status'=>200,
            'data'=>$rs,
            'total' => count($rs)
        ]);
    }
    
    public function pesquisar($pesquisa){
        return $this->vendaModel->pesquisar($pesquisa);
    }
    
    public function excluir($id){
        return $this->vendaModel->excluir($id);
    }
    
    public function editar($dados){
        return $this->vendaModel->editar($dados);
    }
    
    public function cadastrar($dados){
        return $this->vendaModel->cadastrar($dados);
    }

}

