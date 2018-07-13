<?php
function Formchars($p)
{
	return htmlspecialchars(trim($p));
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

function validateOrder($zagqu, $cost, $valyuta, $domain, $text, $conn){

	if(strlen($zagqu) > 150 || strlen($zagqu) < 1)
		$err[] = "Название заказа должна состоять из 1 - 150 символов!";

	if(!is_numeric($cost))
		$err[] = 'На поле "цена" должно быть число!';

	if($valyuta != 'RUB' && $valyuta != 'UZS' && $valyuta != 'USD')
		$err[] = "Неизвестная валюта!";

	if($domain < 1 || $domain > 4)
		$err[] = 'Неизвестная сфера деятельности!';

	$date = time();
	$login = $_SESSION['username'];
	$email = $_SESSION['email'];
	$id = $_SESSION['id'];
	$full_name = $_SESSION['name'] . ' ' . $_SESSION['surname'];

	if(count($err) == 0){
		$cost .= ' ' . $valyuta;
		$query = "INSERT INTO `ordvac` (zagqu, tekst, login, email, full_name, tsena, viewed, views, published, visibility, deleted) VALUES ('$zagqu', '$text', '$login', '$email', '$full_name', '$cost', '', '0', '$date', '1', '0')";

		$query = mysqli_query($conn, $query);

		if($query) {
			$last = mysqli_insert_id($conn);

			$query = mysqli_query($conn, "SELECT orders FROM users WHERE user_id = '$id'");
			if ($query) { 
				$row = mysqli_fetch_assoc($query);
				$order = $row['order'] . $last . ',';

				$query = mysqli_query($conn, "UPDATE users SET orders = '$order' WHERE user_id = '$id'");
			}
			return  '<div class = "alert alert-success"> Success! </div>';
		}
		else return '<div class = "alert alert-danger"> Something went wrong! </div>';
	}
	else return $err;
}

?>