<?php

require_once __DIR__."/../vendor/autoload.php";

use App\Api\apiProdutos;
// use App\Controllers\VendasController;

class ApiRouter
{
    private $method;
    private $path;
    private $parts;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $requestUri = filter_var($_SERVER['REQUEST_URI'] ?? '', FILTER_SANITIZE_URL);
        $this->path = parse_url($requestUri, PHP_URL_PATH);
        $this->parts = explode('/', $this->path);
        
        //remove elementos vazios
        $this->parts = array_filter($this->parts);
        $this->parts = array_values($this->parts);
    }

    public function route() : void
    {
        header('Content-Type: application/json');
        

        // var_dump($this->parts);
        // exit;
    
    
        try {
            //verifica se é uma rota API
            if(($this->parts[1] ?? '') !== 'api'){
                $this->sendError(404, 'Rota não encontrada');
                return;
            }

            $apiProdutos = new apiProdutos();
            // $apiVendas = new VendasController();
            //Roteamento baseado em método HTTP e recurso
            switch (true) {
                case ($this->parts[2] ?? '') == 'produto':
                    $this->handleProdutoRoutes($apiProdutos);
                break;
                // case ($this->parts[1] ?? '') == 'venda':
                //     $this->handleProdutoRoutes($apiVendas);
                // break;
                default:
                    # Lista todos os produtos por padrão
                    if($this->method == 'GET'){
                        echo $apiProdutos->listar();
                    }else{
                        $this->sendError(405,'Método não permitido');
                    }
                break;
            }
        } catch (Exception $e) {
            error_log("Erro na API: ".$e->getMessage());
            $this->sendError(500,'Erro interno do servidor');
        }
    }
    private function handleProdutoRoutes($api): void
    {
        switch ($this->method) {
            case 'POST':
                 $dadosRetornados = $api->cadastrar();
                 echo json_encode($dadosRetornados);
            break;
            case 'PUT':
               echo json_encode($api->atualizar());
            break;
            case 'DELETE':
                echo json_encode($api->excluir());
            break;
            case 'GET':
                //area para adicionar pesquisa
                $api->listar();
            break;
            default:
                $this->sendError(405, 'Método não permitido');
            break;
        }
    }
    private function sendError(int $code, string $message):void
    {
        http_response_code($code);
        echo json_encode(
            [
                'error'=>true,
                'message'=>$message,
                'code'=>$code
            ]
        );
    }
}

$router = new ApiRouter;
$router->route();