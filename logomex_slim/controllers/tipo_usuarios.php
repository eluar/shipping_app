<?php

// handle GET requests for /articles
$app->get('/tipo_usuario', function () use ($app) {  
  // query database for all articles
  $tipo_usuarios = R::findAll('tipo_usuario', 'ORDER BY tipo_usuario'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($tipo_usuarios));
});

$app->get('/tipo_usuario/:id',function($id) use ($app){
  $tipo_usuario = R::findOne('tipo_usuario', 'id=?', array($id));
  try {
    if ($tipo_usuario) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($tipo_usuario)[0]);
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


// handle POST requests to /articles
$app->post('/tipo_usuario', function () use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store article record
    $tipo_usuario = R::dispense('tipo_usuario');
    $tipo_usuario->tipo_usuario = (string)$input->tipo_usuario;
    $tipo_usuario->descripcion = (string)$input->descripcion;
    $id = R::store($tipo_usuario);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($tipo_usuario)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /articles/:id
$app->put('/tipo_usuario/:id', function ($id) use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single article
    $tipo_usuario = R::findOne('tipo_usuario', 'id=?', array($id));  
    
    // store modified article
    // return JSON-encoded response body
    if ($tipo_usuario) {      
      $tipo_usuario->tipo_usuario = (string)$input->tipo_usuario;
      $tipo_usuario->descripcion = (string)$input->descripcion;
      R::store($tipo_usuario);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($tipo_usuario)[0]);
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

// handle DELETE requests to /tipo_usuario/:id
$app->delete('/tipo_usuario/:id', function ($id) use ($app) {    
  try {
    // query database for cliente
    $request = $app->request();
    $tipo_usuario = R::findOne('tipo_usuario', 'id=?', array($id));  
    
    // delete tipo_usuario
    if ($tipo_usuario) {
      R::trash($tipo_usuario);
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