<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ProdutoController;
use App\Models\Produtos;

class ProdutoControllerTest extends TestCase
{
    public function testListarProdutosRetornaJson()
    {
        $mock = $this->createMock(Produtos::class);

        $mock->method('listarTudo')->willReturn([
            ['id'=>1,'name'=>'Teste','barcode'=>'111','price'=>'10']
        ]);

        $controller = new ProdutoController($mock);

        $json = $controller->listarProdutos();
        $array = json_decode($json, true);

        $this->assertEquals(200,$array['status']);
        $this->assertEquals(1,$array['total']);
        $this->assertCount(1,$array['data']);

    }
}