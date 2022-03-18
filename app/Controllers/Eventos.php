<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Eventos extends ResourceController
{
  use ResponseTrait;
  protected $eventos_model;

  function __construct() {
    $this->eventos_model = model(EventosModel::class);
  }


  public function index(){ //GET
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    $id = $this->request->getGet('id');
    // validaciones
    if($id){
      $evento = $this->eventos_model->obtenerEvento($id);
      if($evento)
        $response = $this->respond($evento, 200);
      else
        $response = $this->fail("No se encontró el evento", 404);
    } else {
      $response = $this->fail("No id", 400);
    }

    return $response;
  }


  public function create(){ //POST
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    $params = $this->request->getJSON(true);
    // validaciones
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

    return $response;
  }


  public function update($id = null){ //PUT
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    $params = $this->request->getJSON(true);
    // validaciones
    if(
      isset($params)
    ){
      $response_model = $this->eventos_model->modificarEvento($id, $params);
      if($response_model){
        $response = $this->respondCreated(['registros_modificados' => $response_model]);
      } else {
        $response = $this->fail("No se logró modificar", 400);
      }
    } else {
      $response = $this->fail("Faltan parámetros", 400);
    }

    return $response;
  }


  public function delete($id = null){  //DELETE
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    // validaciones
    if(true){
      $response_model = $this->eventos_model->eliminarEvento($id);
      if($response_model){
        $response = $this->respondDeleted(['registros_eliminados' => $response_model], 200);
      } else {
        $response = $this->fail("No se logró eliminar", 400);
      }
    } else {
      $response = $this->fail("Error de validación", 400);
    }

    return $response;
  }
}

/* ***** ROUTES CREADAS POR CI ResourceController *****
$routes->get('eventos/new',             'Eventos::new');
$routes->post('eventos',                'Eventos::create');
$routes->get('eventos',                 'Eventos::index');
$routes->get('eventos/(:segment)',      'Eventos::show/$1');
$routes->get('eventos/(:segment)/edit', 'Eventos::edit/$1');
$routes->put('eventos/(:segment)',      'Eventos::update/$1');
$routes->patch('eventos/(:segment)',    'Eventos::update/$1');
$routes->delete('eventos/(:segment)',   'Eventos::delete/$1');
*/

/* MÉTODOS ÚTILES
 * var_dump($this->request->getMethod()); //obtener método
 * $this->eventos_model->testdb(); //probar db
 * $uri = current_url(true); //obtener url actual
 * var_dump($uri->getQuery()); //obtener query de la uri
 */
