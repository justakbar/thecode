<?php
function Formchars($p)
{
	return htmlspecialchars(trim($p));
}


function validation($frst_name,$lst_name,$usrname,$paswrd1,$paswrd2,$email)
{
	$dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
	$err = array();

	if( !preg_match("/^[a-zA-Zа-яА-Я]+$/iu",$frst_name))
		$err[] = "Имя и Фамилия может состоять только из букв";

	if( !preg_match("/^[a-zA-Zа-яА-Я]+$/iu",$lst_name))
		$err[] = "Имя и Фамилия может состоять только из букв";

	if((strlen($frst_name) < 1 || strlen($frst_name) > 30) && (strlen($lst_name) < 1 || strlen($lst_name) > 30) )
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

function validateOrder($zagqu, $cost, $valyuta, $domain, $text){

	$dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

	if (!$dbc)
		exit("Error");

	if(strlen($zagqu) > 150 || strlen($zagqu) < 1)
		$err[] = "Название заказа должна состоять из 1 - 150 символов!";

	if(!is_numeric($_POST['cost']))
		$err[] = 'На поле "цена" должно быть число!';

	if($valyuta < 1 || $valyuta > 3)
		$err[] = "Неизвестная валюта!";

	if($domain < 1 || $domain > 4)
		$err[] = 'Неизвестная сфера деятельности!';

	$times = time();
	$login = $_SESSION['username'];
	$email = $_SESSION['email'];
	$full_name = $_SESSION['name'] . ' ' . $_SESSION['surname'];

	if(count($err) == 0){
		$query = "INSERT INTO `ordvac` (zagqu, tekst, login, email, full_name, tsena, viewed, views, published, visibility, deleted) VALUES ('$zagqu', '$text', '$login', '$email', '$full_name', '$cost', '', '0', '$times', '1', '0')";

		$query = mysqli_query($dbc, $query);

		if($query)
			return  '<div class = "alert alert-success"> Success! </div>';
		else return '<div class = "alert alert-danger"> Something went wrong! </div>';
	}
	else return $err;
}

?>