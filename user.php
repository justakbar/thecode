<?php 
session_start();
	$conn = mysqli_connect('localhost','algorithms','nexttome', 'algoritm');
	include 'include/user.function.php';

	if(isset($module) && $page == 'user') {
		$user = htmlentities($module,ENT_QUOTES);

		$value = getUser ($user, $conn);
		$soraw = getUserQuestion($module, $conn);
		$order = getOrder($value['login'],$conn);

		if (empty($soraw))
			$msg2 = 'Вопросов нет';
		if(empty($value))
			$msg1 = "User not found!";

		$value['phonenumber'] = (!empty($value['phonenumber'])) ? '+' . $value['phonenumber'] : $value['phonenumber'];
    	$value['messenger_number'] = (!empty($value['messenger_number'])) ? '+' . $value['messenger_number'] : $value['messenger_number'];
	}
	else $msg = "User not found!";

	include 'head.php'; 
?>
	<div class ="container">
		<div class="row">
			<div class="col-md-9">
				<div class = "row row border border-white padding rounded">
				<?php 
					if (!empty($value)) {
				?>
					<div class = "col-md-3">
						<img src="/resource/img/profile-pictures1.png" class="img-thumbnail">
					</div>
					<div class = "col-md-9">
						<table width="50%" height = "150px">
							<caption><center><strong>Информация</strong></center></caption>

							<tr>
								<td width="200">Имя:</td>
								<td><?php echo $value['first_name']; ?></td>
							</tr>
							<tr> 
								<td>Фамилья:</td>
								<td><?php echo $value['last_name']; ?></td>
							</tr>
							<tr>
								<td>Логин:</td>
								<td><?php echo $value['login']; ?></td>
							</tr>
							<tr>
								<td>Задал(а) вопрос:</td>
								<td><?php echo $value['ask']; ?></td>
							</tr>
							<tr>
								<td>Ответил(а):</td>
								<td><?php echo $value['answer']; ?></td>
							</tr>
							<tr>
								<td>Телефон:</td>
								<td><?php echo $value['phonenumber']; ?></td>
							</tr>
							<tr>
								<td>Месенджер (<?php echo $value['messenger']; ?>):</td>
								<td><?php echo $value['messenger_number']; ?></td>
							</tr>
							<tr>
								<td>Эл. почта:</td>
								<td><?php echo $value['contactemail']; ?></td>
							</tr>
						</table>
					</div>
					<?php
						} else echo $msg1;
					?>
				</div>
			 <ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Вопросы</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Заказы</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<?php 
					if (!empty($soraw)) {
						foreach ($soraw as $key => $value) {
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
					}
				} else echo $msg2;
				?>
				</div>
				<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<?php 
					$num = 0;
					foreach ($order as $key => $val) {

						$num++;
						?>
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne">
									<div class = "alert alert-success">
										<i class="fas fa-sort-down"></i>
										<a class = "questionlink" role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $num; ?>" aria-expanded="true" aria-controls="collapseOne">
											<?php echo $val['zagqu']; ?>
										</a>
									</div>
								</div>
								<div id="<?php echo $num; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class = "border border-white padding-order margin">
											<div class = "row">
												<div class = "col-md-12">
													<h4>
														<a class = "questionlink" href = "/ordvac/<?php echo $val['id']; ?>"><?php echo $val['zagqu']; ?></a>
													</h4> 
												</div>
											</div>
											<div class = "row">
												<div class = "col-md-6">
													<span class = "cost">
														<?php echo $val['cost'] ?> за проект 
													</span>
													<br/>
													<span class = "cost"> 
														<?php echo $val['viewed'] . htmlspecialchars(" • "); ?>
													</span>
													<span class = "cost">
														<?php echo $val['published']; ?>
													</span>
												</div>
												<div class = "col-md-6">
													<?php echo $val['metki']; ?>
												</div>
											</div>
											<hr/>
											<div class = "row tekst">
												<div class = "col-md-12">
													<?php echo $val['tekst']; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php include 'right.php'; ?>
	</div>
</div>
<?php include 'foot.php'; ?>