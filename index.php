<?php
$pass = "";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Todo</title>
	<meta charset="utf-8">	
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>
<body>

<?php
if (!empty($_GET['action']) &&  $_GET['action'] == "logout") {session_destroy();unset ($_SESSION['pass']);}

$path_name = pathinfo($_SERVER['PHP_SELF']);
$this_script = $path_name['basename'];
if (empty($_SESSION['pass'])) {$_SESSION['pass']='';}
if (empty($_POST['pass'])) {$_POST['pass']='';}
if ( $_SESSION['pass']!== $pass) 
{
    if ($_POST['pass'] == $pass) {$_SESSION['pass'] = $pass; }
    else 
    {
        echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post"><input name="pass" type="password"><input type="submit" value=""></form>';
        exit;
    }
}
?>


<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="./">
				Todo
			</a>			
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);?>?action=logout">Logout</a></li>			
			</ul>
		</div>
	</div>
</nav>


<div class="container">	
<div class="panel panel-default">
<div class="panel-heading">List</div>	
<div class="panel-body">
<?php
//var_dump($_POST);
if (isset($_POST['submit'])) {
	$json_str=file_get_contents("list.json");
	$array_json=json_decode($json_str,1);
	$time_stamp=time();	

	if (isset($_POST['id'])) {
		$id=$_POST['id'];
		unset($array_json[$id]);
	}elseif (isset($_POST['new_entry'])   ) {
		$new_entry=$_POST['new_entry'];
		$array_json[$time_stamp]=$new_entry;
	}
	$json_str2 = json_encode($array_json);
	file_put_contents('list.json', $json_str2);		
}

echo '<form method="post" action="index.php" id="add_form">';
echo '<input type="text" name="new_entry" class="form-control" required></input>';
echo '<button type="submit" form="add_form" name="submit" class="btn btn-success pull-right"> add</button>';
echo '</form>';

$json_str=file_get_contents("list.json");
$array_json=json_decode($json_str,1);
echo '<table class="table table-bordered">';
$i=0;
foreach($array_json as $id => $value){
	echo '<tr>';
		echo '<td>'.$id.'</td>';
		echo '<td>'.$value.'</td>';
		echo '<td>';
		echo '<form method="post" action="index.php" id="del_form_'.$i.'">';
		echo '<input name="id" value="'.$id.'" style="display:none;">';
		echo '<button type="submit" form="del_form_'.$i.'" name="submit" class="btn btn-danger">x</button>';
		echo '</form>';
		echo '</td>';
	echo '</tr>';
	$i++;
}
echo '</table>';   

?>

</div></div></div>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</body>
</html>
