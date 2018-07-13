<?php
    session_start();
    $conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
    include 'include/login.function.php';

    if(!isset($_COOKIE['hash']) || !isset($_COOKIE['cookie']) ||
                          $_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code'])
    { 
      if(isset($_POST['send']))
      {
        date_default_timezone_set('Europe/Moscow');
        $time = date("F j, Y, H:i");
        $first_name = htmlentities(trim($_POST['frst_name']),ENT_QUOTES);
        $last_name = htmlentities(trim($_POST['lst_name']),ENT_QUOTES);
        $email = htmlentities(trim($_POST['email']),ENT_QUOTES);
        $username = htmlentities(trim($_POST['usrname']),ENT_QUOTES);
        $password1= htmlentities(trim($_POST['paswrd1']),ENT_QUOTES);
        $password2 = htmlentities(trim($_POST['paswrd2']),ENT_QUOTES);
        
        $err = registration($first_name,$last_name,$username,$password1,$password2,$email,$time,$conn);
      }
    }
    else header("Location: http://thecode.uz");
    include 'head.php';
?>
<div class ="container">
  <div class="row">
    <div class = "col-md-6 offset-md-3">
      	<div class="card text-center mx-auto">
        	<div class="card-header">
        		<center><strong>Регистрация</strong></center>
        	</div>
        	<div class = "card-body">
  		    	<form action = "/registration" method="post" class = "form-group">
      				<p><input type="text" class="form-control loginplace" name = "frst_name" placeholder="Имя"></p>
      				<p><input type="text" class="form-control loginplace" name = "lst_name" placeholder="Фамилья"></p>
  		    		<p><input type="email" class="form-control loginplace" name = "email" placeholder="Эл. почта"></p>
  		    		<p><input type="text" class="form-control loginplace" name = "usrname" placeholder="Логин"></p>
  		    		<p><input type="password" class="form-control loginplace" name = "paswrd1" placeholder="Пароль"></p>
  		    		<p><input type="password" class="form-control loginplace" name = "paswrd2" placeholder="Повторите пароль"></p>
  	    			<div class = "float-left">
                <button type = "submit" class = "btn btn-success btn-sm" name = "send">Регистрация</button>
    	    			<a href = "/login" name="send" class = "btn btn-primary btn-sm">Вход</a>
              </div>
  		    	</form>
    	    </div>
  	    </div>
  	    <?php
          if(count($err) > 0) {
  	      ?>
  	      <div class="alert alert-danger" role="alert">
  	        <?php 
  	          foreach ($err as $errors) {
  	            echo '<p><i class="fas fa-exclamation-triangle"> </i> '.$errors . '</p>';
  	          }
  	        ?>
  	      </div>
  	     <?php } ?>
    </div>
  </div>
</div>
<?php include 'foot.php'; ?>