<?php 
session_start();

$conn = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');	

include 'include/validation.function.php';
include 'include/getOrdvacFunctions.php';

if($_POST['send'])
{
	$zagqu  = htmlentities(trim($_POST['zagqu']),ENT_QUOTES);
	$cost   = htmlentities(trim($_POST['cost']),ENT_QUOTES);
	$valyuta = htmlentities($_POST['valyuta'],ENT_QUOTES);
	$text   = htmlentities(trim($_POST['noise']),ENT_QUOTES);
	$domain = htmlentities($_POST['domain'],ENT_QUOTES);

	$error = validateOrder($zagqu, $cost, $valyuta, $domain, $text, $conn);	
}

include 'head.php';
?>

<div class="container">
	<div class="row">
		<div class="col-md-9">
        	<div class = "main">

<?php
	if ($page != 'ordvac' || $module != 'order') {
?>
<div class="alert alert-success" role="alert">
	<div class = "row">
		<div class = "col-md-8">
			У вас есть идея или проект и вам нужны программисты, тогда вам сюда
		</div>
		<div class = "col-md-4">
			<a class = "btn btn-primary" href="/ordvac/order"> Разместить заказ</a>
		</div>
	</div>
</div>
<?php
}
if ($page == 'ordvac' && !isset($module)) {

	$data = getData($conn);
	echo '<h4>Заказы</h4>';
	foreach ($data as $value => $key) {
	?>
	<div class = "border border-white padding-order">
		<div class = "row">
			<div class = "col-md-9">
				<h4>
					<a class = "questionlink" href = "/ordvac/<?php echo $key['id'] ?>"><?php echo $key['zagqu']; ?></a>
				</h4> 
			</div>
			<div class = "col-md-3">
				<center><span class = "cost"><?php echo $key['cost']; ?> </span></center>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-6">
				<h6 class = "cost"> 
					viewed: <?php echo $key['views']; ?>
				</h6>

				<h6 class = "cost">
					<?php echo $key['published']; ?>
				</h6>
			</div>
			<div class = "col-md-6">
				<?php echo $key['metki']; ?>
			</div>
		</div>
	</div>
	<hr/>
<?php
	}
	echo getPagination($_GET['page'],$conn);
} else if ($page == 'ordvac' && is_numeric($module)) {

	$value = getOrderData($module,$conn);
?>
	<div class = "border border-white padding-order margin">
		<div class = "row">
			<div class = "col-md-12">
				<h4>
					<a class = "questionlink" href = "/ordvac/<?php echo $value['id']; ?>"><?php echo $value['zagqu']; ?></a>
				</h4> 
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-6">
				<span class = "cost">
					<?php echo $value['cost'] ?> за проект 
				</span>
				<br/>
				<span class = "cost"> 
					<?php echo $value['viewed'] . htmlspecialchars(" • "); ?>
				</span>
				<span class = "cost">
					<?php echo $value['published']; ?>
				</span>
			</div>
			<div class = "col-md-6">
				<?php echo $value['metki']; ?>
			</div>
		</div>
		<hr/>
		<div class = "row tekst">
			<div class = "col-md-12">
				<?php echo $value['text']; ?>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-12">
				<small>Заказчик: <a class = "questionlink" href = "/user/<?php echo $value['login']; ?>"><?php echo $value['full_name']; ?></a></smal>
			</div>
		</div>
	</div>
<?php
} else if ($page == 'ordvac' && $module == 'order') {
	echo $_SESSION['id'];
?>
	<div class = "row">
		<div class = "col-md-12">
			<h4>Разместить заказ</h4>
			<hr/>
			<div class="border border-white padding-order">
				<?php 
					if(is_array($error) && count($error) > 0)
						foreach ($error as $key) {
							echo '<div class = "alert alert-danger">'.$key. '</div>';
						}
					else echo $error;
				?>
				<form action = "/ordvac/order" method="post">
					<p>
    					<h6>Название заказа *</h6>
    					<input type="text" name="zagqu" class = "form-control" placeholder="Кратко и конкретно" value = "<?php echo $zagqu; ?>">
    				</p>
    				<p>
    					<h6>Бюджет *</h6>
    					<input type="text" class = "form-control costwidth" name="cost" placeholder="Цена" value = "<?php echo $cost; ?>">
    					<select class="custom-select custom-select-sm valwidth" name = "valyuta">
							<option value="UZS">сум</option>
							<option value="RUB">рубль</option>
							<option value="USD">доллар</option>
						</select>
    				</p>	
    				<p>
    					<small>
    					Не указывайте контактные данные в описании заказа, для итого использовайте <a href = "/profile">профиль</a> 
    					</small>
						<fieldset>
							<textarea id="noise" name="noise" class="widgEditor nothing"><?php echo $text; ?></textarea>
						</fieldset>
					</p>
					<p>
						<h6>Сфера деятельности *</h6>
						<select class="custom-select custom-select-sm valwidth" name = "domain">
							<option value = "1">Разработка</option>
							<option value = "2">Тестирование</option>
							<option value = "3">Администрирование</option>
							<option value = "4">Разное</option>
						</select>
					</p>
					<input type="submit" name="send" class = "btn btn-success">
				</form>
			</div>
		</div>
	</div>
<?php 
}
?>
			</div>
		</div>
	</div>
</div>
<?php include 'foot.php'; ?>