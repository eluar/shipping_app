<?php

// handle GET requests for /articles
$app->get('/tipo_vehiculos', function () use ($app) {  
  // query database for all articles
  $tipo_vehiculos = R::findAll('tipo_vehiculos', 'ORDER BY key_tipo_unidad'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($tipo_vehiculos));
});

$app->get('/tipo_vehiculos/:id',function($id) use ($app){
  $tipo_vehiculo = R::findOne('tipo_vehiculos', 'id=?', array($id));
  try {
    if ($tipo_vehiculo) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($tipo_vehiculo)[0]);
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// Handle GET by vehicules type key name
$app->get('/tipo_vehiculos/name/:key_type',function($key_type) use ($app){
  $tipo_vehiculo = R::findOne('tipo_vehiculos', 'key_tipo_unidad=?', array($key_type));
  try {
    if ($tipo_vehiculo) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($tipo_vehiculo)[0]);
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
});

// handle POST requests to /articles
$app->post('/tipo_vehiculos', function () use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store article record
    $tipo_vehiculo = R::dispense('tipo_vehiculos');
    $tipo_vehiculo->key_tipo_unidad = (string)$input->key_tipo_unidad;
    $tipo_vehiculo->descripcion = (string)$input->descripcion;
    $tipo_vehiculo->activo = 1;
    $id = R::store($tipo_vehiculo);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($tipo_vehiculo)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /articles/:id
$app->put('/tipo_vehiculos/:id', function ($id) use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single article
    $tipo_vehiculo = R::findOne('tipo_vehiculos', 'id=?', array($id));  
    
    // store modified article
    // return JSON-encoded response body
    if ($tipo_vehiculo) {      
      $tipo_vehiculo->key_tipo_unidad = (string)$input->key_tipo_unidad;
      $tipo_vehiculo->descripcion = (string)$input->descripcion;
      $tipo_vehiculo->activo = (string)$input->activo;
      R::store($tipo_vehiculo);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($tipo_vehiculo)[0]);
    } else {
      throw new ResourceNotFoundException();    
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// handle DELETE requests to /tipo_vehiculo/:id
$app->delete('/tipo_vehiculos/:id', function ($id) use ($app) {    
  try {
    // query database for cliente
    $request = $app->request();
    $tipo_vehiculo = R::findOne('tipo_vehiculos', 'id=?', array($id));  
    
    // delete tipo_vehiculo
    if ($tipo_vehiculo) {
      R::trash($tipo_vehiculo);
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
});