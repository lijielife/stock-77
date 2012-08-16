<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>


<?php
    $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
$regex .= "(\:[0-9]{2,5})?"; // Port
$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
?>

Then, the correct way to check against the regex is as follows:

<?php
   if(preg_match_all("/$regex/i", 'example.com gyg edf', $m))
      print_r($m);

   /*if(preg_match("/^$regex$/", 'http://www.example.com/etcetc', $m))
      var_dump($m);*/
	  
	  
	$maxi = preg_replace("/$regex/", '[***]', 'example.com   maxi edu.com www.as.com');
	echo $maxi;
	  
?>

</body>
</html>