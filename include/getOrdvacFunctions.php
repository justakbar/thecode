<?php 

function getPublishedDate($since) {
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

//Views
function viewed($view) {
	return $view = ($view > 1) ? $view . ' views' : $view . ' view';
}

//Get Pagination
function getPagination ($id, $conn) {

	if(!isset($id))
		$id = 1;

	if (!$conn) {
		exit("Error");
	}
	$query = mysqli_query($conn, "SELECT id FROM ordvac ORDER BY id DESC LIMIT 1");
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

	$pagination = '<div class = "pagination"><a '. $class .' href="/ordvac/?page=1"> 1 </a>' . $span;

	while(++$left <= $right)
	{
		if($left != $id)
			$pagination .= '<a href="/ordvac/?page=' . $left .  '"> ' . $left .  '</a>';
		else $pagination .= '<a class = "actived" href="/ordvac/?page='. $left .  '"> ' . $left .  '</a>';
	}
	$pagination .= '</div>';

	return $pagination;
}

// Get Data
function getData($conn) {
	$data = array();
	/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
	if (!$conn) {
		exit("Error");
	}*/

	if (isset($_GET['page']))
		$id = ($_GET['page']-1) * 10;
	else $id = 0;

	$query = mysqli_query($conn, "SELECT * FROM ordvac ORDER BY id DESC LIMIT $id, 10");
	if($query) {
		while ($row = mysqli_fetch_assoc($query)) {
			$data[] = array(
				'id' => $row['id'],
				'zagqu' => $row['zagqu'],
				'cost' => $row['tsena'],
				'views' => viewed($row['views']),
				'published' => getPublishedDate($row['published'])
			);
		}
	}
	return $data;
}


function countViews ($viewed, $viewip, $views, $conn) {

	/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

	if (!$conn) {
		exit("Error");
	}*/

	$id = $_SESSION['id'];
	$ip = $_SERVER['REMOTE_ADDR'];

	$userip = explode(",", $viewip);
	$userid = explode(",", $viewed);

	if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) && $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
	{
		if(!in_array($id, $userid))
		{
			$id = $id . ','. $viewed;
			$qu = mysqli_query($conn, "UPDATE `ordvac` SET `viewed` = '$id', `views` = `views` + 1  WHERE `id` = '$module'");
			$views++;
		}
	}
	else if(!in_array($ip, $userip))
	{
		$ip = $ip . ',' . $viewip;
		$qu = mysqli_query($conn, "UPDATE `ordvac` SET `viewip` = '$ip', `views` = `views` + 1  WHERE `id` = '$module'");
		$views++;
	}
	return $views = ($views > 1) ? $views . ' views' : $views . ' view';
}


function getOrderData($module,$conn) {

	/*$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

	if (!$conn) {
		exit("Error");
	}*/

	$query = mysqli_query($conn, "SELECT * FROM ordvac WHERE id = '$module'");
	if ($query) {
		$row = mysqli_fetch_assoc($query);

		$data = array(
			'id' => $row['id'],
			'zagqu' => $row['zagqu'],
			'text' => html_entity_decode($row['tekst']),
			'login' => $row['login'],
			'full_name' => $row['full_name'],
			'cost' => $row['tsena'],
			'viewed' => countViews($row['viewed'], $row['viewip'], $row['views'],$conn),
			'published' => getPublishedDate($row['published'])
		);
	}
	return $data;
}