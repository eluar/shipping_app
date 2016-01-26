<?php

// handle GET requests for /choferes
$app->get('/choferes', function () { 
  $app = \Slim\Slim::getInstance();
  // query database for all choferes
  $choferes = R::findAll('choferes', 'ORDER BY nombre'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  foreach ($choferes as $chofer) {
      $chofer->nombre_apellidos = $chofer->nombre . ' ' . $chofer->apellidos;
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($choferes));
});

$app->get('/choferes/total/', function() {
  $app = \Slim\Slim::getInstance();
  $total = R::dispense('total');
  $total->total = R::count('choferes');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
});

$app->get('/choferes/page/:pagenumber', function ($pagenumber) {
  $app = \Slim\Slim::getInstance();  
  // query database for all contactos
  $chofers = R::findAll('choferes', 'ORDER BY id LIMIT ?,8', array($pagenumber*8-8));
  
  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  
  foreach ($choferes as $chofer) {
      $chofer->nombre_apellidos = $chofer->nombre . ' ' . $chofer->apellidos;
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($chofers));
});

// handle GET requests for client's ID
$app->get('/choferes/:id',function($id){
  $app = \Slim\Slim::getInstance();
  $chofer = R::findOne('choferes', 'id=?', array($id));
  try {
    if ($chofer) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($chofer)[0]);
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

// Handle GET by chofer name
$app->get('/choferes/name/:name',function($name){
  $app = \Slim\Slim::getInstance();
  $chofer = R::findOne('choferes', 'nombre=?', array($name));
  try {
    if ($chofer) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($chofer)[0]);
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

// handle POST requests to /choferes
$app->post('/choferes', function () {
  $app = \Slim\Slim::getInstance();    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store chofer record
    $chofer = R::dispense('choferes');
    $chofer->nombre = (string)$input->nombre;
    $chofer->apellidos = (string)$input->apellidos;
    $chofer->telefono = (string)$input->telefono;
    $chofer->activo = 1;
    $id = R::store($chofer);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($chofer)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /choferes/:id
$app->put('/choferes/:id', function ($id) {
  $app = \Slim\Slim::getInstance();    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single chofer
    $chofer = R::findOne('choferes', 'id=?', array($id));  
    
    // store modified chofer
    // return JSON-encoded response body
    if ($chofer) {      
      $chofer->nombre = (string)$input->nombre;
      $chofer->apellidos = (string)$input->apellidos;
      $chofer->telefono = (string)$input->telefono;
      $chofer->activo = (string)$input->activo;
      R::store($chofer);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($chofer)[0]);
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

// handle DELETE requests to /choferes/:id
$app->delete('/choferes/:id', function ($id) {
  $app = \Slim\Slim::getInstance();    
  try {
    // query database for chofer
    $request = $app->request();
    $chofer = R::findOne('choferes', 'id=?', array($id));  
    
    // delete chofer
    if ($chofer) {
      R::trash($chofer);
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