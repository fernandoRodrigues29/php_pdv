<?php

use PHPUnit\Framework\TestCase;
use App\Models\Produtos;

class ProdutosTest extends TestCase
{
    private PDO $pdo;
    private Produtos $model;

    protected function setup(): void
    {
        $this->pdo = new PDO('sqlite::memory');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        //tabela fake
        $this->pdo->exec("
            CREATE TABLE products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(50),
                price VARCHAR(50),
                barcode VARCHAR(50) UNIQUE
            );
        ");
        $this->model = new Produtos($this->pdo);
    }

    public function testeListaTudoVazio(){
        $result = $this->model->listarTudo();

        $this->assertEquals(200, $result['status']);
        $this->assertCount(0, $result['data']);

    }

    public function testCadastrarProdutoComSucesso()
    {
        $dados = [
            'name'=>'Coca-cola',
            'price'=>'10.00',
            'barcode'=>'12345'
        ];

        $result = $this->model->cadastrar($dados);

        $this->assertEquals(200,$result['status']);
        $this->assertEquals('Produto cadastrado com sucesso',$result['msg']);
    }

    public function testCadastrarBarcodeDuplicado()
    {
        //cadastra1
          // cadastra 1
        $this->model->cadastrar([
            'name' => 'Teste',
            'price' => '5',
            'barcode' => '9999'
        ]);

        // tenta duplicado
        $result = $this->model->cadastrar([
            'name' => 'Outro Produto',
            'price' => '6',
            'barcode' => '9999'
        ]);

        $this->assertEquals(409,$result['status']);
    }

    public function testExcluirProdutoNaoEncontrado()
    {
       $result = $this->model->excluir(999);
           $this->assertEquals(404,$result['status']);
           $this->assertEquals('Produto não encontrado', $result['msg']);

    }
}