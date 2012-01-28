<?php header('HTTP/1.1 500 Internal Server Error'); // Send 500 HTTP header ?>
<!DOCTYPE html> 
<html dir="ltr" lang="en-US"> 
<head> 
<meta charset="UTF-8"> 
<title><?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php echo FEATHER_URL.'assets/css/feather-tmpl.css'; ?>">
</head>
<body class="error">
<div>
	<p id="version"><?php echo self::$vars['VERSION']; ?></p>
	<h1>Internal Server Error</h1>
	<p class="msg"><?php echo self::$vars['ERROR']; ?></p>
	<table cellspacing="0">
		<tr>
			<th class="num">&mdash;</th>
			<th class="file">File</th>
			<th class="line">Line</th>
			<th class="class">Class</th>
			<th class="func">Function</th>
		</tr>
<?php $trace=debug_backtrace(); // Generate backtrace ?>
<?php $count=0; // Backtrace count ?>
<?php foreach($trace as $item): ?>
		<tr>
			<td class="num"><?php echo $count; ?></td>
			<td class="file"><?php echo isset($item['file'])?basename($item['file']):''; ?></td>
			<td class="line"><?php echo isset($item['line'])?basename($item['line']):''; ?></td>
			<td class="class"><?php echo isset($item['class'])?basename($item['class']):''; ?></td>
			<td class="func"><?php echo isset($item['function'])?basename($item['function']):''; ?></td>
		</tr>
<?php $count++; if($count>10) { break; } ?>
<?php endforeach; ?>
	</table>
</div>
</body>
</html>
