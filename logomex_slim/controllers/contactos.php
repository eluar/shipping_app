<?php

// handle GET requests for /contactos
//$app->get('/contactos', function () {  
function getContactos() {
  $app = \Slim\Slim::getInstance();
  // query database for all contactos
  $contactos = R::findAll('contactos', 'ORDER BY nombre'); 
  //die(var_dump($contactos));
  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //$arContactos = R::exportAll($contactos);
  
  foreach ($contactos as $contacto) {
      //var_dump($i);
      //die(var_dump($contacto->clientes));    
      $contacto->clientes = $contacto->clientes;
  }
  
  //var_dump($arContactos); die();
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($contactos));
}//});

//$app->get('/contactos/justnames/',function() use ($app){
function getContactosJustNames($clientid=null){
  $app = \Slim\Slim::getInstance();
  if ($clientid === null)
    $rows = R::getAll("select c.id, c.clientes_id, c.nombre from contactos c");
  else
    $rows = R::getAll("select c.id, c.clientes_id, c.nombre from contactos c where c.clientes_id=?", array($clientid));
  $contactos = R::convertToBeans( 'contactos', $rows );
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
    
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($contactos));
}//);

//$app->get('/contactos/justnames/:clientid',function($clientid) use ($app){
function getContactosJustNamesByClientId($clientid) {
  $rows = R::getAll("select c.id, c.clientes_id, c.nombre from contactos c where c.clientes_id=?", array($clientid));
  $contactos = R::convertToBeans( 'contactos', $rows );
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
    
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($contactos));
}//});

//$app->get('/contactos/total/', function() {
function getContactosTotal () {
  $app = \Slim\Slim::getInstance();
  $total = R::dispense('total');
  $total->total = R::count('contactos');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
}//});

//$app->get('/contactos/page/:pagenumber', function ($pagenumber) {  
function getContactosByPage ($pagenumber) {
  $app = \Slim\Slim::getInstance();
  // query database for all contactos
  $contactos = R::findAll('contactos', 'ORDER BY nombre LIMIT ?,8', array($pagenumber*8-8));
  //die(var_dump($contactos));
  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //$arContactos = R::exportAll($contactos);
  
  //die(var_dump($contactos));
  //die(var_dump($arContactos));
  
  foreach ($contactos as $contacto) {
      //var_dump($i);
      //die(var_dump($contacto->clientes));    
      $contacto->clientes = $contacto->clientes;
  }
  
  //var_dump($arContactos); die();
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($contactos));
}//});

//$app->get('/contactos/:id',function($id){
function getContactosById($id){
  $app = \Slim\Slim::getInstance();
  $contacto = R::findOne('contactos', 'id=?', array($id));
  try {
    if ($contacto) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($contacto)[0]);
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

// Handle GET by contact name
//$app->get('/contactos/name/:name',function($name){
function getContactosByName($name){
  $app = \Slim\Slim::getInstance();
  $contacto = R::findOne('contactos', 'nombre=?', array($name));
  try {
    if ($contacto) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($contacto)[0]);
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
}//});

// handle POST requests to /contactos
//$app->post('/contactos', function () {    
function addContactos() {
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store contacto record
    $contacto = R::dispense('contactos');
    $contacto->clientes_id = (int)$input->clientes_id;
    $contacto->nombre = (string)$input->nombre;
    $contacto->telefono = (int)$input->telefono;
    $contacto->extension = (string)$input->extension;
    $contacto->celular = (int)$input->celular;
    $contacto->email = (string)$input->email;
    $contacto->comentarios = (string)$input->comentarios;
    $contacto->activo = 1;
    $id = R::store($contacto);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($contacto)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//});


// handle PUT requests to /contactos/:id
//$app->put('/contactos/:id', function ($id) {    
function updateContactosById($id) {
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single contacto
    $contacto = R::findOne('contactos', 'id=?', array($id));  
    
    // store modified contacto
    // return JSON-encoded response body
    if ($contacto) {      
      $contacto->clientes_id = (string)$input->clientes_id;
      $contacto->nombre = (string)$input->nombre;
      $contacto->telefono = (string)$input->telefono;
      $contacto->extension = (string)$input->extension;
      $contacto->celular = (string)$input->celular;
      $contacto->email = (string)$input->email;
      $contacto->comentarios = (string)$input->comentarios;
      $contacto->activo = 1;
      
      R::store($contacto);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($contacto)[0]);
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

// handle DELETE requests to /contactos/:id
//$app->delete('/contactos/:id', function ($id) {    
function deleteContactos($id) {
  $app = \Slim\Slim::getInstance();
  try {
    // query database for cliente
    $request = $app->request();
    $contacto = R::findOne('contactos', 'id=?', array($id));  
    
    // delete contacto
    if ($contacto) {
      R::trash($contacto);
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