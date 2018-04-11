<?php
include('core/init.php');

//ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PHP : Private Message System</title>
<meta name="description" content="Google検索に表示される説明120文字程度" />
<meta name="keywords" content="キーワード５個程度" />

<!--normalize.min.css-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css">
<!-- CSS -->
<link rel="stylesheet" href="css/style.css">
<!--Awesome font-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>

<body>
	
	<div id="container">
		
		<?php
		
		//load page
		include($include_file);
		
		?>
	</div><!-- div id="container" -->
	
	

<!----------------------------
	jQuery
---------------------------------------->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- jquery-migrate --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.1/jquery-migrate.min.js"></script>

</body>
</html>
