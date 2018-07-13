<?php 
	function getMetki () {
		$file = fopen('tags.json', 'r');
		$value = fread($file, 4096);
		$value = json_decode($value, true);

		fclose($file);
		return $value;
	}

	function updatePassword ($id, $lastpass, $password1, $password2, $conn) {
		if(!empty($password1) && !empty($password2) && !empty($lastpass))
        {
            if($password2 == $password1 && !empty($password2) && !empty($password1)) 
            {
                if(isset($_SESSION['email']) && $_SESSION['username'] && isset($_COOKIE['cookie']) && isset($_COOKIE['hash']))
                {
                    $q = "SELECT password FROM users WHERE user_id = '$id'";

                    $q = mysqli_query($conn, $q);
                    $q = mysqli_fetch_assoc($q);

                    if($q['password'] === sha1(sha1($lastpass)))
                    {
                        $password2 = sha1(sha1($password2));
                        $qu = "UPDATE users SET password = '$password2' WHERE user_id = '$id'";
                        $qu = mysqli_query($conn, $qu);
                        if($qu)
                        	return '<div class = "alert alert-success">Success</div>';
                        else return '<div class = "alert alert-danger">Something went wrong!</div>';
                    }
                    else $err = '<div class = "alert alert-danger">Неверный текущий пароль!</div>';
                }
                else $err = '<div class = "alert alert-danger">Что то пошло не так!</div>';
            }
            else $err = '<div class = "alert alert-danger">Пароли не совпадают!</div>';
        }
        else $err = '';
        return $err;
	}

	function updateContacts ($id,$contact_number, $email, $messenger, $messenger_data, $conn) {
		$error = array();
		if (!empty($contact_number)) {
			
			if($contact_number[0] === '+')
				$contact_number = substr($contact_number, 1);

			if(strlen($contact_number) != 12 || preg_match('/[+]/',$contact_number))
				$error[] = '<div class = "alert alert-danger">Телефон номер неверный!</div>';
		}

		if (!empty($email)) {
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				$error[] = '<div class = "alert alert-danger">Эл. почта неверный!</div>';
		}

		if (!empty($messenger_data)) {
			
			if($messenger_data[0] === '+')
				$messenger_data = substr($messenger_data,1);

			if(strlen($messenger_data) != 12 || !is_numeric($messenger_data))
				$error[] = '<div class = "alert alert-danger">Номер мессенджера неверный!</div>';
			
			if ($messenger != 'Telegram' && $messenger != 'WhatsApp')
				$error[] = '<div class = "alert alert-danger">WhatsApp или Telegram</div>';
		}

		if(count($error) == 0)
		{
			$query = mysqli_query($conn, "UPDATE users SET phonenumber = '$contact_number', contactemail = '$email', messenger_number = '$messenger_data', messenger = '$messenger' WHERE user_id = '$id'");
			if($query) 
				return array('<div class = "alert alert-success">Success</div>');
			else return array('<div class = "alert alert-danger">Something went wrong!</div>');
		}
		else return $error;
	}

	function viewed($view) {
		return $view = ($view > 1) ? $view . ' views' : $view . ' view';
	}

	function makeDate($since) {
		$since = time() - $since;
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

		$print = ($count == 1) ? '1 '.$name . ' ago': "$count {$name}s ago";

		return $print;
	}

	function getOrder($login, $conn) {
		$data = array();
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
		if (!$conn) {
			exit("Error");
		}*/
		$query = mysqli_query($conn, "SELECT id,zagqu, tekst, full_name, tsena, views, published, visibility, deleted FROM ordvac WHERE login = '$login' AND deleted != 'deleted'");
		if($query) {
			while ($row = mysqli_fetch_assoc($query)) {
				$data[] = array(
					'id' => $row['id'],
					'zagqu' => $row['zagqu'],
					'cost' => $row['tsena'],
					'full_name' => $row['full_name'],
					'tekst' => html_entity_decode($row['tekst']),
					'views' => viewed($row['views']),
					'published' => makeDate($row['published']),
					'visibility' => $row['visibility'],
					'deleted' => $row['deleted']
 				);
			}
		}
		return $data;
	}

	function getUserQuestion ($user,$conn) {
		$data = array();
		
		$query = mysqli_query($conn, "SELECT id, zagqu, tags, answers, login, dates, view FROM questions WHERE login = '$user' ORDER BY id DESC LIMIT 0,10");

		if ($query) {
			if (mysqli_num_rows($query)) {
				while ($row = mysqli_fetch_assoc($query)) {
					$data[] = array(
						'id' => $row['id'],
						'zagqu' => $row['zagqu'],
						'tags' => $row['tags'],
						'answers' => $row['answers'],
						'login' => $row['login'],
						'dates' =>	makeDate($row['dates']),
						'views' => $row['view']
					);
				}
			}
		}
		return $data;
	}

	function getUser ($user,$conn) {
		$data = array();

		$query = mysqli_query($conn, "SELECT user_id, login, first_name, last_name, ask, answer, orders, phonenumber, contactemail, messenger_number, messenger FROM users WHERE login = '$user'");

		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				$row = mysqli_fetch_assoc($query);
				$data = array(
					'id' => $row['user_id'],
					'login' => $row['login'],
					'first_name' => $row['first_name'],
					'last_name' => $row['last_name'],
					'ask' => count(explode(",", $row['ask'])) - 1,
					'answer' => count(explode(",", $row['answer'])) - 1,
					'orders' => explode(",",$row['orders']),
					'phonenumber' => $row['phonenumber'],
					'contactemail' => $row['contactemail'],
					'messenger_number' => $row['messenger_number'],
					'messenger' => $row['messenger']
				);
			} else array('<div class = "alert alert-danger">Something went wrong!');
		}
		return $data;
	}

?>