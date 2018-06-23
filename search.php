<?php
  session_start();
  $dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
  if (!$dbc) {
          die("Connection failed: " . mysqli_connect_error());
      }
  if(isset($_GET['qu']))
  {
    $qu = htmlentities($_GET['qu'], ENT_QUOTES);

    $query = "SELECT * FROM `questions` WHERE `zagqu` LIKE '%$qu%'
                  OR `question` LIKE '%$qu%' OR `tags` LIKE '%$qu%'";

    $query = mysqli_query($dbc, $query);
  }

  include 'head.php'; 
?>
<div class ="container">
  <div class="row">
    <div class="col-md-9">
      <div class = "main">
        <?php 
          if($query)
            if(mysqli_num_rows($query) > 0)
            {
              while($row = mysqli_fetch_assoc($query))
              {
                echo '
                    <div class = "row">
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
                      </div>
                    </div>';
              }
            }
            else echo "Nothing";
        ?>
      </div>
    </div>
    <?php include 'right.php'; ?>
  </div>
</div>
<?php include 'foot.php'; ?>