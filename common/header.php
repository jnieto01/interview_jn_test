<?php 
    include("controller/common/header.php");
?>
<!DOCTYPE html>
<html dir="ltr" lang="<?php echo $user_language; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $text_title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script src="javascript/jquery/jquery-3.2.1.min.js"></script>
<script src="javascript/common.js" type="text/javascript"></script>
<script src="javascript/jquery/jquery-ui.js"></script>
<link href="javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="javascript/bootstrap/js/bootstrap.min.js"></script>
<link type="text/css" href="stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
</head>
<body>
<header>
  <div class="container">
    <div class="row">   
      <div class="col-sm-6">
        <h1 id="kind"> <?php  echo $text_kind; ?> </h1>
      </div>
      <div class="col-sm-6">
        <h1 id="name"> <?php  echo 'Jesus Nieto'; ?>  </h1>
      </div>
    </div>
  </div>
</header>