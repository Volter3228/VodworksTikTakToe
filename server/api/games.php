<?php
include_once './models/Game.php';

function getSingleGame($id)
{
    $game = new Game();

    $game->id = $id;

    if ($game->getSingle()) {
        $result = array(
            'id' => $game->id,
            'board' => $game->board,
            'status' => $game->status
        );
        echo json_encode($result, true);
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Game Not Found'));
    }
}

function getAllGames()
{
    $game = new Game();
    $result = $game->getAll();
    if (!empty($result)) {
        // Game array
        $games_arr = array();
        $games_arr['data'] = array();
        foreach ($result as $game) {
            $games_arr['data'][] = $game;
        }
        echo json_encode($games_arr['data']);
    } else {
        // No Games
        http_response_code(404);
        echo json_encode(array('message' => 'Games Not Found'));
    }
}

function createGame()
{
    $game = new Game();

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"), true);
    $game->board = $data['board'];

    // Create game
    if ($game->create()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Game Created', 'location' => '/game/' . $game->id));
    } else {
        http_response_code(400);
        echo json_encode(array('reason' => 'The gaming board should contain only 9 cells'));
    }
}

function updateGame($id)
{
    $game = new Game();

    // Get body data
    $data = json_decode(file_get_contents("php://input"), true);

    $game->id = $id;
    $game->board = $data['board'];

    // Update game
    if ($game->update()) {
        echo json_encode(array('message' => 'Game Updated', 'board' => $game->board, 'status' => $game->status));
    } else {
        http_response_code(400);
        echo json_encode(array('message' => 'Game Not Updated', 'reason' => 'Trying to change more than 1 cell or fill cell which is already filled'));
    }
}

function deleteGame($id)
{
    $game = new Game();

    // Get body data
    $data = json_decode(file_get_contents("php://input"), true);

    $game->id = $id;

    // Delete game
    if ($game->delete()) {
        echo json_encode(array('message' => 'Game Deleted'));
    } else {
        echo json_encode(array('message' => 'Game Not Deleted'));
    }
}