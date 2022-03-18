<?php

namespace App\Models;

use CodeIgniter\Model;
use MongoDB;

class UsuariosModel extends Model
{
  private $eventos_coll;

  function __construct() {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $this->usuarios_coll = $cliente->testdb->Usuarios;
  }


  public function testdb(){
    var_dump($this->usuarios_coll);
  }

/*
  public function insertarEvento($data){
    $insertOneResult = $this->eventos_coll->insertOne($data);
    $inserted_count = $insertOneResult->getInsertedCount();

    if($inserted_count != 0){
      return $insertOneResult->getInsertedId();
    } else {
      return false;
    }
  }
 */

  public function obtenerUsuario($id){
    $oid = new MongoDB\BSON\ObjectID($id);
    $document = $this->usuarios_coll->findOne(['_id' => $oid]);

    return $document;
  }

/*
  public function modificarEvento($id, $data){
    $oid = new MongoDB\BSON\ObjectID($id);
    $updateOneResult = $this->eventos_coll->updateOne(['_id' => $oid], ['$set' => $data]);
    $modified_count = $updateOneResult->getModifiedCount();

    if($modified_count != 0){
      return $modified_count;
    } else {
      return false;
    }
  }


  public function eliminarEvento($id){
    $oid = new MongoDB\BSON\ObjectID($id);
    $deleteResult = $this->eventos_coll->deleteOne(['_id' => $oid]);

    return $deleteResult->getDeletedCount();
  }
*/
}
