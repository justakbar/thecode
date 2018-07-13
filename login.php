<?php
session_start();
$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
include 'include/login.function.php';

if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) && $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
	header("Location: http://thecode.uz");
else 
{
	if(isset($_POST['send']))
	{
		$username = trim(htmlentities($_POST['usrname'],ENT_QUOTES));
		$password = trim(htmlentities($_POST['paswrd'],ENT_QUOTES));

		$msg = login ($username, $password, $conn);
	}
}
if($module == 'activate')
{
	$act = $URL_Parts['0'];
	if(isset($_SESSION['activate'])) 
		$msg = activate($act, $_SESSION['activate'], $conn);
}
include 'head.php';
?>
<div class ="container">
	<div class="row">
		<div class = "col-md-6 offset-md-3">
			<div class="card text-center mx-auto">
				<div class="card-header">
					<center><strong>Вход</strong></center>
				</div>
				<div class = "card-body">
					<form action = "/login" method="post">

						<p><input type="text" class="form-control loginplace" name = "usrname" placeholder="Имя пользователя"></p>
						<p><input type="password" class="form-control loginplace" name = "paswrd" placeholder="Пароль"></p>
						<div class = "float-left">
							<button type="sumbit" name="send" class = "btn btn-primary btn-sm">Вход</button>
							<a href = "/registration" name="send" class = "btn btn-success btn-sm">Регистрация</a>
						</div>
					</form>
				</div>
			</div>
			<div class = "margin">
			<?php 
				echo $msg;
			?>
			</div>
		</div>
	</div>
</div>
<?php include 'foot.php'; ?>