<?php include 'head.php'; 
	
	if($_POST['send'])
	{
		echo "success";
	}
		
?>	
	<input type="button" id = 'send' value = "send">

	<?php  if($_POST['send'])
	{
		echo "success";
	}
	?>

<?php include 'foot.php'; ?>