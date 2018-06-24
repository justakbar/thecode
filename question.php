<?php 
	session_start();
  	$dbc = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
  	include 'include/function.php';
  	if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) &&
                          $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
  	{
	  	if($_POST['send'])
		{
			$text = htmlentities( $_POST['questiontext'], ENT_QUOTES);
			$login = $_SESSION['username'];
			$times = time(); 

			$query = "INSERT INTO `answer` (answer, qu_id, login, dates) VALUES ('$text', '$module', '$login', '$times')";

	  		$query = mysqli_query($dbc,$query);

	  		if($query)
	  		{
	  			$qu = "UPDATE `questions` SET `answers` = `answers` + 1 WHERE `id` = '$module'";
	  			$qu = mysqli_query($dbc, $qu);

	  			$user = $_SESSION['username'];
	  			$qu = "SELECT `answer` FROM `users` WHERE `login` = '$user'";
	  			$qu = mysqli_query($dbc, $qu);

	  			$row = mysqli_fetch_assoc($qu);
	  			$add = $row['answer'];
	  			$add .= $module . ' ';

	  			$qu = "UPDATE `users` SET `answer` = '$add' WHERE `login` = '$user'";
	  			$qu = mysqli_query($dbc,$qu);
	  		}
		}
	}
	else $msg = '<div class="alert alert-warning" role="alert">Чтобы ответить на вопросы вы  должни <a href = "/login"> войти </a></div>';

  	include 'head.php'; 
