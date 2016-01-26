<?php

// handle GET requests for /articles
//$app->get('/segmentos', function () {  
function getSegmentos() {  
  $app = \Slim\Slim::getInstance();
  // query database for all articles
  $segmentos = R::findAll('segmento', 'ORDER BY nombre'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');

  foreach ($segmentos as $segmento) {
      $segmento->clientes = $segmento->clientes;
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($segmentos));
}//});

//$app->get('/segmentos/:id',function($id) {
function getSegmentosById($id) {  
  $app = \Slim\Slim::getInstance();
  $segmento = R::findOne('segmento', 'id=?', array($id));
  try {
    if ($segmento) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($segmento)[0]);
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

//$app->get('/segmentos/justnames/',function() {
function getSegmentosJustNamesAll() {  
  $app = \Slim\Slim::getInstance();
  $rows = R::getAll("select s.id, s.clientes_id, s.nombre from segmento s");
  $segmentos = R::convertToBeans( 'segmentos', $rows );
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
    
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($segmentos));
}//});

//$app->get('/segmentos/justnames/:clientid',function($clientid) {
function getSegmentosJustNames($clientid) {  
  $app = \Slim\Slim::getInstance();
  $rows = R::getAll("select s.id, s.clientes_id, s.nombre from segmento s where s.clientes_id=?", array($clientid));
  $segmentos = R::convertToBeans( 'segmentos', $rows );
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
    
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($segmentos));
}//});

//$app->get('/segmentos/total/', function() {
function getSegmentosTotal() {  
  $app = \Slim\Slim::getInstance();
  $total = R::dispense('total');
  $total->total = R::count('segmento');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
}//});

//$app->get('/segmentos/page/:pagenumber', function ($pagenumber) {  
function getSegmentosByPage($pagenumber) {  
  $app = \Slim\Slim::getInstance();
  // query database for all segmentos
  $segmentos = R::findAll('segmento', 'ORDER BY nombre LIMIT ?,8', array($pagenumber*8-8));  
  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');  
  foreach ($segmentos as $segmento) {
      $segmento->clientes = $segmento->clientes;
  } 
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($segmentos));
}//});

// Handle GET by contact name
//$app->get('/segmentos/name/:name',function($name) {
function getSegmentosByName($name) {  
  $app = \Slim\Slim::getInstance();
  $segmento = R::findOne('segmentos', 'nombre=?', array($name));
  try {
    if ($segmento) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($segmento)[0]);
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

// handle POST requests to /articles
//$app->post('/segmentos', function () {    
function addSegmentos() {    
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store article record
    $segmento = R::dispense('segmento');
    $segmento->clientes_id = (string)$input->clientes_id;
    $segmento->nombre = (string)$input->nombre;
    $segmento->descripcion = (string)$input->descripcion;
    $id = R::store($segmento);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($segmento)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
}//});


// handle PUT requests to /articles/:id
//$app->put('/segmentos/:id', function ($id) {
function updateSegmentosById($id) {    
  $app = \Slim\Slim::getInstance();
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single article
    $segmento = R::findOne('segmento', 'id=?', array($id));  
    
    // store modified article
    // return JSON-encoded response body
    if ($segmento) {      
      $segmento->clientes_id = (string)$input->clientes_id;
      $segmento->nombre = (string)$input->nombre;
      $segmento->descripcion = (string)$input->descripcion;
            
      R::store($segmento);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($segmento)[0]);
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

// handle DELETE requests to /segmentos/:id
//$app->delete('/segmentos/:id', function ($id) {    
function deleteSegmentos($id) {    
  $app = \Slim\Slim::getInstance();
  try {
    // query database for cliente
    $request = $app->request();
    $segmento = R::findOne('segmento', 'id=?', array($id));  
    
    // delete segmento
    if ($segmento) {
      R::trash($segmento);
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