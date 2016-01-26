<?php

//envios
$app->get('/envios', authorize(), 'getEnvios');
$app->get('/envios/total/', authorize(), 'getEnviosTotal');
$app->get('/envios/page/:pagenumber', authorize(), 'getEnviosByPage');
$app->get('/envios/:id', authorize(), 'getEnviosById');
$app->get('/envios/service/:serviceId', authorize(), 'getEnviosByServiceId');
$app->post('/envios', authorize(), 'addEnvios');
$app->put('/envios/:id', authorize(), 'updateEnviosById');
$app->delete('/envios/:id', authorize(), 'deleteEnvios');

//clientes
$app->get('/clientes', authorize(), 'getClientes');
$app->get('/clientes/justnames', authorize(), 'getClientesJustNames');
$app->get('/clientes/total/', authorize(), 'getClientesTotal');
$app->get('/clientes/:id', authorize(), 'getClientesById');
$app->get('/clientes/name/:name', authorize(), 'getClientesByName');
$app->get('/clientes/page/:pagenumber', authorize(), 'getClientesByPage');
$app->post('/clientes', authorize(), 'addClientes');
$app->put('/clientes/:id', authorize(), 'updateClientesById');
$app->delete('/clientes/:id', authorize(), 'deleteClientes');

//contactos
$app->get('/contactos', authorize(), 'getContactos');
$app->get('/contactos/justnames/', authorize(), 'getContactosJustNames');
$app->get('/contactos/justnames/:clientid', authorize(), 'getContactosJustNames');
$app->get('/contactos/total/', authorize(), 'getContactosTotal');
$app->get('/contactos/:id', authorize(), 'getContactosById');
$app->get('/contactos/name/:name', authorize(), 'getContactosByName');
$app->get('/contactos/page/:pagenumber', authorize(), 'getContactosByPage');
$app->post('/contactos', authorize(), 'addContactos');
$app->put('/contactos/:id', authorize(), 'updateContactosById');
$app->delete('/contactos/:id', authorize(), 'deleteContactos');

//segmentos
$app->get('/segmentos', authorize(), 'getSegmentos');
$app->get('/segmentos/justnames/', authorize(), 'getSegmentosJustNamesAll');
$app->get('/segmentos/justnames/:clientid', authorize(), 'getSegmentosJustNames');
$app->get('/segmentos/total/', authorize(), 'getSegmentosTotal');
$app->get('/segmentos/:id', authorize(), 'getSegmentosById');
$app->get('/segmentos/name/:name', authorize(), 'getSegmentosByName');
$app->get('/segmentos/page/:pagenumber', authorize(), 'getSegmentosByPage');
$app->post('/segmentos', authorize(), 'addSegmentos');
$app->put('/segmentos/:id', authorize(), 'updateSegmentosById');
$app->delete('/segmentos/:id', authorize(), 'deleteSegmentos');

//subsegmentos




//usuarios
$app->get('/usuarios', authorize(), 'getUsuarios');
//$app->get('/usuarios/justnames/', authorize(), 'getUsuariosJustNamesAll');
//$app->get('/usuarios/total/', authorize(), 'getUsuariosTotal');
$app->get('/usuarios/:id', authorize(), 'getUsuariosById');
//$app->get('/usuarios/name/:name', authorize(), 'getUsuariosByName');
//$app->get('/usuarios/page/:pagenumber', authorize(), 'getUsuariosByPage');
$app->post('/usuarios', authorize(), 'addUsuarios');
$app->put('/usuarios/:id', authorize(), 'updateUsuariosById');
$app->delete('/usuarios/:id', authorize(), 'deleteUsuarios');