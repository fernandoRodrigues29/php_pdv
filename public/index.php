<?php
require_once __DIR__."/../vendor/autoload.php";

use App\Api\apiProdutos;

 $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/',$path);
    $lastPath = end($parts);
    // echo "<pre>";
    // echo "metodo:";
    // var_dump($method);
    // echo "caminho:";
    
    // var_dump($path);
    // echo "partes:";
    // var_dump($parts);
    // echo "ultima parte  :";
    // var_dump($lastPath);
    
    if($parts[1] == 'api'){
        if($parts[2] == 'produto'){
             $apiProdutor = new apiProdutos();
                if($method == 'POST'){
                    $apiProdutor->cadastrar();
                }
                if($method == 'PUT'){
                    $apiProdutor->atualizar();
                }
                if($method == 'DELETE'){
                    $apiProdutor->excluir();
                }
        }else{
            $apiProdutor = new apiProdutos();
            echo $apiProdutor->listar();
        }
    }