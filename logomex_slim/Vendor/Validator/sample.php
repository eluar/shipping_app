<?php
$validations = array(
    'name' => 'anything',
    'email' => 'email',
    'alias' => 'anything',
    'pwd'=>'anything',
    'gsm' => 'phone',
    'birthdate' => 'date');
$required = array('name', 'email', 'alias', 'pwd');
$sanatize = array('alias');

$validator = new FormValidator($validations, $required, $sanatize);

if($validator->validate($_POST))
{
    $_POST = $validator->sanatize($_POST);
    // now do your saving, $_POST has been sanatized.
    die($validator->getScript()."<script type='text/javascript'>alert('changes saved');</script>");
}
else
{
    die($validator->getScript());
}


/** Another sample **/

/** To validate just one element:**/
$validated = FormValidator()->validate('blah@bla.', 'email');
/** To sanatize just one element:**/
$sanatized = FormValidator()->sanatize('<b>blah</b>', 'string');