?>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
            	<div class = "main">
                    <?php 
		        	if($page == 'question' && !isset($module))
		        	{
		        		echo $msg;

		        		$_GET['page']--;
		        		if(isset($_GET['page']) && !isset($_GET['cat']) && $_GET['cat'] != 'noans')
		        		{
		        			$page = $_GET['page'] * 10;
		        			$query = "SELECT * FROM `questions` ORDER BY `id` DESC LIMIT $page, 10";
  							$query = mysqli_query($dbc, $query);
		        			$addr = '?page=';
		        			$title = 'Вопросы';
		        			$lastid = mysqli_query($dbc, "SELECT `id` FROM  `questions` ORDER BY `id` DESC LIMIT 1");
		        			$r = mysqli_fetch_assoc($lastid);
		        			$last = $r['id'];
		        		}
		        		else if($_GET['cat'] == 'noans')
  						{
  							$title = '<h3>Неотвеченные</h3>';
			        		$addr = '?cat=noans&page=';

			        		$page = isset($_GET['page']) ? ($_GET['page']) * 10 : 0;

			        		$query = "SELECT * FROM `questions` WHERE `answers` = 0 ORDER BY `id` LIMIT $page, 10";
  							$query = mysqli_query($dbc, $query);

  							$lastid = mysqli_query($dbc, "SELECT `id` FROM  `questions` WHERE `answers` = 0");
		        			$last = mysqli_num_rows($lastid);
  						}
  						else if(isset($_GET['id']))
		        		{
		        			$search = $_GET['id'];
		        			$title = '<h3>' . $search . '</h3>';
		        			$addr = '?id='.urlencode($search).'&page=';
		        			$msg = '<h3>' . $search . '</h3><hr/>';
		        			$page = isset($_GET['page']) ? $_GET['page'] * 10 : 0;
			        		$query = "SELECT * FROM `questions` WHERE `tags` LIKE '%$search%' ORDER BY `id` DESC LIMIT $page, 10";
  							$query = mysqli_query($dbc, $query);

  							$lastid = mysqli_query($dbc, "SELECT `id` FROM  `questions` WHERE `tags` LIKE '%$search%'");
		        			$last = mysqli_num_rows($lastid);
		        		}
  						else {
  							$page = 0;
  							$query = "SELECT * FROM `questions` ORDER BY `id` DESC LIMIT $page, 10";
  							$query = mysqli_query($dbc, $query);
  							$addr = '?page=';
  							$title = 'Последние вопросы';

  							$lastid = mysqli_query($dbc, "SELECT `id` FROM  `questions` ORDER BY `id` DESC LIMIT 1");
		        			$r = mysqli_fetch_assoc($lastid);
		        			$last = $r['id'];
  						}

				   		echo '	<div class = "row">
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
							}

				    		/*$num = "SELECT `id` FROM `questions` ORDER BY `id` DESC LIMIT 1";
							$num = mysqli_query($dbc, $num);
							$lastid = mysqli_fetch_assoc($num);
							$last = $lastid['id'];*/

							$last =  ceil($last/10);
							$pageid = $_GET['page'] + 1;
							$left = ($pageid  - 2 > 2) ? $pageid - 3 : 1;
							$right = ($last - $pageid > 2) ? $pageid + 3 : $last;

							if($left - 1 == $_GET['page'])
								$class = 'class = "actived"';
							else $class = '';

							if($left > 1)
								$span = '<span>. . . </span>';
							else $span = '';
							echo '<div class = "pagination"><a '. $class .' href="/question/' . $addr . '1"> 1 </a>' . $span;

							while(++$left <= $right)
							{
								if($left - 1 != $_GET['page'])
									echo '<a href="/question/'. $addr . $left .  '"> ' . $left .  '</a>';
								else echo '<a class = "actived" href="/question/' . $addr . ''. $left .  '"> ' . $left .  '</a>';
							}
							echo '</div>';
				    	}
				    	else echo "Something went wrong!";
			    	}
			    	else if($page == 'question' && is_numeric($module))
			    	{
			    		echo $msg;
			    		$search = (string)$_SERVER['REMOTE_ADDR'];
			    		$id = $_SESSION['id'];
			    		$query = "SELECT * FROM `questions` WHERE `id` = '$module'";
			    		$query = mysqli_query($dbc, $query);
			    		if($query)
			    		{
				    		if(mysqli_num_rows($query))
				    		{
					    		$row = mysqli_fetch_assoc($query);
					    		$tg = $row['tags'];
			    				$zag = $row['zagqu'];
					    		$id = $_SESSION['id'];
					    		$ip = $_SERVER['REMOTE_ADDR'];
					    		$view = $row['view'];
					    		$arr = explode(",", $row['views']);
					    		$userid = explode(",", $row['viewed']);

					    		if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) && $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code'])
				    			{
				    				if(!in_array($id, $userid))
				    				{
				    					$id = $id . ','. $row['viewed'];
				    					$qu = mysqli_query($dbc, "UPDATE `questions` SET `viewed` = '$id', `view` = `view` + 1  WHERE `id` = '$module'");
				    					$view++;
				    				}
				    			}
					    		else if(!in_array($ip, $arr))
					    		{
					    			$ip = $ip . ',' . $row['views'];
					    			$qu = mysqli_query($dbc, "UPDATE `questions` SET `views` = '$ip', `view` = `view` + 1  WHERE `id` = '$module'");
					    			$view++;
					    		}
					    		$tags = explode(" ", $row['tags']);
								$metki = '';
								foreach ($tags as $tag) {
									$metki .= '<a class = "badge badge-light" href = "/question/?id='. urlencode($tag) . '">'. htmlspecialchars($tag) . '</a> ';
								}
					    		$view = ($view > 1) ? 'viewed: ' .$view .  ' times' : 'viewed: ' .$view .  ' time';
					    		echo '
					    			<div class = "row">
					    				<div class = "col-md-9">
					    					<h3>'. $row['zagqu'] . '</h3>
					    				</div>
					    				<div class = "col-md-3">
					    					<h6>
					    						<small>' . $view . '</small><br/>
					    						<small> Asked: '  . time_since(time() - $row['dates']) .  ' ago</small><br/>
					    						<small> User: <a class = "questionlink" href = "/user/' . $row['login'] . '">' . $row['login'] . '</a></small>
					    					</h6> 
					    				</div>
					    			</div>
					    			<hr/>
					    			<div class = "row">
					    				<div class = "col-md-12">
					    					<h4>Question</h4>' .
					    						htmlspecialchars_decode($row['question']) . 
					    					'<div class = "row">
						    					<div class = "col-md-12" align = "right">
						    						Метки: ' . $metki .  ' 
						    					</div>
						    				</div>
						    			</div>
					    			</div>';

					    		$query = "SELECT * FROM `answer` WHERE `qu_id` = '$module' ORDER BY `id` DESC";
					    		$query = mysqli_query($dbc,$query);

					    		$add = ($row['answers'] > 1) ? 's':'';
					    		echo '<hr/><h4>'.$row['answers'] . ' answer' . $add . '</h4><hr/>';
					    		if(mysqli_num_rows($query) > 0)
					    		{
									while($row = mysqli_fetch_assoc($query))
						    		{
						    			$since = $row['dates'];
										$since = time() - $since;
										$time = time_since($since);

										echo '
										<div class = "blockquote">
											<div class = "row">
												<div class = "col-md-2 border-right">
													<span style = "font-size: 11pt;">Ответил(а)</span>
													<a class = "questionlink" href = "/user/' . $row['login'] . '">'. $row['login']  . '</a><br/>
													<small> ' . $time . ' ago </small>
												</div>
												<div class = "col-md-10"> ' . 
													htmlspecialchars_decode($row['answer']) .
												'</div>
											</div>
										</div>
										<hr/>';
						    		}
					    		}
					    		$exist = true;
				    	?>
                        <h3>Ответить</h3>
                        <form action="/question/<?php echo $module; ?>" method="post">
                            <section id="page-demo">
                                <textarea id="txt-content" name="questiontext" data-autosave="editor-content" required></textarea>
                            </section>
                            <input type="submit" name="send" value="Отправить" class="btn btn-secondary margin">
                        </form>
                        <?php 
				    		}
				    		else { echo '<h4> Question not exist!</h4>'; $exist = false; }
				    	}
			    	} ?>
			    </div>
            </div>
            <?php include 'right.php'; ?>
        </div>
    </div>
<?php include 'foot.php'; ?>