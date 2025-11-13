<?php 
namespace App\Models;
use PDO;
use PDOException;
use App\Models\Constantes;

class Sales{
    private PDO $pdo;
    public function __construct()
        {
            $this->pdo = new PDO('sqlite:'.Constantes::DB_PATH);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        
    public function listar():array{
        try {
            $stmt = $this->pdo->query("SELECT id,total,payment_method from sales");
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
    public function cadastrar(array $dados):array{
         //validação de formulario
        if(empty($dados['total']) && empty($dados['payment_method']) ){
            return [
            'status'=>400,
            'msg'=>"Campo `total` e `payment_method` obrigatorio" 
            ];
        }
      //executar codigo 
      try {
         $stmt = $this->pdo->prepare('INSERT INTO sales (total, payment_method)
                VALUES (:name, :price, :barcode)');
         
         $stmt->bindValue(':total',trim($dados['total']), PDO::PARAM_INT);
         $stmt->bindValue(':payment_method',trim($dados['payment_method']), PDO::PARAM_STR);
        
         if($stmt->execute()){
            return [
               'status'=>200,
               'msg'=>'venda cadastrado com sucesso',
               'id'=>$this->pdo->lastInsertId()
            ];
         }

         return [
            'status'=>500,
            'msg'=>'Erro ao vender o produto'
         ];

      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }
    public function pesquisar(string $pesquisa):array{
              try {
         $stmt = $this->pdo->prepare('SELECT * FROM sales WHERE name LIKE :name');
         $stmt->bindValue(':total','%'.trim($pesquisa).'%', PDO::PARAM_INT);
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
    public function listarUm(int $id):array{
              try {
         $stmt = $this->pdo->prepare('SELECT * FROM sales WHERE id = :id');
         $stmt->bindValue(':id',$id, PDO::PARAM_INT);
         $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
               if(!$result){
                     return [
                           'status'=>404,
                           'msg'=>'Venda não encontrado',
                        ];
               }
              return [
                'status' => 200,
                'msg' => 'venda encontrado',
                'data' => $result
              ]; 
         
      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }    
    public function editar(array $dados):array{
      if(empty($dados['total']) && empty($dados['payment_method']) ){
         return [
           'status'=>400,
           'msg'=>"Campo `total` e  `payment_method` é obrigatorio" 
         ];
      }

      //executar codigo 
      try {
         $campos = [];
         $valores = [':id' => intval($dados['id'])];

         if(!empty($dados['total'])){
            $campos[] = 'total= :total';
            $valores[':total'] = trim($dados['total']);
         }

         if(!empty($dados['payment_method'])){
            $campos[] = 'payment_method= :payment_method';
            $valores[':payment_method'] = trim($dados['payment_method']);
         }

         if(empty($campos)){
            return [
               'status' => 400,
               'msg' => 'Nenhum campo válido fornecido para atualização'
            ];
         }

         $sql = 'UPDATE sales SET '. implode(', ',$campos).' WHERE id = :id';
        
         $stmt = $this->pdo->prepare($sql);
                         
         //loop para preencher os dados
         foreach($valores as $key=>$value){
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
         }
         
         if($stmt->execute()){
            return [
               'status'=>200,
               'msg'=>'venda atualizado com sucesso',
            ];
         }
        
         return [
            'status'=>500,
            'msg'=>'Erro ao atualizar o venda'
         ];

      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }    
    public function excluir(int $id):array{
     try {
         $stmt = $this->pdo->prepare('DELETE FROM sales WHERE id = :id');
         $stmt->bindValue(':id',$id, PDO::PARAM_INT);
         $stmt->execute();
         
         if($stmt->rowCount() === 0){
            return [
               'status'=>404,
               'msg'=>'Venda não encontrado',
            ];
         }

         return [
            'status'=>200,
            'msg'=>'Venda Excluido com sucesso'
         ];

      } catch (PDOException $e) {
         return [
           'status'=>500,
           'msg'=>'Error:'.$e->getMessage() 
         ];
      }
    }    
}