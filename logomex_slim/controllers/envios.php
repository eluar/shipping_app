<?php

// handle GET requests for /envios
function getEnvios() {
//$app->get('/envios', function () use ($app) {  
  $app = \Slim\Slim::getInstance();
  // query database for all envios
  $envios = R::findAll('envios', 'ORDER BY nombre'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  
  foreach ($envios as $envio) {
      $envio->clientes = $envio->clientes;
      $envio->segmento = $envio->segmento;
      $envio->subsegmento = $envio->subsegmento;
      $envio->contactos = $envio->contactos;
      $envio->ubicaciones_origen = R::findOne('ubicaciones', 'id=?', array($envio->ubicaciones_origen_id));
      $envio->ubicaciones_destino = R::findOne('ubicaciones', 'id=?', array($envio->ubicaciones_destino_id));
      $envio->vehiculos = $envio->vehiculos;
      $envio->choferes = $envio->choferes;
      $envio->statuses = $envio->statuses;
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($envios));
//});
}

function getEnviosTotal() {
  $app = \Slim\Slim::getInstance();
  //$app->get('/envios/total/', function() use ($app) {
  $total = R::dispense('total');
  $total->total = R::count('envios');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
//});
}

function getEnviosByPage($pagenumber) {
//$app->get('/envios/page/:pagenumber', function ($pagenumber) use ($app) {  
  $app = \Slim\Slim::getInstance();
  // query database for all contactos
  $envios = R::findAll('envios', 'ORDER BY id LIMIT ?,8', array($pagenumber*8-8));
  
// send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  
  //die(print_r($envios[0]->ubicaciones));
  
  
  foreach ($envios as $envio) {
      $envio->clientes = $envio->clientes;
      $envio->segmento = $envio->segmento;
      $envio->subsegmento = $envio->subsegmento;
      $envio->contactos = $envio->contactos;
      $envio->ubicaciones_origen = R::findOne('ubicaciones', 'id=?', array($envio->ubicaciones_origen_id));
      $envio->ubicaciones_destino = R::findOne('ubicaciones', 'id=?', array($envio->ubicaciones_destino_id));
      $envio->vehiculos = $envio->vehiculos;
      $envio->choferes = $envio->choferes;
      $envio->statuses = $envio->statuses;
      
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($envios));
//});
}

function getEnviosById ($id) {
//$app->get('/envios/:id',function($id) use ($app){
  $app = \Slim\Slim::getInstance();
  $envio = R::findOne('envios', 'id=?', array($id));
  try {
    if ($envio) {
        
      $envio->clientes = $envio->clientes;
      $envio->segmento = $envio->segmento;
      $envio->subsegmento = $envio->subsegmento;
      $envio->contactos = $envio->contactos;
      $envio->ubicaciones_origen = R::findOne('ubicaciones', 'id=?', array($envio->ubicaciones_origen_id));
      $envio->ubicaciones_destino = R::findOne('ubicaciones', 'id=?', array($envio->ubicaciones_destino_id));
      $envio->vehiculos = $envio->vehiculos;
      $envio->choferes = $envio->choferes;
      $envio->statuses = $envio->statuses;
        
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($envio)[0]);
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
//});
}


// Handle GET by contact name
//$app->get('/envios/service/:serviceId',function($serviceId) use ($app){
function getEnviosByServiceId($serviceId) {
  $app = \Slim\Slim::getInstance();
  $envio = R::findOne('envios', 'numero_servicio=?', array($serviceId));
  try {
    if ($envio) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($envio)[0]);
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
    //echo json_encode($app->response()->status(404));
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//);


// handle POST requests to /envios
//$app->post('/envios', function () use ($app) {    
function addEnvios () {  
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store envio record
    $envio = R::dispense('envios');
    $envio->numero_servicio = (int)$input->numero_servicio;
    $envio->fecha_hora_solicitud = $input->fecha_hora_solicitud;
    $envio->clientes_id = (int)$input->clientes_id;
    $envio->segmento_id = (int)$input->segmento_id;
    $envio->subsegmento_id = (int)$input->subsegmento_id;
    $envio->contactos_id = (int)$input->contactos_id;
    $envio->ubicaciones_origen_id = (int)$input->ubicaciones_origen_id;
    $envio->ubicaciones_destino_id = (int)$input->ubicaciones_destino_id;
    $envio->fecha_hora_servicio = $input->fecha_hora_servicio;
    $envio->folio = (string)$input->folio;
    $envio->vehiculos_id = (int)$input->vehiculos_id;
    $envio->choferes_id = (int)$input->choferes_id;
    $envio->observaciones = (string)$input->observaciones;
    $envio->statuses_id = (int)$input->statuses_id;
    $id = R::store($envio);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($envio)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//);


// handle PUT requests to /envios/:id
//$app->put('/envios/:id', function ($id) use ($app) {    
function updateEnviosById($id) { 
  $app = \Slim\Slim::getInstance();   
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single envio
    $envio = R::findOne('envios', 'id=?', array($id));  
    
    // store modified envio
    // return JSON-encoded response body
    if ($envio) {      
      $envio->numero_servicio = (int)$input->numero_servicio;
      $envio->fecha_hora_solicitud = (string)$input->fecha_hora_solicitud; //date("YYYY/mm/dd hh:mm:ss");
      $envio->clientes_id = (int)$input->clientes_id;
      $envio->segmento_id = (int)$input->segmento_id;
      $envio->subsegmento_id = (int)$input->subsegmento_id;
      $envio->contactos_id = (int)$input->contactos_id;
      $envio->ubicaciones_origen_id = (int)$input->ubicaciones_origen_id;
      $envio->ubicaciones_destino_id = (int)$input->ubicaciones_destino_id;
      $envio->fecha_hora_servicio = (string)$input->fecha_hora_servicio;
      $envio->folio = (string)$input->folio;
      $envio->vehiculos_id = (int)$input->vehiculos_id;
      $envio->choferes_id = (int)$input->choferes_id;
      $envio->observaciones = (string)$input->observaciones;
      $envio->statuses_id = (int)$input->statuses_id;
      
      R::store($envio);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($envio)[0]);
    } else {
      throw new ResourceNotFoundException();    
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//);

// handle DELETE requests to /envios/:id
//$app->delete('/envios/:id', function ($id) use ($app) {
function deleteEnvios($id) {
  $app = \Slim\Slim::getInstance();
  try {
    // query database for cliente
    $request = $app->request();
    $envio = R::findOne('envios', 'id=?', array($id));  
    
    // delete envio
    if ($envio) {
      R::trash($envio);
      $app->response()->status(204);
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//);