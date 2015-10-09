<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$app = new Application();

$app['debug'] = true;

$notes = [
  '1' => [
    'name' => 'Sprinkler',
    'body' => 'Replace Sprinkler Body',
    'tags' => 'Outside, Yard Work, Summer'
  ],
  '2' => [
    'name' => 'Brakes',
    'body' => 'Fix the brakes on truck',
    'tags' => 'Outside, Vehicle'
  ]
];

$users = [
  '1' => [
    'username' => 'foo',
    'password' => 'private',
    'firstname' => 'Brent',
    'lastname' => 'Barker'
  ],
  '2' => [
    'username' => 'bar',
    'password' => 'private',
    'firstname' => 'B',
    'lastname' => 'Barke'
  ],
];

$app->get('/', function () {
  return new Response('<h1>Proj 1 Deliverable 1</h1>', 200);
});

/* create */
$app->post('/notes', function (Application $app, Request $request) use ($notes) {

  $contentTypeValid = in_array(
    'application/json',
    $request->getAcceptableContentTypes()
  );

  if (!$contentTypeValid) {
    $app->abort(406, 'Client must accept content type of "application/json"');
  }

  $content = json_decode($request->getContent());
  $newId = uniqid();

  $notes[$newId] = [
    'name' => $content->name,
    'body' => $content->body,
    'tags' => $content->tags
  ];

  return new Response(
    json_encode(['result' => 'success', 'status' => 'created']),
    201,
    [
      'Content-Type' => 'application/json',
      'Location' => 'http://localhost:8080/notes/' . $newId
    ]
  );
});

/* read */
$app->get('/notes/{id}', function (Application $app, Request $request, $id) use ($notes) {


  $singleNote = $notes[$id];

  if (!isset($singleNote)) {
    $app->abort(404, 'Note with ID $id does not exist.');
  }

  return new Response(
    json_encode($notes[$id]),
    201,
    ['content-type' => 'application/json']
  );
});

/* update */
$app->put('/notes/{id}', function (Application $app, Request $request, $id) use ($notes) {

  $contentTypeValid = in_array(
    'application/json',
    $request->getAcceptableContentTypes()
  );

  if (!$contentTypeValid) {
    $app->abort(406, 'Client must accept content type of "application/json"');
  }

  $content = json_decode($request->getContent(), true);

  if(!isset($notes[$id])) {
    $app->abort(404, 'Note with ID $id does not exist.');
  }

  $notes[$id] = [
    'name' => $content->name,
    'body' => $content->body,
    'tags' => $content->tags
  ];

  return new Response(
    json_encode(['result' => 'success', 'status' => 'updated']),
    200,
    [
      'Content-Type' => 'application/json',
      'Location' => 'http://localhost:8080/notes/' . $id
    ]
  );
});

/* delete */
$app->delete('/notes/{id}', function (Application $app, $id) use ($notes) {
  if (!isset($notes[$id])) {
    $app->abort(404, 'Note with ID $id does not exist.');
  }

  unset($notes{$id});

  return new Response(null, 204);
});

/* list */
$app->get('/notes', function (Application $app) use ($notes) {

  return new Response(
    json_encode($notes),
    201,
    ['content-type' => 'application/json']
  );
});

/**users**/

/* create */
$app->post('/user', function (Application $app, Request $request) use ($users) {

  $contentTypeValid = in_array(
    'application/json',
    $request->getAcceptableContentTypes()
  );

  if (!$contentTypeValid) {
    $app->abort(406, 'Client must accept content type of "application/json"');
  }

  $content = json_decode($request->getContent());
  $newId = uniqid();

  $users[$newId] = [
    'username' => $content->username,
    'password' => $content->password,
    'firstname' => $content->firstname,
    'lastname' => $content->lastname
  ];

  return new Response(
    json_encode(['result' => 'success', 'status' => 'created']),
    201,
    [
      'Content-Type' => 'application/json',
      'Location' => 'http://localhost:8080/user/' . $newId
    ]
  );
});

/*update*/
$app->put('/user/{id}', function (Application $app, Request $request, $id) use ($users) {

  $contentTypeValid = in_array(
    'application/json',
    $request->getAcceptableContentTypes()
  );

  if (!$contentTypeValid) {
    $app->abort(406, 'Client must accept content type of "application/json"');
  }

  $content = json_decode($request->getContent());

  if(!isset($users[$id])) {
    $app->abort(404, 'User with ID $id does not exist.');
  }

  $users[$id] = [
    'username' => $content->username,
    'password' => $content->password,
    'firstname' => $content->firstname,
    'lastname' => $content->lastname
  ];

  return new Response(
    json_encode(['result' => 'success', 'status' => 'updated']),
    200,
    ['Content-Type' => 'application/json']
  );
});


/*delete*/
$app->delete('/user/{id}', function (Application $app, $id) use ($users) {
  if (!isset($users[$id])) {
    $app->abort(404, 'User with ID $id does not exist.');
  }

  unset($users{$id});

  return new Response(null, 204);
});

/*read*/
$app->get('/user/{id}', function (Application $app, $id) use ($users) {
  if (!isset($users[$id])) {
    $app->abort(404, 'User with ID $id does not exist.');
  }

  return new Response(
    json_encode($users[$id]),
    200,
    ['Content-Type' => 'application/json']
  );

});

$app->run();



