<?php
  if($_SERVER['REQUEST_URI'] == '/')
  {
    $page = 'index';
    $module = 'index';
  }
  else {
    $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $URL_Parts = explode('/', trim($URL_Path, " /"));
    $page = array_shift($URL_Parts);
    $module = array_shift($URL_Parts);
  }

  if(!empty($module))
  {
    $param = array();
    for($i = 0; $i < count($URL_Parts); $i++)
    {
      $param[$URL_Parts[$i]] = $URL_Parts[++$i];
    }
  }
  
    if(isset($URL_Parts[1]))
      header("Location: sorry");
    if($page == 'index')
      include 'home.php';
    else if($page == 'question')
      include 'question.php'; 
    else if($page == 'login')
      include_once 'login.php';
    else if($page == 'registration' && !isset($module))
      include 'registration.php';
    else if($page == 'profile' && !isset($module))
      include 'profile.php';
    else if($page == 'logout' && !isset($module))
      include 'logout.php';
    else if($page == 'search')
      include 'search.php';
    else if($page == 'ask')
      include 'ask.php';
    else if($page == 'user')
      include 'user.php';
    else include 'sorry.php';

  /*echo "<pre>";
  print_r($URL_Path);
  echo "</pre>";
  echo "<pre>";
  print_r($URL_Parts);
  echo "</pre>";
  echo $page . "<br/>";
  echo $module;
  echo "<pre>";
  print_r($param);
  echo "</pre>";*/
?>