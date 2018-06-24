<?php
  session_start();
  $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');
  if (!$dbc) {
          die("Connection failed: " . mysqli_connect_error());
      }
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
                $title = 'Последние вопросы';
                $page = 0;
                $query = "SELECT * FROM `questions` ORDER BY `id` DESC LIMIT $page, 10";
                $query = mysqli_query($dbc, $query);
                $addr = '?page=';

                echo '  <div class = "row">
                      <div class = "col-md-8">
                        <h3>' . $title .  '</h3>
                      </div>
                      <div class = "col-md-4">
                        <a class = "btn btn-primary" href= "/question/?cat=noans">Неотвеченные</a>
                      </div>
                    </div>
                    <hr/>';


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
                        $last = $row['id'];
                    }
                    $pageid = $_GET['page'] + 1;
                    $left = ($pageid  - 2 > 2) ? $pageid - 3 : 1;
                    $right = ($last - $pageid > 2) ? $pageid + 3 : $last;
                        
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
                  else echo "Something went wrong!";
            }

        ?>
        </div>
    </div>
    <?php include 'right.php'; ?>
  </div>
</div>

<?php 
  include 'foot.php';
  mysqli_close($dbc); 
?>