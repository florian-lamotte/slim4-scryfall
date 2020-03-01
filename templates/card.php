<!DOCTYPE html>
<html>
<head>
	<title>Card</title>
	<link href="http://localhost:8080/slim4-scryfall/public/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="container">
		<a href="<?= $retour ?>" class="retour"><<< <?= isset($card->set_name) ? $card->set_name : '' ?></a>

		<img src="<?= $card->image_uris->normal ?>">

		<div class="informations">
			<h1><?= isset($card->printed_name) ? $card->printed_name : $card->name ?> <?= $mana_cost ?></h1>

			<div class="set_name"><?= isset($card->set_name) ? $card->set_name : '' ?></div>

			<div class="price">
				<a href="<?= isset($card->purchase_uris->cardmarket) ? $card->purchase_uris->cardmarket : '' ?>" target="_blank"><?= isset($card->prices->eur) ? $card->prices->eur . ' EUR' : $card->prices->usd . ' USD' ?></a>
			</div>

			<ul>
			<?php foreach ($card->legalities as $cle => $valeur): 
				$legalitie = $valeur == 'legal' ? 'legal': 'not_legal'; ?>

				<li><?= $cle ?>: <span class="<?= $legalitie ?>"><?= $valeur ?></span></li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
</body>
</html>