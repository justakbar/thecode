<?php 
    session_start();
    $err = array();
    $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

    if(!isset($_COOKIE['hash']) || !isset($_COOKIE['cookie']) ||
                          $_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code'])
    {
        unset($_COOKIE['cookie']);
        unset($_COOKIE['hash']);
        unset($_COOKIE['PHPSESSID']);
        setcookie('cookie', '', time() - 3600, '/');
        setcookie('hash', '', time() - 3600, '/');
        setcookie('PHPSESSID','', time() - 3600, '/');
        header("Location: /login");
    }

    include 'include/validation.function.php';
    include 'head.php';
    $hash = $_SESSION['hash'];
    $email = $_SESSION['email'];

    if(isset($_POST['contact']))
    {
      $contact_number = htmlentities(trim($_POST['phonenumber']),ENT_QUOTES);
      $email = htmlentities(trim($_POST['contactemail']),ENT_QUOTES);
      $messenger = htmlentities(trim($_POST['messenger']),ENT_QUOTES);
      $messenger_data = htmlentities(trim($_POST['messengerdata']),ENT_QUOTES);

      if(!empty($contact_number))
      {
        if($contact_number[0] !== '+')
          $contact_number = '+'.$contact_number;

        if(strlen($contact_number) != 13 || !is_numeric($contact_number))
          $error[] = 'Телефон номер неверный!';
      }
      
      if(!empty($email))
      {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
          $error[] = 'Эл. почта неверный!';
      }

      if(!empty($messenger_data))
      {
        if(isset($messenger))
        {
          if($messenger != 1 && $messenger != 2)
            $error[] = 'WhatsApps или Telegram!';
        }
        if($messenger_data[0] !== '+')
          $messenger_data = '+'.$messenger_data;

        if(strlen($messenger_data) != 13 || !is_numeric($messenger_data))
          $error[] = 'Номер мессенджера неверный!';
      }


      $login = $_SESSION['username'];
      $id = $_SESSION['id'];
      if(count($error) == 0)
      {
        $messenger_data = $messenger . $messenger_data;
        $query = mysqli_query($dbc, "UPDATE `users` SET `phonenumber` = '$contact_number', `contactemail` = '$email', `whatstg` = '$messenger_data' WHERE `user_id` = '$id'");

        if($query)
          $msg = '<div class = "alert alert-success">Success</div>';
        else $msg = '<div class = "alert" alert-danger">Something went wrong!</div>';
      }
    }

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
    $query = mysqli_query($dbc, "SELECT `ask`, `answer`,`phonenumber`, `contactemail`, `whatstg` FROM `users` WHERE `hash` = '$hash'");

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

      $phonenumber = $row['phonenumber'];
      $contactemail = $row['contactemail'];
      $whatstg = $row['whatstg'];

      if($whatstg[0] == 1){
        $type = 'WhatsApps';
        $selected1 = 'selected';
      }
      else if($whatstg[0] == '2') {
        $type = 'Telegram';
        $selected2 = 'selected';
      }

      $whatstg = substr($whatstg, 1);
    }
    else $soraw = array(0);
