<?php

namespace App\Models;

use App\Models\Constantes;
use PDO;
use PDOException;

 class Database {
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:".Constantes::DB_PATH);
        // $this->pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $this->criarBanco();
    }

    public function criarBanco(){
    //    var_dump(Constantes::DB_PATH);
        $script = Constantes::INIT_SCRIPT;
            $sqlq = file_get_contents($script);
            try {
               if($this->pdo->exec($sqlq)){
                echo json_encode(['status'=>200,'msg'=>'banco populado com sucesso']);
               }
            } catch (PDOException $e) {
                echo json_encode(['status'=>500,'msg'=>$e->getMessage()]);
            }
    }
 }
?>