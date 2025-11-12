<?php
namespace App\Models;
 use PDO;
 use PDOException;
 use App\Models\Constantes;

 class ProdutosV2
 {
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = new PDO('sqlite:'.Constantes::DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function listarTudo() :array {
      try {
         $stmt = $this->pdo->query("SELECT id, name, price, barcode FROM products");
         return [
            'status'=>200,
            'data'=>$stmt->fetchAll(PDO::FETCH_ASSOC)
         ];
         
      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }
    public function cadastrar(array $dados): array{
      //validação de formulario
      if(empty($dados['name']) && empty($dados['barcode']) ){
         return [
           'status'=>400,
           'msg'=>"Campo `nome` e obrigatorio" 
         ];
      }

      if(!$this->verificarCadastroUnico($dados['barcode'])){
         return [
           'status'=>409,
           'msg'=>"Codigo de barras já cadastrado" 
         ];
      }

      //executar codigo 
      try {
         $stmt = $this->pdo->prepare('INSERT INTO products (name, price, barcode)
                VALUES (:name, :price, :barcode)');
         $stmt->bindValue(':name',trim($dados['name']), PDO::PARAM_STR);
         $stmt->bindValue(':price',trim($dados['price']), PDO::PARAM_STR);
         $stmt->bindValue(':barcode',trim($dados['barcode']), PDO::PARAM_STR);
      
         if($stmt->execute()){
            return [
               'status'=>200,
               'msg'=>'Produto cadastrado com sucesso',
               'id'=>$this->pdo->lastInsertId()
            ];
         }

         return [
            'status'=>500,
            'msg'=>'Erro ao cadastar o produto'
         ];

      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }

    public function pesquisar(string $pesquisa): array{
      //executar codigo 
      try {
         $stmt = $this->pdo->prepare('SELECT * FROM products WHERE name LIKE :name');
         $stmt->bindValue(':name','%'.trim($pesquisa).'%', PDO::PARAM_STR);
         $stmt->execute();
            return [
               'status'=>200,
               'msg'=>'Pesquisa Realizada com sucesso',
               'data'=>$stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
         
      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }
    public function listarUm(int $id): array{
            //executar codigo 
      try {
         $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
         $stmt->bindValue(':id',$id, PDO::PARAM_INT);
         $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
               if(!$result){
                     return [
                           'status'=>404,
                           'msg'=>'Produto não encontrado',
                        ];
               }
              return [
                'status' => 200,
                'msg' => 'Produto encontrado',
                'data' => $result
              ]; 
         
      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }
    public function editar(array $dados): array{
           //validação de formulario
      if(empty($dados['id']) && empty($dados['nome']) ){
         return [
           'status'=>400,
           'msg'=>"Campo `nome` e  `id` é obrigatorio" 
         ];
      }

      //executar codigo 
      try {
         $campos = [];
         $valores = [':id' => intval($dados['id'])];

         if(!empty($dados['name'])){
            $campos[] = 'name= :name';
            $valores[':name'] = trim($dados['name']);
         }

         if(!empty($dados['price'])){
            $campos[] = 'price= :price';
            $valores[':price'] = trim($dados['price']);
         }

         if(!empty($dados['barcode'])){
            $campos[] = 'barcode= :barcode';
            $valores[':barcode'] = trim($dados['barcode']);
         }

         if(empty($campos)){
            return [
               'status' => 400,
               'msg' => 'Nenhum campo válido fornecido para atualização'
            ];
         }

         $sql = 'UPDATE products SET '. implode(', ',$campos).' WHERE id = :id';
        
         $stmt = $this->pdo->prepare($sql);
                         
         //loop para preencher os dados
         foreach($valores as $key=>$value){
            // $paramType = str_contains($key, 'price') ? PDO::PARAM_STR : PDO::PARAM_STR;
            // $stmt->bindValue($key, $value, $paramType);
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
         }
         
         if($stmt->execute()){
            return [
               'status'=>200,
               'msg'=>'Produto atualizado com sucesso',
            ];
         }
        
         return [
            'status'=>500,
            'msg'=>'Erro ao atualizar o produto'
         ];

      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }
    public function excluir(int $id): array{
      //executar codigo 
      try {
         $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
         $stmt->bindValue(':id',$id, PDO::PARAM_INT);
         $stmt->execute();
         
         if($stmt->rowCount() === 0){
            return [
               'status'=>404,
               'msg'=>'Produto não encontrado',
            ];
         }

         return [
            'status'=>200,
            'msg'=>'Produto Excluido com sucesso'
         ];

      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }
    private function verificarCadastroUnico(string $barcode, ?int $excludeId = null): bool{
      try {
         $sql = 'SELECT id FROM products WHERE barcode = :barcode';
            if($excludeId !== null){
               $sql .= ' AND id != :id'; 
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':barcode', $barcode, PDO::PARAM_STR);
               if($excludeId !== null){
                  $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
               }
                  $stmt->execute();
                     return !$stmt->fetch();
         
      } catch (PDOException $e) {
         throw new \Exception('Erro ao verificar código de barras: '. $e->getMessage());
         
      }
    }
 }
