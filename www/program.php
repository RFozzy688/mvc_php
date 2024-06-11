<?php

$uri = $_SERVER['REQUEST_URI'];
$quest_position = strpos($uri, '?');

if($quest_position !== false){
  $uri = substr($uri, 0, $quest_position);
}

$part = explode('/', $uri);

$controller = empty($part[1]) ? 'home' : $part[1];
$action = empty($part[2]) ? 'index' : $part[2];
$id = isset($part[3]) ? $part[3] : false;

$controller_filename = "./controllers/{$controller}_controller.php";

if(is_readable($controller_filename)) { 

  include_once($controller_filename);

  # в переменной находится имя класса
  $controller_classname = ucfirst($controller) . 'Controller';

  if(class_exists($controller_classname)){
    # создание объекта ч/з string, т.е. распаковывается переменная 
    $controller_obj = new $controller_classname();

    if(method_exists($controller_obj, "serve")){
      $controller_obj->serve($action);
    }
    else{
      echo "$controller_classname has no 'serve' method";
    }
  }
  else{

  }
}
else {
  echo "404 page not found";
}