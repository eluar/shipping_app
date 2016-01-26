<?php

// handle GET requests for /vehiculos
$app->get('/vehiculos', function () use ($app) {  
  // query database for all vehiculos
  $vehiculos = R::findAll('vehiculos', 'ORDER BY economico'); 

  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  foreach ($vehiculos as $vehiculo) {
      $vehiculo->tipo_vehiculos = $vehiculo->tipo_vehiculos;
  }
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($vehiculos));
});

$app->get('/vehiculos/justnames', function() use ($app){
	$rows = R::getAll("select v.id, concat(v.economico,'  :  ',t.descripcion ) as economico  from vehiculos v, tipo_vehiculos t
where v.tipo_vehiculos_id=t.id");
	$vehiculos = R::convertToBeans( 'vehiculos', $rows );
	
	// send response header for JSON content type
	$app->response()->header('Content-Type', 'application/json');
		
	// return JSON-encoded response body with query results
	echo json_encode(R::exportAll($vehiculos));
});

// handle GET requests for client's ID
$app->get('/vehiculos/:id',function($id) use ($app){
  $vehiculo = R::findOne('vehiculos', 'id=?', array($id));
  try {
    if ($vehiculo) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($vehiculo)[0]);
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

$app->get('/vehiculos/total/', function() use ($app) {
  $total = R::dispense('total');
  $total->total = R::count('vehiculos');
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  //TODO: Buscar un modo elegante de hacer lo siguiente:
  echo json_encode(R::exportAll($total)[0]);
});

$app->get('/vehiculos/page/:pagenumber', function ($pagenumber) use ($app) {  
  
  $vehiculos = R::findAll('vehiculos', 'ORDER BY economico LIMIT ?,8', array($pagenumber*8-8));
  
  $app->response()->header('Content-Type: text/html; charset=utf-8');
  $app->response()->header('Content-Type', 'application/json');
  
  foreach ($vehiculos as $vehiculo) {
      $vehiculo->tipo_vehiculos = $vehiculo->tipo_vehiculos;
  }
  
  echo json_encode(R::exportAll($vehiculos));
});

// Handle GET by vehiculo name
$app->get('/vehiculos/name/:name',function($name) use ($app){
  $vehiculo = R::findOne('vehiculos', 'nombre=?', array($name));
  try {
    if ($vehiculo) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($vehiculo)[0]);
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

// handle POST requests to /vehiculos
$app->post('/vehiculos', function () use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // store vehiculo record
    $vehiculo = R::dispense('vehiculos');
    $vehiculo->tipo_vehiculos_id= $input->tipo_vehiculos_id;
    $vehiculo->numero= $input->numero;
    $vehiculo->economico = (string)$input->economico;
    $vehiculo->placas = (string)$input->placas;
    $vehiculo->serie = (string)$input->serie;
    $vehiculo->activo = 1;
    $vehiculo->nip = (string) $input->nip;
    $id = R::store($vehiculo);    
    
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($vehiculo)[0]);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// handle PUT requests to /vehiculos/:id
$app->put('/vehiculos/:id', function ($id) use ($app) {    
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    // query database for single vehiculo
    $vehiculo = R::findOne('vehiculos', 'id=?', array($id));  
    
    // store modified vehiculo
    // return JSON-encoded response body
    if ($vehiculo) {      
      $vehiculo->tipo_vehiculos_id= $input->tipo_vehiculos_id;
      $vehiculo->numero= $input->numero;
      $vehiculo->economico = (string)$input->economico;
      $vehiculo->placas = (string)$input->placas;
      $vehiculo->serie = (string)$input->serie;
      $vehiculo->activo = 1;
      $vehiculo->nip = (string) $input->nip;
      R::store($vehiculo);    
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($vehiculo)[0]);
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

// handle DELETE requests to /vehiculos/:id
$app->delete('/vehiculos/:id', function ($id) use ($app) {    
  try {
    // query database for vehiculo
    $request = $app->request();
    $vehiculo = R::findOne('vehiculos', 'id=?', array($id));  
    
    // delete vehiculo
    if ($vehiculo) {
      R::trash($vehiculo);
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