<?php
	session_start();
	$dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
	if (!$dbc) {
		die("Connection failed: " . mysqli_connect_error());
	}

	include 'include/getQuestionsFunctions.php';
	include 'include/validation.function.php';
	include 'head.php'; 
?>
	<div class ="container">
		<div class="row">
			<div class="col-md-9">
				<div class = "main">
<?php				if (!isset($_COOKIE['hash']) || !isset($_COOKIE['cookie']) ||
				    $_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code']) {
					echo '
					<div class = "row">
						<div class = "col-12">
							<div class="alert alert-warning" role="alert">
								Чтобы ответить на вопросы вы  должни 
								<a href = "/login"> войти </a>
							</div>    
						</div>
					</div>';
		}
?>
<?php

if($page == 'index') {
	$data = getQuestion();
	echo '<h3>Вопросы</h3>';
	foreach ($data as $key => $value) {
?>
<div class = "row blockquote">
	<div class = "col-md-8">
		<a href = "/question/<?php echo $value['id']; ?>" class = "questionlink"><?php echo $value['zagqu']; ?></a>
		<div class = "row">
			<div class = "col-md-5">
				<p> 
					<small>Asked <a class = "a" href = "/user/<?php echo $value['login']; ?>"><?php echo $value['login']; ?> </a><?php echo $value['dates']; ?></small>
				</p>
			</div>
			<div class = "col-md-7">
				<?php echo $value['tags']; ?>
			</div>
		</div>
	</div>

	<div class = "col-md-2 border border-white">
		<center>
			<small><?php echo $value['views']; ?></small>
			<h6><small>просмотров</small></h6>
		</center>
	</div>

	<div class = "col-md-2 border border-white">
		<center>
			<small><?php echo $value['answers']; ?></small>
			<h6><small>Ответов</small></h6>
		</center>
	</div>
</div>
<?php
}
	echo getPagination($_GET['page']);
	} else if ($page == 'question' && is_numeric($module)) {

		$value = getData($module);

	if ($value != false) {
		$exist = true;
?>
	<div class = "row">
		<div class = "col-md-9">
			<h3><?php echo $value['zagqu'] ?></h3>
		</div>
		<div class = "col-md-3">
			<h6>
				<small><?php echo $value['views']; ?></small><br/>
				<small> Asked: <?php echo $value['dates']; ?></small><br/>
				<small> User: <a class = "questionlink" href = "/user/<?php echo $value['login']; ?>"><?php echo $value['login']; ?></a></small>
			</h6> 
		</div>
	</div>
	<hr/>
	<div class = "row">
		<div class = "col-md-12">
			<h4>Question</h4>
			<div class = "ques">
				<?php echo $value['question']; ?>
			</div>
			<div class = "row">
				<div class = "col-md-12" align = "right">
					етки: <?php echo $value['tags']; ?>
				</div>
			</div>
		</div>
	</div>
	<h3>Ответить</h3>
	<form action="/question/<?php echo $module; ?>" method="post">
		<section id="page-demo">
			<textarea id="txt-content" name="questiontext" data-autosave="editor-content" required></textarea>
		</section>
		<input type="submit" name="send" value="Отправить" class="btn btn-secondary margin">
	</form>

	<?php
	} else echo "Question not exist!";
}
?>

			</div>
		</div>
		<?php include 'right.php'; ?>
	</div>
</div>
<?php include 'foot.php'; ?>