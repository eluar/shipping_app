<?php

require_once 'Vendor/Slim/Slim.php';
require_once 'Vendor/RedBean/rb.php';
include 'Vendor/Validator/FormValidator.php';
require_once 'config/config.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
	'debug' => true,
	'mode' => 'development',
	'log.enabled' => true,
	//'log.writer' => new \My\LogWriter(),
	'log.level' => \Slim\Log::DEBUG,
	'cookies.encrypt' => false,
	'cookies.secret_key' => 'logomex_secret'
));

class ResourceNotFoundException extends Exception {}

//R::setup('mysql:host=localhost;dbname=appdata','user','pass');
R::setup("mysql:host=$dbHost;dbname=$dbName",$dbUser,$dbPass);
R::freeze(true);

// file: api/index.php
session_start(); // Add this to the top of the file

$app->get('/hello/:name', function ($name) {
	echo "Damn dude $name";
});

// I add the login route as a post, since we will be posting the login form info
$app->post('/login', 'login');
$app->get('/logout', 'logOut');

//including all the app controllers
include 'controllers/choferes.php';
include 'controllers/clientes.php';
include 'controllers/contactos.php';
include 'controllers/envios.php';
include 'controllers/segmentos.php';
include 'controllers/statuses.php';
include 'controllers/subsegmentos.php';
include 'controllers/tipo_usuarios.php';
include 'controllers/tipo_vehiculos.php';
include 'controllers/ubicaciones.php';
include 'controllers/usuarios.php';
include 'controllers/vehiculos.php';

//load all the actions GETs POSTs PUTs DELETEs
include 'controllers/actions.php';

$app->run();

/**
 * Quick and dirty login function with hard coded credentials (admin/admin)
 * This is just an example. Do not use this in a production environment
 */
function login() {
    if(!empty($_POST['email']) && !empty($_POST['password'])) {
        // normally you would load credentials from a database. 
        // This is just an example and is certainly not secure
        $user = getUsuariosByLogin($_POST['email'], $_POST['password']);
        if ($user) {
    		$_SESSION['user'] = $user;
    		$_SESSION['user']['role'] = $_SESSION['user']['tipo_usuario']['tipo_usuario'];
    		echo json_encode($user);
    	}
        //if($_POST['email'] == 'admin' && $_POST['password'] == 'admin') {
        //    $user = array("email"=>"admin", "firstName"=>"Clint", "lastName"=>"Berry", "role"=>"user");
        //    $_SESSION['user'] = $user;
        //    echo json_encode($user);
        //}
        else {
        	$error = array("error"=> array("text"=>"Usuario y/o password incorrecto!..."));
        	echo json_encode($error);
        }
    }
    else {
	$error = array("error"=> array("text"=>"Se requieren usuario y password."));
        echo json_encode($error);
    }
}

/**
 * Authorise function, used as Slim Route Middlewear (http://www.slimframework.com/documentation/stable#routing-middleware)
 */
function authorize($role = "user") {
    //return true;
    return function () use ( $role ) {
        // Get the Slim framework object
        $app = \Slim\Slim::getInstance();
        // First, check to see if the user is logged in at all
        //$_SESSION['user'] = array('name'=>'luar','role'=>'Super Usuario'); //patch for fix
        if(!empty($_SESSION['user'])) {
            // Next, validate the role to make sure they can access the route
            // We will assume admin role can access everything
            if($_SESSION['user']['role'] == $role || 
                $_SESSION['user']['role'] == 'Super Usuario') {
                //User is logged in and has the correct permissions... Nice!
                return true;
            }
            else {
                // If a user is logged in, but doesn't have permissions, return 403
                $app->halt(403, 'Usuario y/o password incorrecto!');
            }
        }
        else {
            // If a user is not logged in at all, return a 401
            $app->halt(401, 'Lo sentimos, No ha entrado correctamente... Intente loguearse');
        }
    };
}

function logOut() {
	unset($_SESSION['user']);
	$return = array("message"=>"success", "text"=>"Ha terminado su sesión...");
	//else 
	//	$return = array("message"=>"error", "text"=>"Error ocurred while you loged out...");
	echo json_encode($return);
}