?>
<div class ="container">
  <div class="row">
    <div class="col-md-9">
    	<div class = "row border border-white padding rounded">
	    	<div class = "col-md-3">
	    		<img src="/resource/img/profile-pictures1.png" class="img-thumbnail">
          <br/>
	    		<a href = "/logout" class="btn btn-primary">Выход</a>
	    	</div>
        <div class = "col-md-9">
        	<table height = "150px">
  					<caption><strong>Информация</strong></caption>
    					<tr>
    						<td width="200">Имя:</td>
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
              <tr>
                <td>Телефон:</td>
                <td><?php echo $phonenumber; ?></td>
              </tr>
              <tr>
                <td>Мессенджер (<?php echo $type; ?>) :</td>
                <td><?php echo $whatstg; ?></td>
              </tr>
              <tr>
                <td>Эл. почта:</td>
                <td><?php echo $contactemail; ?></td>
              </tr>
          </table>
        </div>
	    </div>
	    <div class = "row border border-white padding">
        <div class = "col-md-6">
	        <form action = "/profile" method="post" class = "passwordform">
            <p>
              <center>
                <strong>
                  Изменить пароль
                </strong>
              </center>
            </p>
  				  <p><input type="password" class = "form-control" name="lastpass" placeholder="Текщий пароль"></p>
           	<p><input type="password" class = "form-control" name="newpass1" placeholder="Новый пароль"></p>
            <p><input type="password" class = "form-control" name="newpass2" placeholder="Повторите новый пароль"></p>
            <p><input name="update" class="btn btn-primary btn-sm" value="Изменить" type="submit"></p>
			    </form>
	    	</div>
        <div class = "col-md-6">
          <?php 
            if(count($error) > 0) {
              foreach ($error as $key) {
                  echo '<div class="alert alert-danger" role="alert">'. $key . 
                  '</div>';
                  }
              }
          ?>
          <form action="/profile" method="post">
            <p>
              <center>
                <strong>
                  Контактные данные
                </strong>
              </center  >
            </p>
            <?php echo $msg;  ?>
            <p><input type="text" class = "form-control" name="phonenumber" placeholder="Телефон" value = "<?php echo $phonenumber; ?>"></p>
            <p><input type="email" class = "form-control" name="contactemail" placeholder="Эл. почта" value = "<?php echo $contactemail; ?>"></p>
            <p>
              <div class = "row">
                <div class = "col-6">
                  <select class = "custom-select custom-select-sm" name = "messenger">
                    <option value = "0" selected>Меседжеры</option>
                    <option value = "1"<?php echo $selected1; ?>>WhatsApp</option>
                    <option value = "2"<?php echo $selected2; ?>>Telegram</option>
                  </select>
                </div>
                <div class = "col-6">
                  <input type="text" class = "form-control" name="messengerdata" placeholder="+998931234567" value = "<?php echo $whatstg; ?>">
                </div>
              </div>
            </p>
            <p><input name="contact" class="btn btn-primary btn-sm" value="Сохранить" type="submit"></p>
          </form>
        </div>
	    </div>
	    <div class = "row">
	        <div class = "col-md-12">
		        <?php 
					if($change == 1) echo '<div class = "alert alert-success"><i class = "glyphicon glyphicon-ok"></i> ' . msgsend(1) . '</div>'; 
          else if($change == 2) echo '<div class = "alert alert-danger">' . msgsend(2) . '</div>';

					if(count($err) > 0) {
            foreach ($err as $key) {
              echo '<div class="alert alert-danger" role="alert">'. $key . 
              '</div>';
            }
          } ?>
		    </div>
		</div>
		<div class = "row">
	        <div class = "col-md-12">
		    <?php 
          if(count($soraw) > 0)
            echo '<h4>Вопросы</h4><hr/>';
		    	foreach ($soraw as $key => $value) 
            {
              $query = "SELECT * FROM `questions` WHERE `id` = '$value'";
              $query = mysqli_query($dbc, $query);
              if($query)
              {

                $row = mysqli_fetch_assoc($query);
                $since = $row['dates'];
                $since = time() - $since;
                $time = time_since($since); 

                $tags = explode(" ", $row['tags']);
                $metki = '';
                foreach ($tags as $tag) {
                  $metki .= '<a class = "badge badge-light" href = "/question/?id='. urlencode($tag) . '">'. htmlentities($tag) . '</a> ';
                }

                echo '
                  <div class = "row blockquote">
                    <div class = "col-md-8">
                        <div class="">
                          <a href = "/question/'. $row['id'] . '" class = "questionlink">' . $row['zagqu'] . '</a>
                          <div class = "row">
                              <div class = "col-md-4">
                                <p> 
                                      <small>Asked ' . $time . ' ago </small>
                                    
                                  </p>
                              </div>
                              <div class = "col-md-8">
                              '. $metki . '
                              </div>
                          </div>
                      </div>
                    </div>

                    <div class = "col-md-2 border border-white">
                      <center><small>' . $row['view'] . '</small>
                          <h6><small>просмотров</small></h6>
                        </center>
                    </div>

                    <div class = "col-md-2 border border-white">
                        <center><small>' . $row['answers'] . '</small>
                          <h6><small>Ответов</small></h6>
                        </center>
                    </div>
                  </div>
                  ';
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