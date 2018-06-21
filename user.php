<?php 
  session_start();
  $dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
  include 'include/function.php';

  if(isset($module) && $page == 'user')
  {
    if(!empty($module))
    {
      $user = htmlentities($module);

      $query = "SELECT `login`, `first_name`, `last_name`, `ask`, `answer`, `rating` FROM `users` WHERE  `login` = '$user'";
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
      <div class = "row">
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
        <?php } else echo $msg;
            } else echo $msg; ?>
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