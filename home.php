<?php
  session_start();
  $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
  include 'include/function.php';
  include 'head.php'; 
?>
<div class ="container">
  <div class="row">
    <div class="col-md-9">
      <div class = "main">
        <?php 
              if($page == 'index')
              {
                echo $msg;
                $msg = '<h3>Последние вопросы</h3><hr/>';

                if(isset($_GET['page']))
                {
                  $_GET['page']--;
                  $page = $_GET['page'] * 10;
                }
                else $page = 0;

                $query = "SELECT * FROM `questions` WHERE `id` ORDER BY `id` DESC LIMIT $page, 10";
                $query = mysqli_query($dbc, $query);

                if(isset($_GET['id']))
                {
                  $search = $_GET['id'];
                  $msg = '<h3>' . $search . '</h3><hr/>';
                  $query = "SELECT * FROM `questions` WHERE `tags` LIKE '%$search%' ORDER BY `id` DESC LIMIT 0, 10";
                  $query = mysqli_query($dbc,$query);
                }
                echo $msg;
                echo '<div class = "row">';
                if($query)
                {
                  while($row = mysqli_fetch_assoc($query))
                  {
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
                    </div>
                    ';
                  }
                  $num = "SELECT `id` FROM `questions` ORDER BY `id` DESC LIMIT 1";
                  $num = mysqli_query($dbc, $num);
                  $lastid = mysqli_fetch_assoc($num);
                  

                  $last =  $lastid['id'];
                  $pageid = $_GET['page'];
                  $left = ($pageid  - 1 > 2) ? $pageid - 2 : 1;
                  $right = ($last - $pageid > 2) ? $pageid + 4 : $last;
                  
                  if($left - 1 == $_GET['page'])
                    $class = 'class = "actived"';
                  else $class = '';

                  if($left > 1)
                    $span = '<span>. . . </span>';
                  else $span = '';
                  echo '<div class = "pagination"><a '. $class .' href="/question/?page=1"> 1</a>' . $span;

                  while(++$left <= $right)
                  {
                    if($left - 1 != $_GET['page'])
                      echo '<a href="/question/?page=' . $left .  '"> ' . $left .  '</a>';
                    else echo '<a class = "actived" href="/question/?page=' . $left .  '"> ' . $left .  '</a>';
                  }

                  echo '</div>';
                  }
            }

        ?>
        </div>
      </div>
    </div>
    <?php include 'right.php'; ?>
  </div>
</div>

<?php 
  include 'foot.php';
  mysqli_close($dbc); 
?>