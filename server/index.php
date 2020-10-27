<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];
$params = explode('/', $_GET['q']);
require './api/games.php';

$type = $params[0];
if (isset($params[1])) {
    $id = $params[1];
}

if ($method === 'GET') {
    if ($type === 'games') {
        if (isset($id)) {
            getSingleGame($id);
        } else {
            getAllGames();
        }
    }
} else if ($method === 'POST') {
    if ($type === 'games') {
        createGame();
    }
} else if ($method === 'PUT') {
    if ($type === 'games' && isset($id)) {
        updateGame($id);
    }
} else if ($method === 'DELETE') {
    if ($type === 'games' && isset($id)) {
        deleteGame($id);
    }
}