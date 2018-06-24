<?php
    session_start();
    $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
    include_once 'include/function.php';
    $err = array();
    if(!isset($_COOKIE['hash']) || !isset($_COOKIE['cookie']) ||
                          $_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code'])
    { 
      if(isset($_POST['snd']))
      {
        date_default_timezone_set('Europe/Moscow');
        $time = date("F j, Y, H:i");
        $frst_name = mysqli_real_escape_string($dbc, Formchars($_POST['frst_name']));
        $lst_name = mysqli_real_escape_string($dbc, Formchars($_POST['lst_name']));
        $email = mysqli_real_escape_string($dbc, Formchars($_POST['email']));
        $usrname = mysqli_real_escape_string($dbc, Formchars($_POST['usrname']));
        $paswrd1= mysqli_real_escape_string($dbc, Formchars($_POST['paswrd1']));
        $paswrd2 = mysqli_real_escape_string($dbc, Formchars($_POST['paswrd2']));
        
        $err = validation($frst_name,$lst_name,$usrname,$paswrd1,$paswrd2,$email);

        if(count($err) == 0)
        {
          $pass = sha1(sha1($paswrd2));

          $query = "INSERT INTO `users` (login, email, password, first_name, last_name, confirm, reg_time) VALUES ('$usrname','$email',  '$pass', '$frst_name', '$lst_name', '0', '$time')";

          mysqli_query($dbc,$query);

          mail($email, "Регистрация на сайт codeline.uz", "Ссылка для активации: http://codeline.uz/login/activate/". substr(sha1(sha1($email)), 0, -10));
          $_SESSION['activate'] = $email;
          header("Location: /login "); 
        }
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
                <button type = "submit" class = "btn btn-success btn-sm" name = "snd">Регистрация</button>
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