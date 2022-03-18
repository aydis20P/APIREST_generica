<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Nada extends ResourceController
{
  use ResponseTrait;
  protected $eventos_model;

  function __construct() {
    $this->nada_model = model(NadaModel::class);
  }


  public function index(){ //GET
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    return $response;
  }


  public function create(){ //POST
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    $params = $this->request->getJSON(true);

    $hoy = new \DateTime();
    $hoy->setTimeZone(new \DateTimeZone('America/Mexico_City'));
    $hoy = $hoy->format('Y-m-d H:i:s');

    $data = array(
      'params' => $params,
      'fecha' => $hoy
    );

    $response_model = $this->nada_model->insertarLog($data);
    if($response_model){
      $response = $this->respondCreated(array('insertado_id' => $response_model));
    } else {
      $response = $this->fail("No se logró insertar", 400);
    }

    return $response;
  }


  public function update($id = null){ //PUT
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

    return $response;
  }


  public function delete($id = null){  //DELETE
    // response con fail por defecto
    $response = $this->fail("Ocurrió un error", 400);

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
