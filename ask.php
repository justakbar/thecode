<?php 
	session_start();
	$conn = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
	include 'include/ask.function.php';
	if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }
  	if($_POST['send'])
	{
		$zagqu = htmlentities($_POST['zagqu'],ENT_QUOTES);
		$text = htmlentities($_POST['questiontext'],ENT_QUOTES);
		$login = $_SESSION['username'];
		$email = $_SESSION['email'];
		$metki = htmlentities(trim($_POST['metki']),ENT_QUOTES);
		$metki = preg_replace('/\s\s+/', ' ', $metki);
		$time = time();

		$msg = ask ($zagqu, $text, $login, $metki, $time,$conn);
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
	      				if(count($msg) > 0)
	      				{
	      					echo "<h4>Исправьте!</h4>";
		      				foreach ($msg as $error) {
		      					echo $error;
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