<?php 
	// ========================================= Login ============================================//
	function generateCode($length=10) {

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;
		while (strlen($code) < $length) {
			$code .= $chars[mt_rand(0,$clen)];
		}
		return $code;
	}

	function login ($login, $password, $conn) {

		if (!empty($login) && !empty($password)) {

			if( !preg_match("/^[a-zA-Z0-9_]+$/",$login) || strlen($login) < 5 || strlen($login) > 20 || strlen($password) < 8 )
				return '<div class = "alert alert-danger">Неверный логин или пароль!</div>';

			$password = sha1(sha1($password));

			$query = mysqli_query($conn, "SELECT user_id, login, email, confirm,first_name, last_name FROM users WHERE login = '$login' AND password = '$password'");

			if ($query) {
				if (mysqli_num_rows($query) == 1) {

					$row = mysqli_fetch_assoc($query);

					if ($row['confirm'] == 1) {
						$hash = generateCode();
						$id = $row['user_id'];

						$query = mysqli_query($conn, "UPDATE users SET hash = '$hash' WHERE user_id = '$id'");
						$_SESSION['id'] = $id;
						$_SESSION['username'] = $row['login'];
						$_SESSION['name'] = $row['first_name'];
						$_SESSION['surname'] = $row['last_name'];
						$_SESSION['hash'] = $hash;
						$_SESSION['email'] = $row['email'];
						$_SESSION['code'] = substr(sha1(sha1($row['email'])), 0, -6);

						setcookie('cookie', $_SESSION['code'], time() + 60*60*24);
						setcookie('hash', $hash, time() + 60*60*24);
						header("Location: ". $_SERVER['REQUEST_URI']);
					} else {
						$_SESSION['activate'] = $row['email'];
						return '<div class = "alert alert-warning">Подтвердите эл.почту!</div>';
					}
				} else return '<div class = "alert alert-danger">Неверный логин или пароль</div>';	
			}
		} else return '<div class = "alert alert-danger">Пусто!</div>';
	}

	function activate ($act, $email,$conn) {
		if($act === substr(sha1(sha1($email)), 0, -10))
		{
			$query = "SELECT confirm FROM users WHERE email = '$email'";
			$query = mysqli_query($conn,$query);
			if ($query) {
				$row = mysqli_fetch_assoc($query);

				if($row['confirm'] == 1)
					return '<div class = "alert alert-warning">Эл. почта уже активирована!</div>';
				else {
					$query = "UPDATE users SET confirm = '1' WHERE email = '$email'";
					mysqli_query($conn, $query);
					return '<div class = "alert alert-success">Теперь вы можете войти!</div>';
				}
			}
		}
	}

	// =================================================== Registration =================================================//

	function registration ($first_name,$last_name,$username,$password1,$password2,$email, $time, $conn) {
		$err = array();
		if( !preg_match("/^[a-zA-Zа-яА-Я]+$/iu",$first_name))
			$err[] = "Имя и Фамилия может состоять только из букв";

		if( !preg_match("/^[a-zA-Zа-яА-Я]+$/iu",$last_name))
			$err[] = "Имя и Фамилия может состоять только из букв";

		if((strlen($first_name) < 1 || strlen($first_name) > 30) && (strlen($last_name) < 1 || strlen($last_name) > 30) )
			$err[] = "Имя и Фамилия должен быть не меньше 5-х символов и не больше 30";

		if( preg_match("/^[a-zA-Z]+$/",$username))
			if( !preg_match("/^[a-zA-Z0-9_]+$/",$username))
				$err[] = "Логин может состоять только из букв английского алфавита";

		if( (strlen($username) < 5 || strlen($username) > 20) )
			$err[] = "Логин должен быть не меньше 5-х символов и не больше 20";

		if(strlen($password1) < 8)
			$err[] = "Пароль должен быть не меньше 8-х символов";

		if($password1 !== $password2)
			$err[] = "Пароли не совпадают";

		$query1 = "SELECT email FROM users WHERE email = '$email'";
		$query2 = "SELECT login FROM users WHERE login = '$username'";

		$data1 = mysqli_query($conn, $query1);
		$data2 = mysqli_query($conn, $query2);

		if(mysqli_num_rows($data1) > 0)
			$err[] = "Эл.почта уже существует!";

		if(mysqli_num_rows($data2) > 0)
			$err[] = "Логин уже существует!";

		if(count($err) == 0)
        {
          $password = sha1(sha1($password2));

          $query = "INSERT INTO `users` (login, email, password, first_name, last_name, confirm, reg_time) VALUES ('$username','$email',  '$password', '$first_name', '$last_name', '0', '$time')";
          mysqli_query($conn,$query);

          mail($email, "Регистрация на сайт thecode.uz", "Ссылка для активации: http://thecode.uz/login/activate/". substr(sha1(sha1($email)), 0, -10));
          $_SESSION['activate'] = $email;
          header("Location: /login "); 
        } else return $err;
	}

?>