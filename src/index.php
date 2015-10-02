<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$app = new Application();

$app['debug'] = true;

$inventory = [
	'322323' => [
		'name' => 'Bubbleicous',
		'desc' => 'A yummy bubble gum',
		'rank' => 'high'
	],
	'83573897539' => [
		'name' => 'Blow Pop',
		'desc' => 'Tasty bubble gum inside a lolipop',
		'rank' => 'super high'
	]
];


$notes = [
    '322323' => [
        'name' => 'Bubbleicous',
        'desc' => 'A yummy bubble gum',
        'rank' => 'high'
    ],
    '83573897539' => [
        'name' => 'Blow Pop',
        'desc' => 'Tasty bubble gum inside a lolipop',
        'rank' => 'super high'
    ]
];

$app->get('/', function() {
	return new Response('<h1>ReST API Candy Demo</h1>', 200);
});

$app->get('/barrell', function() use ($inventory) {
	$jsons = json_encode($inventory);
	$response = new Response($jsons, 200);
	$response->headers->set('Content-type', 'application/json');
	$response->headers->set('Content-length', strlen($jsons));

	return $response;
});


$app->delete('/notes/{id}', function(Application $app, $id) use ($notes) {
	if(!isset($notes[$id])) {
		$app->abort(404, 'Note with ID {$id} does not exist.');
	}

	unset($notes{$id});

	return new Response(null, 204);
});


$app->put('/notes/{id}', function(Application $app, Request $request, $id) use ($notes) {

	$contentTypeValid = in_array(
		'application/json',
		$request->getAcceptableContentTypes()
	);

	if(!$contentTypeValid) {
		$app->abort(406, 'Client must accept content type of "application/json"');
	}

    $content = json_decode($request->getContent(), true);
	$newId = uniqid();

	$notes[$newId] = [
		'name' => $content->name,
		'body' => $content->body,
		'tags' => $content->tags
	];

	return new Response(
		json_encode($notes),
		201,
		['Location' => 'http://localhost:8888/notes/' . $newId]
	);
});

$app->post('/notes', function(Application $app, Request $request, $id) use ($notes) {

    $contentTypeValid = in_array(
		'application/json',
		$request->getAcceptableContentTypes()
	);

	if(!$contentTypeValid) {
		$app->abort(406, 'Client must accept content type of "application/json"');
	}

    $content = json_decode($request->getContent(), true);
	$newId = uniqid();

	$notes[$newId] = [
		'name' => $content->name,
		'body' => $content->body,
		'tags' => $content->tags
	];

	return new Response(
		json_encode($notes),
		201,
		['Location' => 'http://localhost:8888/notes/' . $newId]
	);
});

$app->get('/notes', function(Application $app, $id) use ($notes) {

	return new Response(
		json_encode($notes),
		201,
		['content-type' => 'application/json']
	);
});

/*
$app->update('/notes/{id}', function(Application $app, $id) use ($notes) {

});
*/

//Not implemented
$app->put('/notes/{id}', function(Application $app, $id) use ($notes) {
	return new Response(null, 501);
});

$app->run();



/*

docker - forawards to different containers.


*/
