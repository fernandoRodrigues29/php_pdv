<?php
namespace App\Controllers;

use App\Models\Database;
use App\Models\Produtos;

class InicioController{
    public function __construct()
    {
        
    }
    public function inicio(){
        $mApp = new Database();
        $mApp->criarBanco();
        echo json_encode(['controller/InicioController']);
    }
    public function listarTudo(){
        $mApp = new Produtos();
          return $mApp->listarTudo();
            //echo json_encode(['controller/InicioController']);
    }
    public function pesquisar($pesquisa){

    }
    public function excluir($id){}
    public function editar($dados){}
    public function cadastrar($dados){}
}

