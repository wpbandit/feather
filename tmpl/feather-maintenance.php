<?php header('HTTP/1.1 503 Service Temporarily Unavailable'); // Send 503 HTTP header ?>
<!DOCTYPE html> 
<html dir="ltr" lang="en-US"> 
<head> 
<meta charset="UTF-8"> 
<title><?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php echo FEATHER_URL.'assets/css/feather-tmpl.css'; ?>">
</head>
<body class="maintenance">
<div class="box">
	<h1>Site Maintenance</h1>
	<p class="note">Site is currently under maintenance. Please check back later.</p>
</div>
</body>
</html>
