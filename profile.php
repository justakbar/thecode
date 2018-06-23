<?php 
    session_start();
    $err = array();
    $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

    if(!isset($_COOKIE['cookie']) || !isset($_COOKIE['hash']))
    {
        unset($_COOKIE['cookie']);
        unset($_COOKIE['hash']);
        unset($_COOKIE['PHPSESSID']);
        setcookie('cookie', '', time() - 3600, '/');
        setcookie('hash', '', time() - 3600, '/');
        setcookie('PHPSESSID','', time() - 3600, '/');
        header("Location: /login");
    }

    include 'include/function.php';
    include 'head.php';
    $hash = $_SESSION['hash'];
    $email = $_SESSION['email'];

    if(isset($_POST['update']))
    {
        /*$first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $newlogin = htmlspecialchars($_POST['login']);*/

        $lastpass = htmlspecialchars($_POST['lastpass']);
        $password1 = htmlspecialchars($_POST['newpass1']);
        $password2 = htmlspecialchars($_POST['newpass2']);
        
        if(!empty($password1) && !empty($password2) && !empty($lastpass))
        {
            if($password2 == $password1) 
            {
                if(isset($_SESSION['email']) && $_SESSION['username'] && isset($_COOKIE['cookie']) && isset($_COOKIE['hash']))
                {
                    $email = $_SESSION['email'];
                    $log = $_SESSION['username'];
                    $q = "SELECT password FROM `users` WHERE email = '$email' AND login = '$log'";

                    $q = mysqli_query($dbc, $q);
                    $q = mysqli_fetch_assoc($q);

                    if($q['password'] == sha1(sha1($lastpass)))
                    {
                        $password2 = sha1(sha1($password2));
                        $qu = "UPDATE `users` SET password = '$password2' WHERE email = '$email' AND login = '$log'";
                        $qu = mysqli_query($dbc, $qu);
                        if($qu)
                          $change = 1;
                        else $change = 2;
                    }
                    else $err[] = "Неверный текущий пароль!";
                }
                else $err[] = "Что то пошло не так!";
            }
            else $err[] = "Пароли не совпадают!";
        }
        else $err[] = "";
    }

    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $login = $_SESSION['username'];
    $email = $_SESSION['email'];
    $hash = $_SESSION['hash'];
    $query = mysqli_query($dbc, "SELECT `ask`, `answer` FROM `users` WHERE `hash` = '$hash'");

    if($query)
    {
      $row = mysqli_fetch_assoc($query);
      $ask = $row['ask'];
      $answer = $row['answer'];
      $ask = explode(" ", $ask);
      array_pop($ask);
      $soraw = $ask;
      $ask = count($ask);

      $answer = explode(" ", $answer);
      array_pop($answer);
      $answer = count($answer);
    }
    else $soraw = array(0);
?>
<div class ="container">
  <div class="row">
    <div class="col-md-9">
    	<div class = "row">
	    	<div class = "col-md-3">
	    		<img src="/resource/img/profile-pictures1.png" class="img-thumbnail">
          <br/>
	    		<a href = "/logout" class="btn btn-primary">Выход</a>
	    	</div>
	        <div class = "col-md-9">
	        	<table width="50%" height = "150px">
					<caption><center><strong>Информация</strong></center></caption>

					<tr>
						<td>Имя:</td>
						<td><?php echo $name; ?></td>
					</tr>
					<tr> 
						<td>Фамилья:</td>
						<td><?php echo $surname; ?></td>
					</tr>
					<tr>
						<td>Логин:</td>
						<td><?php echo $login; ?></td>
					</tr>
					<tr>
						<td>Задал(а) вопрос:</td>
						<td><?php echo $ask; ?></td>
					</tr>
					<tr>
						<td>Ответиль(а):</td>
						<td><?php echo $answer; ?></td>
					</tr>
            	</table>
	        </div>
	    </div>
	    <div class = "row">
	        <div class = "col-md-12">
		        <form action = "/profile" method="post" class = "passwordform">
		            <p><center><strong>Изменить пароль</strong></center></p>
		            <table width="50%" height = "150px">
		            	<tr>
		            		<td>Текщий пароль:</td>
		    				<td><input type="password" class = "form-control" name="lastpass" placeholder="Текщий пароль"></td>
			           	</tr>
			            <tr> 
			                <td>Новый пароль:</td>
			        		<td><input type="password" class = "form-control" name="newpass1" placeholder="Новый пароль"></td>
			            </tr>
			        
			            <tr> 
			            	<td>Повторите новый пароль:</td>
			        		<td><input type="password" class = "form-control" name="newpass2" placeholder="Повторите новый пароль"></td>
			            </tr>
			            <tr>
							<td><input name="update" class="btn btn-primary btn-sm" value="Сохранить" type="submit"></td>
						</tr>
			        </table>
			    </form>
	    	</div>
	    </div>
	    <div class = "row">
	        <div class = "col-md-12">
		        <?php 
					if($change == 1) echo '<div class = "alert alert-success"><i class = "glyphicon glyphicon-ok"></i> ' . msgsend(1) . '</div>'; 
          else if($change == 2) echo '<div class = "alert alert-danger">' . msgsend(2) . '</div>';

					if(count($err) > 0) { ?>
			            <?php 
			                foreach ($err as $key) {
			                    echo '<div class="alert alert-danger" role="alert">'. $key . 
			                    '</div>';
			                }
			            ?>
		        <?php } ?>
		    </div>
		</div>
		<div class = "row">
	        <div class = "col-md-12">
		    <?php 
		    	foreach ($soraw as $key => $value) 
            {
              $query = "SELECT * FROM `questions` WHERE `id` = '$value'";
              $query = mysqli_query($dbc, $query);
              if($query)
              {
                echo '<h4>Вопросы</h4><hr/>';

                $row = mysqli_fetch_assoc($query);
                $since = $row['dates'];
                $since = time() - $since;
                $time = time_since($since); 

                $tags = explode(" ", $row['tags']);
                $metki = '';
                foreach ($tags as $tag) {
                  $metki .= '<a class = "badge" href = "/question/?id='. urlencode($tag) . '">'. htmlentities($tag) . '</a> ';
                }

                echo '
                  <div class = "col-md-10">
                      <blockquote class="blockquote">
                        <a href = "/question/'. $row['id'] . '" class = "questionlink">' . $row['zagqu'] . '</a>
                        <div class = "row">
                          <div class = "col-md-4">
                            <p> 
                              <h6>
                                Asked <a class = "questionlink" href = "/user/'.  $row['login'] . '">' . $row['login'] .  '</a>
                                  ' . $time . ' ago 
                                </h6>
                              </p>
                           </div>
                           <div class = "col-md-8">
                            '. $metki . '
                           </div>
                        </div>
                      </blockquote>
                  </div>
                  <div class = "col-md-1 ans">
                    <center>' . $row['view'] . '
                      <h5><small>просмотров</small></h5>
                    </center>
                  </div>
                  <div class = "col-md-1 ans">
                    <center>' . $row['answers'] . '
                      <h5><small>Ответов</small></h5>
                    </center>
                  </div>';
                }
            }
		    ?>
		</div>
    </div>
</div>
    <?php include 'right.php'; ?>
  </div>
</div>
<?php include 'foot.php'; ?>