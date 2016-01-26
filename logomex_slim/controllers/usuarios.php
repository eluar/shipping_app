<?php

// handle GET requests for /articles
//$app->get('/usuarios', function () {  
function getUsuarios() {
  $app = \Slim\Slim::getInstance();
  // query database for all articles
  $usuarios = R::findAll('usuarios', 'ORDER BY user_name'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($usuarios));
}//});

//$app->get('/usuarios/:id',function($id) {
function getUsuariosById($id) {
  $app = \Slim\Slim::getInstance();
  $usuario = R::findOne('usuarios', 'id=?', array($id));
  try {
    if ($usuario) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($usuario)[0]);
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//});

//$app->get('/usuarios/:id',function($id) {
function getUsuariosByLogin($username, $password) {
  $app = \Slim\Slim::getInstance();
  $usuario = R::findOne('usuarios', 'user_name=? && user_password=?', array($username, $password));
  try {
    if ($usuario) {
      $usuario->tipo_usuario = $usuario->tipo_usuario;
      $app->response()->header('Content-Type', 'application/json');
      //echo json_encode(R::exportAll($usuario)[0]);
      //die(var_dump($usuario));
      return R::exportAll($usuario)[0];
    } else {
      return false;
      //throw new ResourceNotFoundException();
    }
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
    return false;
  }
}//});


// handle POST requests to /articles
//$app->post('/usuarios', function () {    
function addUsuarios() {    
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store article record
    $usuario = R::dispense('usuarios');
    $usuario->user_name = (string)$input->user_name;
    $usuario->tipo_usuario_id = (string)$input->tipo_usuario_id;
    $usuario->user_password = (string)$input->user_password;
    $usuario->user_real_name = (string)$input->user_real_name;
    $usuario->status = 0;
    $id = R::store($usuario);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($usuario)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//});


// handle PUT requests to /articles/:id
//$app->put('/usuarios/:id', function ($id) {    
function updateUsuariosById($id) {
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single article
    $usuario = R::findOne('usuarios', 'id=?', array($id));  
    
    // store modified article
    // return JSON-encoded response body
    if ($usuario) {      
      $usuario->user_name = (string)$input->user_name;
      $usuario->user_password = (string)$input->user_password;
      $usuario->status = 0;
      R::store($usuario);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($usuario)[0]);
    } else {
      throw new ResourceNotFoundException();    
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//});

// handle DELETE requests to /usuario/:id
//$app->delete('/usuarios/:id', function ($id) {    
function deleteUsuarios($id) {
  $app = \Slim\Slim::getInstance();
  try {
    // query database for cliente
    $request = $app->request();
    $usuario = R::findOne('usuarios', 'id=?', array($id));  
    
    // delete usuario
    if ($usuario) {
      R::trash($usuario);
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
}//});