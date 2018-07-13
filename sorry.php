<?php 
session_start();
include 'head.php'; 
?>


<div class = "container">
	<div class = "row">

		<div class = "col-md-4">
		</div>

		<div class = "col-md-4">
			<center><h3>Page not found!</h3></center>
		</div>

		<div class = "col-md-4">
		<?php 
			echo substr("akbar", 1);
			/*$fp = fopen('tags.json', 'r+');

			$text = fread($fp, 4096);
		    $array = json_decode($text,true);

			$arr = array('php', 'mysqli','dfg','mysqli','javascript','sdkljflksd');
			$arr = array_count_values($arr);

			foreach ($arr as $key => $value) {
				if (isset($array[$key])) {
					$array[$key] = $array[$key] + $value;
				}
				else $array[$key] = 1;
			}

			arsort($array);
			print_r($array);
			$part = json_encode($array, JSON_PRETTY_PRINT);
			print_r($part);
			fwrite($fp, $part);
			fclose($fp);*/
/*
			$file = file_get_contents('tags.json');
			$file = json_decode($file,true);

			print_r($file);*/
		?>
		</div>

	</div>
</div>


<?php include 'foot.php'; ?>