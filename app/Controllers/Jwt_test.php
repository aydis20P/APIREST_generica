<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Jwt_test extends ResourceController
{
  use ResponseTrait;
  protected $eventos_model;

  function __construct() {
    $this->eventos_model = model(EventosModel::class);
    helper('jwtcheck');
  }


  /* 
   * Método para obtener un JWT dado
   * el string oid de un usuario existente 
   * en la db de mongo
   */
  public function index(){ //GET
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    $id = $this->request->getGet('id');
    // validaciones
    if($id){
      $jwt = getJwt($id);
      if($jwt){
        $pubKey = file_get_contents("./pubKey");
        openssl_public_encrypt($jwt, $jwt_encrypted, $pubKey);
        $response = $this->respond(['jwt' => base64_encode($jwt_encrypted)], 200);
      }
      else
        $response = $this->failUnauthorized();
    } else {
      $response = $this->fail("No id", 400);
    }

    return $response;
  }


  /* 
   * Método de ejemplo sobre como se consume una API 
   * con el estandar jwt implementado en CI 4
   */
  public function create(){ //POST
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    $res = jwtValidation();
    // validacion de jwt
    if($res === true){
      $params = $this->request->getJSON(true);
      if(
        isset($params['nombre'])
        && isset($params['fecha'])
        && isset($params['horario'])
      ){
        $response_model = $this->eventos_model->insertarEvento($params);
        if($response_model){
          $response = $this->respondCreated(array('insertado_id' => $response_model));
        } else {
          $response = $this->fail("No se logró insertar", 400);
        }
      } else {
        $response = $this->fail("Faltan parámetros", 400);
      }
    } else {
        $response = $this->failUnauthorized($res); //$res == 0 token expirado, $res == -1 token corrupto
    }

    return $response;
  }


  /*
   * Método para generar un nuevo jwt
   * dado otro que ha vencido
   */
  public function update($jwt = null){ //PUT
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    // validaciones
    if(
      isset($jwt)
    ){
      $njwt = refreshJWT($jwt);
      if($njwt != -1){
        $pubKey = file_get_contents("./pubKey");
        openssl_public_encrypt($njwt, $njwt_encrypted, $pubKey);
        $response = $this->respondCreated(['jwt' => base64_encode($njwt_encrypted)]);
      } else {
        $response = $this->failUnauthorized($njwt);
      }
    } else {
      $response = $this->fail("Faltan parámetros", 400);
    }

    return $response;
  }
}
