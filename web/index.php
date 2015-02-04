<?php
//error_reporting(E_ALL);
//ini_set('display_errors','on');

$controller = $_GET['controller'];
$action = $_GET['action'];
$id = $_GET['id'];

if ($controller == 'files' && $action == 'get') {
  header("X-Accel-Redirect: /warehouse/readme.txt");
  header("Content-type application/octet-stream");
  header('Content-Disposition: attachment; filename=newfile.csv');
  exit;
}
else {
  echo "c: $controller; a: $action; i: $id";

//  header("HTTP/1.0 404 Not Found");
//  require('private/errors/40x.html');
//  echo 'xe-xe';
}





//$url = explode('/', $_GET['url']);
//print_r($url);
//
//require 'controllers/' . $url[0] . '.php';
//$controller = new $url[0];
//
//if (isset($url[1])) {
//  $controller->{$url[1]}();
//}
