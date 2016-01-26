<?php

// handle GET requests for /articles
$app->get('/statuses', function () use ($app) {  
  // query database for all articles
  $statusess = R::findAll('statuses', 'ORDER BY status_key'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($statusess));
});

$app->get('/statuses/:id',function($id) use ($app){
  $statuses = R::findOne('statuses', 'id=?', array($id));
  try {
    if ($statuses) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($statuses)[0]);
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

$app->get('/statuses/key/:key',function($key) use ($app){
  $statuses = R::findOne('statuses', 'status_key=?', array($key));
  try {
    if ($statuses) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($statuses)[0]);
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
$app->post('/statuses', function () use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store article record
    $statuses = R::dispense('statuses');
    $statuses->status_key = (string)$input->status_key;
    $statuses->descripcion = (string)$input->descripcion;
    $id = R::store($statuses);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($statuses)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /articles/:id
$app->put('/statuses/:id', function ($id) use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single article
    $statuses = R::findOne('statuses', 'id=?', array($id));  
    
    // store modified article
    // return JSON-encoded response body
    if ($statuses) {      
      $statuses->status_key = (string)$input->status_key;
      $statuses->descripcion = (string)$input->descripcion;
      R::store($statuses);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($statuses)[0]);
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

// handle DELETE requests to /statuses/:id
$app->delete('/statuses/:id', function ($id) use ($app) {    
  try {
    // query database for cliente
    $request = $app->request();
    $status = R::findOne('statuses', 'id=?', array($id));  
    
    // delete statuses
    if ($status) {
      R::trash($status);
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