<?php
require_once __DIR__."/../vendor/autoload.php";

use App\Api\apiProdutos;

 $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/',$path);
    $lastPath = end($parts);
    
    if($parts[1] == 'api'){
        if($parts[2] == 'produto'){
             $apiProdutor = new apiProdutos();
                if($method == 'POST'){
                    $apiProdutor->cadastrar();
                }
                if($method == 'PUT'){
                   $r = $apiProdutor->atualizar();
                    echo json_encode($r);
                }
                if($method == 'DELETE'){
                    $r = $apiProdutor->excluir();
                        echo json_encode($r);
                }
        }else{
            $apiProdutor = new apiProdutos();
            echo $apiProdutor->listar();
        }
    }