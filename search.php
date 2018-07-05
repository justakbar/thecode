<?php
  session_start();
  include 'include/getQuestionsFunctions.php';
  $dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
  if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
  }
  if(isset($_GET['qu']))
    $qu = htmlentities($_GET['qu'], ENT_QUOTES);
  $data = search($qu);

  include 'head.php'; 
?>
<div class ="container">
  <div class="row">
    <div class="col-md-9">
      <div class = "main">
        <?php 
          $pagination = array_pop($data);
          foreach ($data as $key => $value) {
        ?>
            <div class = "row blockquote">
              <div class = "col-md-8">
                  <a href = "/question/<?php echo $value['id']; ?>" class = "questionlink"><?php echo $value['zagqu']; ?></a>
                  <div class = "row">
                      <div class = "col-md-5">
                        <p> 
                              <small>Asked <a class = "a" href = "/user/<?php echo $value['login']; ?>"><?php echo $value['login']; ?> </a><?php echo $value['dates']; ?></small>
                            </p>
                      </div>
                      <div class = "col-md-7">
                      <?php echo $value['tags']; ?>
                      </div>
                  </div>
              </div>

              <div class = "col-md-2 border border-white">
                <center><small><?php echo $value['views']; ?></small>
                    <h6><small>просмотров</small></h6>
                  </center>
              </div>

              <div class = "col-md-2 border border-white">
                  <center><small><?php echo $value['answers']; ?></small>
                    <h6><small>Ответов</small></h6>
                  </center>
              </div>
            </div>
          <?php
            } echo $pagination;
        ?>
      </div>
    </div>
    <?php include 'right.php'; ?>
  </div>
</div>
<?php include 'foot.php'; ?>