<?php

// handle GET requests for /ubicaciones
$app->get('/ubicaciones', function () use ($app) {  
  // query database for all ubicaciones
  $ubicaciones = R::findAll('ubicaciones', 'ORDER BY nombre'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($ubicaciones));
});

$app->get('/ubicaciones/justnames', function () use ($app) {  
  // query database for all ubicaciones
  $ubicaciones = R::getAll('SELECT id, nombre FROM ubicaciones ORDER BY nombre');
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode($ubicaciones);
});

// handle GET requests for client's ID
$app->get('/ubicaciones/:id',function($id) use ($app){
  $ubicacion = R::findOne('ubicaciones', 'id=?', array($id));
  try {
    if ($ubicacion) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($ubicacion)[0]);
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

// Handle GET by ubicacion name
$app->get('/ubicaciones/name/:name',function($name) use ($app){
  $ubicacion = R::findOne('ubicaciones', 'nombre=?', array($name));
  try {
    if ($ubicacion) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($ubicacion)[0]);
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

// handle POST requests to /ubicaciones
$app->post('/ubicaciones', function () use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store ubicacion record
    $ubicacion = R::dispense('ubicaciones');
    $ubicacion->nombre = (string)$input->nombre;
    $ubicacion->descripcion = (string)$input->descripcion;
    $ubicacion->key_tipo = (string)$input->key_tipo;
    $id = R::store($ubicacion);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($ubicacion)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /ubicaciones/:id
$app->put('/ubicaciones/:id', function ($id) use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single ubicacion
    $ubicacion = R::findOne('ubicaciones', 'id=?', array($id));  
    
    // store modified ubicacion
    // return JSON-encoded response body
    if ($ubicacion) {      
      $ubicacion->nombre = (string)$input->nombre;
      $ubicacion->descripcion = (string)$input->descripcion;
      $ubicacion->key_tipo = (string)$input->key_tipo;
      R::store($ubicacion);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($ubicacion)[0]);
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

// handle DELETE requests to /ubicaciones/:id
$app->delete('/ubicaciones/:id', function ($id) use ($app) {    
  try {
    // query database for ubicacion
    $request = $app->request();
    $ubicacion = R::findOne('ubicaciones', 'id=?', array($id));  
    
    // delete ubicacion
    if ($ubicacion) {
      R::trash($ubicacion);
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