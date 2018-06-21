<?php
  session_start();
  include 'head.php';
  $dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');

  $title = "";
  if(!isset($URL_Parts['0']))
    if(!isset($module))
    {
      $re = "SELECT * FROM `algo` ORDER BY `id` DESC LIMIT 0, 6";
      $title = "Статии";
    }
    else {
      if($module == 'cpp')
        $title = "Алгоритмы на C++";
      else if($module == 'python')
        $title = "Алгоритмы на Python";
      else if($module == 'Java')
        $title = "Алгоритмы на Java";
      else if($module == 'web')
        $title = "Статии про Web разроботка";
      else if($module == 'js')
        $title = "Алгоритмы на JavaScript";
      else if($module == 'php')
        $title = "Про PHP";
      $re = "SELECT * FROM `algo` WHERE `lang` = '$module' ORDER BY `id` DESC LIMIT 0, 6";
    }
  else {
    $urlpart= $URL_Parts['0'];
    $re = "SELECT * FROM `algo` WHERE `name` = '$urlpart' AND `lang` = '$module'";
    mysqli_query($dbc,"UPDATE `algo` SET `views` = `views` + 1 WHERE `name` = '$urlpart'");
  }

  $re = mysqli_query($dbc,$re);
  $algoname = $module . "/" . $URL_Parts['0'];

  if(isset($_POST['sendcomment']))
  {
    $data = $_POST;
    if(!empty($data['comment']))
    {
      date_default_timezone_set('Europe/Moscow');
      $time = date("F j, Y, H:i");
      $comment = trim($data['comment']);
      $user = $_SESSION['username'];
      $name = $_SESSION['name'] . " " . $_SESSION['surname'];
      $email = $_SESSION['email'];
      $nenaw = $algoname;
      $q = "INSERT INTO `comment` (algoname, likes, comment, user, username, email, dates) VALUES ('$algoname', '0', '$comment', '$user', '$name','$email', '$time')";

      $q = mysqli_query($dbc,$q);

      $q = "UPDATE `algo` SET `comment` = `comment` + 1 WHERE `name` = '$urlpart'";
      $q = mysqli_query($dbc,$q);
    }
  }
  else $msg = "Error!";

  $q = "SELECT * FROM `comment` WHERE `algoname` = '$algoname' ORDER BY `id` DESC";
  $q = mysqli_query($dbc,$q);


?>
 <div class ="container">
    <div class="row">
      <div class="col-md-9">
        <div class = "main">
        	<?php
  					if(!isset($URL_Parts['0']))
  					{
  						if(mysqli_num_rows($re) > 0)
  						{
  							while($row = mysqli_fetch_assoc($re))
  							{
  								/*echo "
  								<div class = \"algoview\">
  								  <a href = \"/tag/cpp/".$row['name']."\">
  								    <img class=\"img-thumbnail\" src=\"/resource/img/".$row['images']."\" alt = \"".$row['title']."\">
  								    <div>".$row['title']."</div>
  								  </a>
  								</div>";*/
  							
  							echo 
  								'
  								<div class="jumbotron">
  									<div class = "row">
  										<div class = "col-md-4">
  											<img class="img-thumbnail" src="/resource/img/' .$row['images']. '"  alt = "'.$row['title'].'">
  										</div>
  										<div class = "col-md-8">
  											<h4><a href = "/tag/'. $row['tags'].'/'. $row['name'] .'">'. $row['title'] .'</a></h4>
  										</div>
  									</div>
  									<div class = "row">
  										<div class = "col-md-12">
  											<div class = "pull-right">
  												<h5>
  														<i class="glyphicon glyphicon-comment"></i> '. $row['comment']. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  														<i class="glyphicon glyphicon-eye-open"></i> '. $row['views']. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  														<i class="glyphicon glyphicon-time"></i> ' . $row['dates']. '
  												</h5>
  											</div>
  										</div>
  									</div>
  								</div>';
  							}
  						}
  					}
  					else
  					{
  						if(mysqli_num_rows($re) > 0)
  						{
  					  		$row = mysqli_fetch_assoc($re);
  					  		include 'algo/'.$row['way'];
  						}
  						else
  					  		echo "<span>Sorry bro</span>";
  					}
  				?>
        </div>
  	  </div>
      <?php include 'right.php'; ?>
    </div>
      <div class = "row">
        <div class = "col-md-9">
          <?php if(mysqli_num_rows($re) > 0 && isset($URL_Parts['0'])) { 
            if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']))
            {
              if($_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
              {
            ?>
            <div class = "comment">
              <h4> Комментировать</h4>
              <form action = "/tag/<?php echo $module . "/" . $urlpart; ?>" method="post" id = "commentform" data = "hard">
                <section id="page-demo">
                  <textarea id="txt-content" name = "comment" data-autosave="editor-content" required></textarea>
                </section>
                <p><input type="submit" class = "btn btn-primary btn-sm margin" value = "Отправить" name = "sendcomment"></p>
              </form>
            </div>
            <?php 
              } 
            }
            else echo '<label>Чтобы оставить комментарий вы должны <a href = "/login">войти</a></label>';
            
            while ($c = mysqli_fetch_assoc($q))
            {
              echo '
              <div class="well">
                <div class="media-left media-top">
                  <img class="media-object" src="/resource/img/profile-pictures-small.png" alt="...">
                </div>
                <div class="media-body">
                  <a href = "/user/'.$c["user"].'">'. $c["username"] .'</a>
                  <small class = "pull-right"><i class="fas fa-clock"></i> '. $c["dates"] .'</small>'
                  . $c['comment'] .
                '</div>
              </div>';
            }
            ?>
            </div>
            <?php } ?>
        </div>
      </div>
  </div>
  <div class = "footer>
    <div class = "container">
      &copy; 2018
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