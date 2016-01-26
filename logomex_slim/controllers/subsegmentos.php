<?php

// handle GET requests for /articles
$app->get('/subsegmentos', function () use ($app) {  
  // query database for all articles
  $subsegmentos = R::findAll('subsegmento', 'ORDER BY nombre'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  foreach ($subsegmentos as $subsegmento) {
      $subsegmento->segmento = $subsegmento->segmento;
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($subsegmentos));
});

$app->get('/subsegmentos/:id',function($id) use ($app){
  $subsegmento = R::findOne('subsegmento', 'id=?', array($id));
  try {
    if ($subsegmento) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($subsegmento)[0]);
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

$app->get('/subsegmentos/justnames/',function() use ($app){
  $rows = R::getAll("select s.id, s.segmento_id, s.nombre from subsegmento s");
  $subsegmentos = R::convertToBeans( 'subsegmentos', $rows );
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
    
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($subsegmentos));
});

$app->get('/subsegmentos/justnames/:segmentid',function($segmentid) use ($app){
  $rows = R::getAll("select s.id, s.segmento_id, s.nombre from subsegmento s where s.segmento_id=?", array($segmentid));
  $subsegmentos = R::convertToBeans( 'subsegmentos', $rows );
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
    
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($subsegmentos));
});

$app->get('/subsegmentos/total/', function() use ($app) {
  $total = R::dispense('total');
  $total->total = R::count('subsegmento');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
});

$app->get('/subsegmentos/page/:pagenumber', function ($pagenumber) use ($app) {  
  // query database for all segmentos
  $subsegmentos = R::findAll('subsegmento', 'ORDER BY nombre LIMIT ?,8', array($pagenumber*8-8));  
  // send response header for JSON content type
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');  
  foreach ($subsegmentos as $subsegmento) {
      $subsegmento->segmento = $subsegmento->segmento;
  } 
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($subsegmentos));
});

// Handle GET by contact name
$app->get('/subsegmentos/name/:name',function($name) use ($app){
  $subsegmento = R::findOne('subsegmentos', 'nombre=?', array($name));
  try {
    if ($subsegmento) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($subsegmento)[0]);
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
$app->post('/subsegmentos', function () use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store article record
    $subsegmento = R::dispense('subsegmento');
    $subsegmento->segmento_id = (string)$input->segmento_id;
    $subsegmento->nombre = (string)$input->nombre;
    $subsegmento->descripcion = (string)$input->descripcion;
    $id = R::store($subsegmento);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($subsegmento)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /articles/:id
$app->put('/subsegmentos/:id', function ($id) use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single article
    $subsegmento = R::findOne('subsegmento', 'id=?', array($id));  
    
    // store modified article
    // return JSON-encoded response body
    if ($subsegmento) {      
      $subsegmento->segmento_id = (string)$input->segmento_id;
      $subsegmento->nombre = (string)$input->nombre;
      $subsegmento->descripcion = (string)$input->descripcion;
            
      R::store($subsegmento);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($subsegmento)[0]);
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

// handle DELETE requests to /segmentos/:id
$app->delete('/subsegmentos/:id', function ($id) use ($app) {    
  try {
    // query database for cliente
    $request = $app->request();
    $subsegmento = R::findOne('subsegmento', 'id=?', array($id));  
    
    // delete segmento
    if ($subsegmento) {
      R::trash($subsegmento);
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