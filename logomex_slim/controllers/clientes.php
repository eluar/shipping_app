<?php

// handle GET requests for /clientes
//$app->get('/clientes', function () use ($app) {  
function getClientes() {
  $app = \Slim\Slim::getInstance();
  // query database for all clientes
  $clientes = R::findAll('clientes', 'ORDER BY nombre'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($clientes));
}//);

//$app->get('/clientes/justnames', function () use ($app) {
function getClientesJustNames() {
  $app = \Slim\Slim::getInstance(); 
  // query database for all clientes
  //$clientes = R::findAll('clientes', 'ORDER BY nombre'); 
  $clientes = R::getAll('SELECT id, nombre FROM clientes ORDER BY nombre');

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  //echo json_encode(R::exportAll($clientes));
  echo json_encode($clientes);
}//);

//$app->get('/clientes/total/', function() use ($app) {
function getCLientesTotal() {
  $app = \Slim\Slim::getInstance();
  $total = R::dispense('total');
  $total->total = R::count('clientes');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
}//);

// handle GET requests for client's ID
//$app->get('/clientes/:id',function($id) use ($app){
function getClientesById($id) {
  $app = \Slim\Slim::getInstance();
  $cliente = R::findOne('clientes', 'id=?', array($id));
  try {
    if ($cliente) {
      $app->response()->header('Content-Type', 'application/json');
      //echo json_encode(R::exportAll($cliente));
      echo json_encode(R::exportAll($cliente)[0]);
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

// Handle GET by cliente name
//$app->get('/clientes/name/:name',function($name) use ($app){
function getClientesByName($name) {
  $app = \Slim\Slim::getInstance();
  $cliente = R::findOne('clientes', 'nombre=?', array($name));
  try {
    if ($cliente) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($cliente)[0]);
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

//$app->get('/clientes/page/:pagenumber', function ($pagenumber) use ($app) { 
function getClientesByPage($pagenumber) { 
  $app = \Slim\Slim::getInstance(); 
  // query database for all clientes
  $clientes = R::findAll('clientes', 'ORDER BY nombre LIMIT ?,8', array($pagenumber*8-8));
  //die(var_dump($clientes));
  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //$arContactos = R::exportAll($clientes); 
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($clientes));
}//);

// handle POST requests to /clientes
//$app->post('/clientes', function () use ($app) {
function addClientes () {
  $app = \Slim\Slim::getInstance();   
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store cliente record
    $cliente = R::dispense('clientes');
    $cliente->nombre = (string)$input->nombre;
    $cliente->descripcion = (string)$input->descripcion;
    $cliente->telefono = (string)$input->telefono;
    $cliente->activo = 1;
    $id = R::store($cliente);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($cliente)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//);


// handle PUT requests to /clientes/:id
//$app->put('/clientes/:id', function ($id) use ($app) {
function updateClientesById ($id) {
  $app = \Slim\Slim::getInstance();   
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single cliente
    $cliente = R::findOne('clientes', 'id=?', array($id));  
    
    // store modified cliente
    // return JSON-encoded response body
    if ($cliente) {      
      $cliente->nombre = (string)$input->nombre;
      $cliente->descripcion = (string)$input->descripcion;
      $cliente->telefono = (string)$input->telefono;
      $cliente->activo = (string)$input->activo;
      R::store($cliente);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($cliente)[0]);
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

// handle DELETE requests to /clientes/:id
//$app->delete('/clientes/:id', function ($id) use ($app) {
function deleteClientes($id) {
  $app = \Slim\Slim::getInstance();
  try {
    // query database for cliente
    $request = $app->request();
    $cliente = R::findOne('clientes', 'id=?', array($id));  
    
    // delete cliente
    if ($cliente) {
      R::trash($cliente);
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