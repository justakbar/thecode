<?php 
  session_start();
  $dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
  include 'include/function.php';

  if(isset($module) && $page == 'user')
  {
    if(!empty($module))
    {
      $user = htmlentities($module);

      $query = "SELECT * FROM `users` WHERE  `login` = '$user'";
      $query = mysqli_query($dbc, $query);
    }
    else $msg = "User not found!";

    if($query)
    {
      if(mysqli_num_rows($query))
      {
        $row = mysqli_fetch_assoc($query);
        $login = $row['login'];
        $name = $row['first_name'];
        $surname = $row['last_name'];
        $answer = $row['answer'];
        $ask = $row['ask'];

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

        $type = $whatstg[0];

        if($type == '1')
          $type = 'WhatsApp';
        else if($type == '2')
          $type = 'Telegram';
        $whatstg = substr($whatstg, 1);
      }
      else $msg = "User not found!";
    }
  }
  else $msg = "User not found!";

  include 'head.php'; 
?>
<div class ="container">
  <div class="row">
    <div class="col-md-9">
      <div class = "row row border border-white padding rounded">
        <?php 
          if(!empty($module))
          {
            if(mysqli_num_rows($query)) 
              { ?>
                <div class = "col-md-3">
                  <img src="/resource/img/profile-pictures1.png" class="img-thumbnail">
                </div>
                <div class = "col-md-9">
                  <table width="50%" height = "150px">
                    <caption><center><strong>Информация</strong></center></caption>
                
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
                      <td>Месенджер (<?php echo $type; ?>):</td>
                      <td><?php echo $whatstg; ?></td>
                    </tr>
                    <tr>
                      <td>Эл. почта:</td>
                      <td><?php echo $contactemail; ?></td>
                    </tr>
                  </table>
                </div>
        <?php } else echo $msg;
          } else echo $msg; ?>
      </div>
      <div class = "row">
        <div class = "col-md-12">
          <?php 
            if(!empty($module))
            {
              if(mysqli_num_rows($query)) 
                {
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