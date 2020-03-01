<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

$container->set('view', new PhpRenderer('../templates'));

$container->set('sets', function () {
    $adresse = 'https://api.scryfall.com/sets';

    $api = file_get_contents($adresse);
    $json = json_decode($api);

    return $json;
});

require('dump.php');

$app->get('/', function (Request $request, Response $response) {
    $sets = $this->get('sets')->data;
    $url_cards = 'cards';

    $response = $this->get('view')->render($response, "sets.php", [
        'sets' => $sets, 
        'cards' => $url_cards
    ]);

    return $response;
});

$app->get('/sets', function (Request $request, Response $response) {
    $sets = $this->get('sets')->data;
    $url_cards = 'cards';

    $response = $this->get('view')->render($response, "sets.php", [
        'sets' => $sets, 
        'cards' => $url_cards
    ]);

    return $response;
});

$app->get('/cards/{set}', function (Request $request, Response $response, $args) {
    $adresse = 'https://api.scryfall.com/cards/search?order=color&q=set%3A' . $args['set'];
    $api = file_get_contents($adresse);
    $json = json_decode($api);

    $url = '/slim4-scryfall';
    $sets = '/sets';
    $cards = '/cards/'. $args['set'];
    $card = '/card/'. $args['set'];

    $pages_totales = ceil($json->total_cards / 175);

    $next_page = $json->next_page;

    /* ajouter une recherche par couleurs */

    $response = $this->get('view')->render($response, "cards.php", [
        'cards' => $json->data,
        'set' => $args['set'],
        'url_sets' => $url . $sets,
        'url_cards' => $url . $cards,
        'url_card' => $url . $card,
        'pages' => $pages_totales
    ]);

    return $response;
});

$app->get('/cards/{set}/page={page}', function (Request $request, Response $response, $args) {
    $adresse = 'https://api.scryfall.com/cards/search?order=color&page=' . $args['page'] . '&q=set%3A' . $args['set'];
    $api = file_get_contents($adresse);
    $json = json_decode($api);

    $url = '/slim4-scryfall';
    $sets = '/sets';
    $cards = '/cards/'. $args['set'];
    $card = '/card/'. $args['set'];

    $pages_totales = ceil($json->total_cards / 175);

    $response = $this->get('view')->render($response, "cards.php", [
        'cards' => $json->data,
        'set' => $args['set'],
        'url_sets' => $url . $sets,
        'url_cards' => $url . $cards,
        'url_card' => $url . $card,
        'pages' => $pages_totales
    ]);

    return $response;
});

$app->get('/card/{set}/{id}', function (Request $request, Response $response, $args) {
    $adresse = 'https://api.scryfall.com/cards/' . $args['id'];
    $api = file_get_contents($adresse);
    $json = json_decode($api);

    $images = preg_replace('#[0-9A-Z]#', '<img class="mana_cost" src="https://img.scryfall.com/symbology/$0.svg">', $json->mana_cost);
    $mana_cost = preg_replace('#[{}]#', '', $images);

    $url = '/slim4-scryfall';
    $cards = '/cards/'. $args['set'];

    $response = $this->get('view')->render($response, "card.php", [
        'card' => $json,
        'mana_cost' => $mana_cost,
        'retour' => $url . $cards
    ]);

    return $response;
});

$app->run();
