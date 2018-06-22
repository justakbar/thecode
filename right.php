<div class="col-md-3 metki">
	<?php
		$query = "SELECT `tags` FROM `questions`";
		$query = mysqli_query($dbc, $query);

		while($row = mysqli_fetch_assoc($query))
			$array .= $row['tags'] . " ";

		$array = array_count_values(explode(" ", $array));
		array_pop($array);
		arsort($array);
		if($page == 'question' && is_numeric($module))
		{
			echo '
				<label>
			    	Похожие вопросы
			  	</label>';
			$query = "SELECT `id`, `zagqu`, `tags` FROM `questions` WHERE `question` LIKE '%$zag%' OR `zagqu` LIKE '%$zag%' OR `tags` LIKE '%$tg%'";
			$query = mysqli_query($dbc, $query);
			if($query)
			{
				while($row = mysqli_fetch_assoc($query))
				{
					if($row['id'] != $module)
					{
						$tg = explode(" ", $row['tags']);
						$met = '';
						foreach ($tg as $tag) {
							$met .= '<a class = "badge" href = "/question/?id='. urlencode($tag) . '">'. htmlentities($tag) . '</a> ';
						}
						echo '
							<div class = "row">
								<div class = "col-md-12">
									<h4><a class = "questionlink" href = "/question/'. $row['id'] .'">' . $row['zagqu'] . '</a></h4>
								' . $met .  '
								</div> 
							</div>
						';
					}
				}
			}
			else echo "Что то пошло не так!";
		
		echo "<hr/>";
		}
		echo '<label>
				    	Метки
				  </label>';
		$i = 0;
		foreach ($array as $key => $value) {
			if(++$i == 11) break;
			echo '
				  <div class = "row">
					<div class = "col-md-12">
						<p>
							<code><a style = "text-decoration: none;" href = "/question/?id='.urlencode($key).'"><code>'.$key. 
							'</code> </a></code> 
							<span> x ' . $value . '</span>
						</p>
					</div>
				</div>';
		}
/*

		while ($rows = mysqli_fetch_assoc($r))
		echo "<p><code><a style = \"text-decoration: none;\" href = \"/tag/".urlencode($rows['algo'])."\"><code>".$rows['name'] . 
		"</code> </a></code> <span> x " . $rows['howmany'] . "</span></p>";*/
	?>
</div>