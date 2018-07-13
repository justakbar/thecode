<div class="col-md-3 metki">
<?php
		
if($page == 'question' && is_numeric($module))
{
	$same = getSameQuestion($module,$conn);
	echo '	
		<label>
	    	Похожие вопросы
	  	</label>';

	foreach ($same as $key => $value) { 
?>
	<div class = "row">
		<div class = "col-md-12">
			<h6>
				<a class = "questionlink" href = "/question/<?php echo $value['id']; ?>">
					<?php echo $value['zagqu']; ?>
				</a>
			</h6>
		</div> 
	</div>
<?php 
	}
}
	$array = getMetki();
	echo '<label>
			Метки
		</label>';
	$i = 0;
	if (!empty($array)) {
		foreach ($array as $key => $value) {
			if(++$i == 11) break;
?>
	<div class = "row">
		<div class = "col-md-12">
			<p>
				<a class = "badge badge-light" href = "/question/?id=<?php echo urlencode($key); ?>"><?php echo $key; ?></a> 
				<span> x <?php echo $value; ?></span>
			</p>
		</div>
	</div>
<?php 
	}
}
	?>
</div>