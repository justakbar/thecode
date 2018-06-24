<?php
	session_start();
  	$dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
  	if (!$dbc) {
        die("Connection failed: " . mysqli_connect_error());
    }

	include 'head.php';

?>
 <div class="container">
	<div class="row">
		<div class="col-md-9">
        	<div class = "main">

        		



			</div>
		</div>
		<?php 
			include 'right.php';
		?>
    </div>
</div>