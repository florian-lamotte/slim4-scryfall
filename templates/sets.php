<!DOCTYPE html>
<html>
<head>
	<title>Sets</title>
	<link href="http://localhost:8080/slim4-scryfall/public/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="container">
		<ul>
		<?php foreach ($sets as $set): 
			if($set->set_type == "expansion"){ ?>
				<li>
					<img src="<?= $set->icon_svg_uri ?>">
					<a href="<?= $cards . '/' . $set->code ?>"><?= $set->name ?></a>
				</li>
		<?php } 
		endforeach; ?>
		</ul>
	</div>
</body>
</html>