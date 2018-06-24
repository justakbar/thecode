<?php 
	session_start();
	$dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm'); 
	if (!$dbc) {
          die("Connection failed: " . mysqli_connect_error());
      }
  	if($_POST['send'])
	{
		$zagqu = htmlentities($_POST['zagqu'],ENT_QUOTES);
		$text = htmlentities($_POST['questiontext'],ENT_QUOTES);
		$login = $_SESSION['username'];
		$email = $_SESSION['email'];
		$metki = htmlentities(trim($_POST['metki']),ENT_QUOTES);
		$times = time();

		if(empty($zagqu))
			$err[] = 'Поля "заголовок вопроса" пусто!';
		if(empty($text))
			$err[] = 'Поля "основной текст" пусто!';
		if(empty($metki))
			$err[] = 'Добавтье не менее 1 и не более 5 метки!';
		/*$part = count(explode(" ", $metki));*/
		$part = str_word_count($metki);
		if($part >= 5 || $part < 0)
			$err[] = 'Добавтье не менее 1 и не более 5 метки!';
		
		if(count($err) == 0)
		{
			$query = "INSERT INTO `questions` (zagqu, question, tags, answers, email, login, dates, views, viewed, view) VALUES ('$zagqu', '$text', '$metki', '0', '$email', '$login', '$times', '', '', '0')";
	  		$query = mysqli_query($dbc,$query);
	  		if($query)
	  		{
	  			$user = $_SESSION['username'];
	  			$query = "SELECT `id` FROM `questions` WHERE `login` = '$user' ORDER BY `id` DESC LIMIT 1";

	  			$query = mysqli_query($dbc, $query);
	  			$row = mysqli_fetch_assoc($query);

	  			$qu = "SELECT `ask` FROM `users` WHERE `login` = '$user'";
	  			$qu = mysqli_query($dbc, $qu);

	  			$rows = mysqli_fetch_assoc($qu);
	  			$add = $rows['ask'];
	  			$add .= $row['id'] . ' ';

	  			$qu = "UPDATE `users` SET `ask` = '$add' WHERE `login` = '$user'";
	  			$qu = mysqli_query($dbc,$qu);
	  			header("Location: /question");
	  		}
	  		else $err[] = 'Something went wrong!';
	  	}
	}
	/*$i = 0;
	while(++$i < 100){
		$query = "INSERT INTO `questions` (zagqu, question, tags, answers, email, login, dates, views, viewed, view) VALUES ('$i', '$i', 'nothing', '0', 'akbar@gmail.com', 'akbar', 'time()', '', '', '0')";
		$query = mysqli_query($dbc,$query);
	}*/
	include 'head.php';
?>
	<div class ="container">
	  	<div class="row">
	    	<div class="col-md-9">
	      		<div class = "main">
	      			<?php 
	      				if(count($err) > 0)
	      				{
	      					echo "<h4>Исправьте!</h4>";
		      				foreach ($err as $error) {
		      					echo '<div class="alert alert-danger" role="alert">' . $error . ' </div>';
		      				}
		      			}
	      			?>
	        		<form action = "/ask" method="post">

		        		<p>
		        			<h4>Заголовок вопроса:</h4>
		        			<input type="text" name="zagqu" class = "form-control" placeholder="Заголовка вопрос">	
		        		</p>
		        		<p>
		        			<h5>Чтобы писать код нажмите на - <code><?php echo htmlentities("</>") ?></code></h5>
		        		</p>
		        		<h3>Основной текст</h3>
		        		
		        		<section id="page-demo">
		                  	<textarea id="txt-content" name = "questiontext" data-autosave="editor-content" required></textarea>
		                </section>

		                <p>
		               		<?php echo $msg; ?>
		                	<h4>Метки</h4>
		                	<input type="text" name="metki" placeholder="введите не менее 1 и не более 5" class = "form-control">
		                </p>


		                <input type="submit" name="send" value = "Задать" class = "btn btn-primary">

		        	</form>
	      		</div>
	    	</div>
	    <?php include 'right.php'; ?>
	  </div>
	</div>
</div>
<div class = "footer">
    <div class = "container padding">
        <div class = "col-md-12">
        	<h5>TheCode</h5>
        	<h5><small>Да будет полезным ваши вопросы другим тоже</small></h5>
        </div>
    </div>
</div>
	<script src="/resource/js/jquery.min.js"></script>
    <script type="text/javascript" src="/resource/editor/site/assets/scripts/module.js"></script>
    <script type="text/javascript" src="/resource/editor/site/assets/scripts/uploader.js"></script>
    <script type="text/javascript" src="/resource/editor/site/assets/scripts/hotkeys.js"></script>
    <script type="text/javascript" src="/resource/editor/site/assets/scripts/simditor.js"></script>
    <script type="text/javascript" src="/resource/editor/site/assets/scripts/page-demo.js"></script>
    <script src="/resource/js/bootstrap.min.js"></script>
</body>
</html>