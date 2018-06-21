<?php
	function Formchars($p)
	{
		return htmlspecialchars(trim($p));
	}

  /*function quget()
  {
    $dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
    if(isset($_GET['id']))
    {
      $search = $_GET['id'];
      $msg = '<h3>' . $search . '</h3><hr/>';
      $query = "SELECT * FROM `questions` WHERE `tags` LIKE '%$search%' ORDER BY `id` DESC LIMIT 0, 10";
      $query = mysqli_query($dbc,$query);
    }

    $query = "SELECT * FROM `questions` ORDER BY `id` DESC LIMIT 0, 10";
    $query = mysqli_query($dbc, $query);
    return $query;
  }
*/
	function validation($frst_name,$lst_name,$usrname,$paswrd1,$paswrd2,$email)
	{
		$dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
		$err = array();

    if( !preg_match("/^[a-zA-Zа-яА-Я]+$/iu",$frst_name))
      $err[] = "Имя и Фамилия может состоять только из букв";
    
    if( !preg_match("/^[a-zA-Zа-яА-Я]+$/iu",$lst_name))
      $err[] = "Имя и Фамилия может состоять только из букв";

    if((strlen($frst_name) < 1 || strlen($frst_name) > 30) &&
       (strlen($lst_name) < 1 || strlen($lst_name) > 30) )
      $err[] = "Имя и Фамилия должен быть не меньше 5-х символов и не больше 30";

    if( preg_match("/^[a-zA-Z]+$/",$usrname))
      if( !preg_match("/^[a-zA-Z0-9_]+$/",$usrname))
        $err[] = "Логин может состоять только из букв английского алфавита";
       
    if( (strlen($usrname) < 5 || strlen($usrname) > 20) )
      $err[] = "Логин должен быть не меньше 5-х символов и не больше 20";
    
    if($paswrd1 !== $paswrd2)
      $err[] = "Пароли не совпадают";
    

    if(strlen($paswrd1) < 8)
      $err[] = "Пароль должен быть не меньше 8-х символов";

    $query1 = "SELECT `email` FROM `users` WHERE email = '$email'";
    $query2 = "SELECT `login` FROM `users` WHERE login = '$usrname'";
    
    $data1 = mysqli_query($dbc, $query1);
    $data2 = mysqli_query($dbc, $query2);

    if(mysqli_num_rows($data1) > 0)
      $err[] = "Эл.почта уже существует!";
    
    if(mysqli_num_rows($data2) > 0)
      $err[] = "Логин уже существует!";

    	return $err;
	}

  function checkupdate($first_name, $last_name, $login)
  {
    $err = array();
      
    if( !preg_match("/^[a-zA-Z0-9]+$/",$login))
      $err[] = "Логин может состоять только из букв английского алфавита";
       
    if(strlen($login) < 4 || strlen($login) > 20)
      $err[] = "Логин должен быть не меньше 4-х символов и не больше 20";

    if(count($err) == 0)
      if($login != $_SESSION['username'])
      {
        $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'users');
        $q = "SELECT * FROM `users` WHERE login = '$login'";
        $q = mysqli_query($dbc, $q);
        if(mysqli_num_rows($q) == 1)
          $err[] = "Логин существуеть!";
        mysqli_close($dbc);
      }

    if(empty($last_name) && empty($first_name) && empty($login) && empty($password1) && empty($password2))
    {
      $err[] = "Все поля пусто";
      return $err;
    }

    if(!empty($first_name))
    {
      if(!preg_match("/^[a-zA-Z0-9]+$/",$first_name))
        $err[] = "Имя и Фамилия может состоять только из букв английского алфавита";

      if((strlen($first_name) < 1 || strlen($first_name) > 40))
        $err[] = "Имя и Фамилия должен быть не меньше 1 символов и не больше 40";
    }

    if(!empty($last_name))
    {
      if( !preg_match("/^[a-zA-Z0-9]+$/",$last_name))
        $err[] = "Имя и Фамилия может состоять только из букв английского алфавита";

      if(strlen($last_name) < 1 || strlen($last_name) > 40)
        $err[] = "Имя и Фамилия должен быть не меньше 1 символов и не больше 40";
    }
    return $err;
  }

  function msgsend($num)
  {
    if($num == 1)
      return "Данные успешно изменены!";
    if($num == 2)
      return "Что то пошьло не так!";
  }

  function time_since($since) {
      $chunks = array(
          array(60 * 60 * 24 * 365 , 'year'),
          array(60 * 60 * 24 * 30 , 'month'),
          array(60 * 60 * 24 * 7, 'week'),
          array(60 * 60 * 24 , 'day'),
          array(60 * 60 , 'hour'),
          array(60 , 'minute'),
          array(1 , 'second')
      );

      for ($i = 0, $j = count($chunks); $i < $j; $i++) {
          $seconds = $chunks[$i][0];
          $name = $chunks[$i][1];
          if (($count = floor($since / $seconds)) != 0) {
              break;
          }
      }

      $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
      return $print;
  }

?>