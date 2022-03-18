<?php

namespace App\Models;

use CodeIgniter\Model;
use MongoDB;

class NadaModel extends Model
{
  private $logs_coll;

  function __construct() {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $this->logs_coll = $cliente->testdb->Logs;
  }


  public function testdb(){
    var_dump($this->logs_coll);
  }


  public function insertarLog($data){
    $insertOneResult = $this->logs_coll->insertOne($data);
    $inserted_count = $insertOneResult->getInsertedCount();

    if($inserted_count != 0){
      return $insertOneResult->getInsertedId();
    } else {
      return false;
    }
  }


  public function obtenerLog($id){
    $oid = new MongoDB\BSON\ObjectID($id);
    $document = $this->logs_coll->findOne(['_id' => $oid]);

    return $document;
  }


  public function modificarLog($id, $data){
    $oid = new MongoDB\BSON\ObjectID($id);
    $updateOneResult = $this->logs_coll->updateOne(['_id' => $oid], ['$set' => $data]);
    $modified_count = $updateOneResult->getModifiedCount();

    if($modified_count != 0){
      return $modified_count;
    } else {
      return false;
    }
  }


  public function eliminarLog($id){
    $oid = new MongoDB\BSON\ObjectID($id);
    $deleteResult = $this->logs_coll->deleteOne(['_id' => $oid]);

    return $deleteResult->getDeletedCount();
  }
}
