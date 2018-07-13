<?php 
	
	function getMetki () {
		$file = fopen('tags.json', 'r');
		$value = fread($file, 4096);
		$value = json_decode($value, true);

		fclose($file);
		return $value;
	}

	function ask ($zagqu, $text, $login, $metki, $time, $conn) {

		$err = array();

		if(empty($zagqu))
			$err[] = 'Поля "заголовок вопроса" пусто!';
		if(empty($text))
			$err[] = 'Поля "основной текст" пусто!';
		if(empty($metki))
			$err[] = 'Добавтье не менее 1 и не более 5 метки!';
		
		$part = str_word_count($metki);
		if($part >= 6 || $part < 0)
			$err[] = 'Добавтье не менее 1 и не более 5 метки!';

		if (count($err) == 0) {
			$tag = explode(" ", $metki);
			foreach ($tag as $key) {
				$tags .= '<a class = "badge badge-light" href = "/question/?id='. urlencode($key) . '">'. html_entity_decode($key) . ' </a> ';
			}

			$query = "INSERT INTO `questions` (zagqu, question, tags, answers, email, login, dates, views, viewed, view) VALUES ('$zagqu', '$text', '$tags', '0', '$email', '$login', '$time', '', '', '0')";
			$query = mysqli_query($conn,$query);

			if ($query) {
				$last = mysqli_insert_id($conn);

				$fp = fopen('tags.json', 'r');

	  			$metki = array_count_values(explode(" ", $metki));
	  			$array = json_decode(fread($fp, 4096),true);

	  			foreach ($metki as $key => $value) {
					if (isset($array[$key])) {
						$array[$key] = $array[$key] + $value;
					}
					else $array[$key] = 1;
				}
				arsort($array);
				fclose($fp);

				$fp = fopen('tags.json', 'w');
				fwrite($fp, json_encode($array, JSON_PRETTY_PRINT));
				fclose($fp);

				$query = mysqli_query($conn, "SELECT ask FROM users WHERE login = '$login'");

				$row = mysqli_fetch_assoc($query);
				$last = $row['ask'] . $last . ',';
				$query = mysqli_query($conn, "UPDATE users SET ask = '$last' WHERE login = '$login'");
				if ($query)
					return array('<div class = "alert alert-success">Success</div>');
				else return array('<div class = "alert alert-danger">Something went wrong!');
			}
		}
	}

?>