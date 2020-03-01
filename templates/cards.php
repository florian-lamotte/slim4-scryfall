<!DOCTYPE html>
<html>
<head>
	<title>Cards</title>
	<link href="http://localhost:8080/slim4-scryfall/public/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="container">
		<a href="<?= $url_sets ?>" class="retour"><<< sets</a>
		
		<?php foreach ($cards as $card): ?>
			<a href="<?= $url_card . '/' . $card->id ?>">
				<img src="<?= $card->image_uris->small ?>">
			</a>

			<!--<div>
				<a href="<?= $set . '/' . $card->id ?>">
					<?= isset($card->printed_name) ? $card->printed_name : $card->name ?>
				</a>
			</div>-->
		<?php endforeach; ?>

		<div>
		<?php for($i = 1; $i <= $pages; $i++){ ?>
			<a href="<?= $url_cards ?>/page=<?= $i ?>"><?= $i ?></a>
		<?php } ?>
		</div>
	</div>
</body>
</html>