<?php
    session_start();
    include_once 'include/function.php';
    $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
    $err = array();
    $msg = "";

    if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) &&
                          $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
      header("Location: http://thecode.uz");
    else
    {
      if(isset($_POST['send']))
      {
        $usrname = Formchars($_POST['usrname']);
        $paswrd = Formchars($_POST['paswrd']);
        


          if(!empty($usrname) || !empty($paswrd))
          {
            if( !preg_match("/^[a-zA-Z0-9_]+$/",$usrname) || 
               strlen($usrname) < 5 || strlen($usrname) > 20 || strlen($paswrd) < 8 )
              $err[] = "Неверные данные!";
            else
            {
              $pass = sha1(sha1($paswrd));
              $query = "SELECT * FROM `users` WHERE login = '$usrname' AND password = '$pass'";
              $data = mysqli_query($dbc, $query);
              $row = mysqli_fetch_assoc($data); 

              if(mysqli_num_rows($data) == 1)
              {
                if($row['confirm'] == 1)
                {
                  function generateCode($length=10) {

                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
                    $code = "";
                    $clen = strlen($chars) - 1;
                    while (strlen($code) < $length) {
                      $code .= $chars[mt_rand(0,$clen)];
                    }
                  return $code;
                  }
                  $email = $row['email'];
                  $id = $row['user_id'];
                  $hash = md5(generateCode());
                  mysqli_query($dbc,"UPDATE users SET hash = '$hash' WHERE user_id = '$id'");

                  $_SESSION['ask'] = $row['ask'];
                  $_SESSION['answer'] = $row['answer'];
                  $_SESSION['id'] = $row['user_id'];
                  $_SESSION['name'] = $row['first_name'];
                  $_SESSION['surname'] = $row['last_name'];
                  $_SESSION['username'] = $usrname;
                  $_SESSION['hash'] = $hash;
                  $_SESSION['email'] = $email;
                  $_SESSION['code'] = substr(sha1(sha1($row['email'])), 0, -6);

                  setcookie('cookie', $_SESSION['code'], time() + 60*60*24);
                  setcookie('hash', $hash, time() + 60*60*24);

                  header("Location: ". $_SERVER['REQUEST_URI']);
                }
                else {
                  $_SESSION['activate'] = $row['email'];
                  $err[] = "Подтвердите эл.почту!";
                }
              }
              else $err[] = "Неверный логин или пароль";
            }
          }
          else $err[] = "Пусто!";
        }
    }
    if($module == 'activate')
    {
      $act = $URL_Parts['0'];
      if(isset($_SESSION['activate'])) 
      {
        $email = $_SESSION['activate'];
        if($act == substr(sha1(sha1($email)), 0, -10))
        {
          $qu = "SELECT `confirm` FROM `users` WHERE email = '$email'";
          $q = mysqli_query($dbc,$qu);
          $q = mysqli_fetch_assoc($q);
          if($q['confirm'] == 1)
            $msg = "Эл. почта уже активирована!";
          else {
            $qu = "UPDATE `users` SET confirm = '1' WHERE email = '$email'";
            mysqli_query($dbc, $qu);
            $msg = "Теперь вы можете войти!";
          }
        }
      }
    } 
    include 'head.php';
?>
    <div class ="container">
      <div class="row">
        <div class = "col-md-4">
        </div>
        <div class = "col-md-4">
        	<?php if(!empty($msg)) echo '<div class = "alert alert-success">' . $msg . '</div>'; ?>
        	<div class="panel panel-default">
              	<div class="panel-heading">
              		<center><strong>Вход</strong></center>
              	</div>
              	<div class = "panel-body">
    		    	<form action = "/login" method="post">
    		    			
    		    		<p><input type="text" class="form-control loginplace" name = "usrname" placeholder="Имя пользователя"></p>
    		    		<p><input type="password" class="form-control loginplace" name = "paswrd" placeholder="Пароль"></p>
    	    			<button type="sumbit" name="send" class = "btn btn-primary btn-sm">Вход</button>
    	    			<a href = "/registration" name="send" class = "btn btn-success btn-sm">Регистрация</a>

    		    	</form>
    	    	</div>
    	    </div>
    	    <?php
            if(count($err) > 0) {
    	      ?>
    	      <div class = "errors">
    	        <?php 
    	          foreach ($err as $errors) {
    	            echo '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"> </i> '.$errors.'</div>';
    	          }
    	        ?>
    	      </div>
    	      <?php } ?>
    	</div>
      <div class = "col-md-4">
      </div>
    </div>
</div>
<?php include 'foot.php'; ?>