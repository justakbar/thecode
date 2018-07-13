<?php 
// ============================= Right Side ================================ //
	function getMetki () {
		$file = fopen('tags.json', 'r');
		$value = fread($file, 4096);
		$value = json_decode($value, true);

		fclose($file);
		return $value;
	}


	function getSameQuestion ($module, $conn) {
		$data = array();
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn) 
			exit("Error");*/

		$query = "SELECT `id`, `zagqu` FROM `questions` WHERE `question` LIKE '%$zag%' OR `zagqu` LIKE '%$zag%' OR `tags` LIKE '%$tg%'";
		$query = mysqli_query($conn, $query);

		if ($query) {

			while ($row = mysqli_fetch_assoc($query)) {
				if (++$i == 5) break;
				if ($row['id'] == $module) continue;

				$data[] = array(
					'id' => $row['id'],
					'zagqu' => $row['zagqu']
				);
			}
		}
		return $data;
	}

// =================================== Get Questions list ============================================ //

	/*function makeTags($text) {
		$text = explode(" ", $text);

		foreach ($text as $key) {
			$tags .= '<a class = "badge badge-light" href = "/question/?id='. urlencode($key) . '">'. html_entity_decode($key) . ' </a>';
		}
		return $tags;
	}
*/
	function getPagination ($id,$conn) {
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn) {
			exit("Error");
		}*/

		if(!isset($id))
			$id = 1;
		$query = mysqli_query($conn, "SELECT id FROM questions ORDER BY id DESC LIMIT 1");
		if($query){
			$row = mysqli_fetch_assoc($query);
			$last =  ceil($row['id']/10);
		}
		else $last = 0;

		$left = ($id  - 2 > 2) ? $id - 3 : 1;
		$right = ($last - $id > 2) ? $id + 3 : $last;

		if($left == $id)
			$class = 'class = "actived"';
		else $class = '';

		if($left > 1)
			$span = '<span>. . . </span>';
		else $span = '';

		$pagination = '<div class = "pagination"><a '. $class .' href="/question/?page=1"> 1 </a>' . $span;

		while(++$left <= $right)
		{
			if($left != $id)
				$pagination .= '<a href="/question/?page=' . $left .  '"> ' . $left .  '</a>';
			else $pagination .= '<a class = "actived" href="/question/?page='. $left .  '"> ' . $left .  '</a>';
		}
		$pagination .= '</div>';
		return $pagination;
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


	function getQuestion ($conn) {
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
		if (!$conn)
			exit ("Something went wrong!");*/
		$data = array();
		if (isset($_GET['page']))
			$page_id = ($_GET['page'] - 1) * 10;
		else $page_id = 0;

		if (isset($_GET['id'])){
			$tags = $_GET['id'];

			$query = "SELECT id, zagqu, tags, answers, login, dates, view FROM questions WHERE tags LIKE '%$tags%' ORDER BY id DESC LIMIT $page_id, 10";
			$query = mysqli_query($conn, $query);
		} else {

			$query = "SELECT id, zagqu, tags, answers, login, dates, view FROM questions ORDER BY id DESC LIMIT $page_id, 10";
			$query = mysqli_query($conn, $query);
		}

		if ($query) {
			while ( $row = mysqli_fetch_assoc($query)) {
				$data[] = array(
					'id' => $row['id'],
					'zagqu' => $row['zagqu'],
					'tags' => $row['tags'],
					'answers' => $row['answers'],
					'login' => $row['login'],
					'dates' => makeDate($row['dates']),
					'views' => $row['view']
				);
			}
		}
		else exit("error");
		return $data;
	}

