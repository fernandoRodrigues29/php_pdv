<?php
namespace App\Models;
 use PDO;
 use PDOException;
 use App\Models\Constantes;

 class Produtos
 {
    private PDO $pdo;
    public function __construct()
    {
        
    }
    public function listarTudo() :array {}
    public function cadastrar(array $dados): array{}
    public function pesquisar(string $pesquisa): array{}
    public function listarUm(int $id): array{}
    public function editar(array $dados): array{}
    public function excluir(int $id): array{}
    private function verificarCadastroUnico(string $barcode, ?int $excludeId = null): bool{}
 }