// ======================================= Get Question Datas =========================================== //

	function makeViews ($userip, $userid, $countviews,$module,$conn) {
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn) {
			exit("Error");
		}*/

		$id = $_SESSION['id'];
		$ip = $_SERVER['REMOTE_ADDR'];

		$newUserIp = explode(",", $userip);
		$newUserId = explode(",", $userid);

		if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) && $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
		{
			if(!in_array($id, $newUserId))
			{
				$id = $id . ','. $userid;
				$qu = mysqli_query($conn, "UPDATE `questions` SET `viewed` = '$id', `view` = `view` + 1  WHERE `id` = '$module'");
				$countviews++;
			}
		}
		else if(!in_array($ip, $newUserIp))
		{
			$ip = $ip . ',' . $userip;
			$qu = mysqli_query($conn, "UPDATE `questions` SET `views` = '$ip', `view` = `view` + 1  WHERE `id` = '$module'");
			$countviews++;
		}
		return $countviews = ($countviews > 1) ? $countviews . ' views' : $countviews . ' view';
	}

	function getData ($module,$conn) {
		$data = array();

		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn)
			exit ("Something went wrong!");*/

		$query = "SELECT * FROM questions WHERE id = $module";
		$query = mysqli_query($conn, $query);
		if ($query) {
			if (mysqli_num_rows($query)) { 
				$row = mysqli_fetch_assoc($query);
				$data = array(
					'id' => $row['id'],
					'zagqu' => $row['zagqu'],
					'question' => $row['question'],
					'tags' => $row['tags'],
					'answers' => $row['answers'],
					'login' => $row['login'],
					'dates' => makeDate($row['dates']),
					'views' => makeViews($row['views'], $row['viewed'], $row['view'], $module, $conn)
				);
			}
			else return false;
		}
		return $data;
	}

	function getAnswers ($module,$conn) {
		$data = array();

		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn)
			exit ("Something went wrong!");*/
		$query = mysqli_query($conn, "SELECT answer, login, dates FROM answer WHERE qu_id = '$module' ORDER BY id DESC");

		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($row = mysqli_fetch_assoc($query)) {
					$data[] = array(
						'answer' => html_entity_decode($row['answer']),
						'login' => $row['login'],
						'dates' => makeDate($row['dates'])
					);
				}
			} else {
				return false;
			}
		}
		return $data;
	}

// ======================================== Search ============================================= //

	function getPaginationSearch ($id,$qu,$conn) {
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn) {
			exit("Error");
		}*/

		if(!isset($id))
			$id = 1;
		$query = mysqli_query($conn, "SELECT id FROM questions WHERE question LIKE '%$qu%'");
		if($query){
			$row = mysqli_num_rows($query);
			$last =  ceil($row/10);
		}
		else $last = 0;

		$left = ($id  - 2 > 2) ? $id - 3 : 1;
		$right = ($last - $id > 2) ? $id + 3 : $last;

		if($left == $id)
			$class = 'class = "actived"';
		else $class = '';

		if($left > 1)
			$span = '<span>. . . </span>';
		else $span = '';

		$pagination = '<div class = "pagination"><a '. $class .' href="/search/?qu='. $qu .'&page=1"> 1 </a>' . $span;

		while(++$left <= $right)
		{
			if($left != $id)
				$pagination .= '<a href="/search/?qu='. $qu .'&page=' . $left .  '"> ' . $left .  '</a>';
			else $pagination .= '<a class = "actived" href="/search/?qu='. $qu .'&page='. $left .  '"> ' . $left .  '</a>';
		}
		$pagination .= '</div>';
		return $pagination;
	}


	function search ($key,$conn) {
		$data = array();

		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn)
			exit ("Something went wrong!");*/
		
		if (isset($_GET['page']))
			$page_id = ($_GET['page'] - 1) * 10;
		else $page_id = 0;

		$query = mysqli_query($conn, "SELECT * FROM `questions` WHERE `zagqu` LIKE '%$qu%' OR `question` LIKE '%$qu%' OR `tags` LIKE '%$qu%' ORDER BY id DESC LIMIT $page_id, 10"); 

		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ( $row = mysqli_fetch_assoc($query)) {
					$data[] = array(
						'id' => $row['id'],
						'zagqu' => $row['zagqu'],
						'tags' => $row['tags'],
						'answers' => $row['answers'],
						'login' => $row['login'],
						'dates' => makeDate($row['dates']),
						'views' => $row['view']
						/*'views' => $this->makeViews($row['views'], $row['viewed'], $row['view'])*/
					);
				}
			}
		}
		return $data;
	}

	//======================================= Answer To Question ===========================================//

	function answerToQuestion ($text, $id, $login, $user_id,$conn) {
		/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

		if (!$conn)
			exit ("Something went wrong!");*/
		$time = time();
		$query = mysqli_query($conn, "INSERT INTO answer (answer, qu_id, login, dates) VALUES ('$text', '$id', '$login', '$time')");

		if ($query) {
			$query = mysqli_query($conn, "SELECT answer FROM users WHERE login = '$login'");
			$row = mysqli_fetch_assoc($query);
			$last = $row['answer'] . $id . ',';

			$query = mysqli_query($conn, "UPDATE users SET answer = '$last' WHERE login = '$login'");

			return '<div class = "alert alert-success">Success</div>';
		}
	}
?>