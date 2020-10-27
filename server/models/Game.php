<?php

class Game
{
    // Game properties
    public string $id;
    public string $board;
    public string $status;

    // Get Games
    public function getAll()
    {
        $data = file_get_contents(__DIR__ . '/../storage/games.txt');
        return json_decode($data, true);
    }

    // Get Single Game
    public function getSingle(): bool
    {
        $data_arr = $this->getAll();
        $key = array_search($this->id, array_column($data_arr, 'id'), true);
        if ($key !== false) {
            $this->board = $data_arr[$key]['board'];
            $this->status = $data_arr[$key]['status'];
            return true;
        }
        return false;
    }

    // Create new Game
    public function create(): bool
    {
        if (strlen($this->board) === 9) {
            $this->id = uniqid('', true);
            $this->status = $this->checkStatus($this->board);
            $data_arr = $this->getAll();
            $data_arr[] = $this;
            file_put_contents(__DIR__ . '/../storage/games.txt', json_encode($data_arr));
            return true;
        }
        return false;
    }

    // Update Game
    public function update(): bool
    {
        if (strlen($this->board) === 9) {
            $data_arr = $this->getAll();
            $key = array_search($this->id, array_column($data_arr, 'id'), true);
            if ($key !== false) {
                if (($data_arr[$key]['status'] !== 'RUNNING') || !($this->isUpdateBoard($data_arr[$key]['board']))) {
                    return false;
                }
                $this->status = $this->checkStatus($this->board);
                if ($this->status !== "X_WON" && $this->status !== 'O_WON') {
                    $this->makeTurn();
                    $this->status = $this->checkStatus($this->board);
                }
                $data_arr[$key]['board'] = $this->board;
                $data_arr[$key]['status'] = $this->status;
                file_put_contents(__DIR__ . '/../storage/games.txt', json_encode($data_arr));
                return true;
            }
        }
        return false;
    }

    // Delete Game
    public function delete(): bool
    {
        $data_arr = $this->getAll();
        $key = array_search($this->id, array_column($data_arr, 'id'), true);
        if ($key !== false) {
            unset($data_arr[$key]);
            $data_arr = array_values($data_arr);
            file_put_contents(__DIR__ . '/../storage/games.txt', json_encode($data_arr));
            return true;
        }
        return false;
    }

    private function isUpdateBoard($board): bool
    {
        if (($board === $this->board) || (abs(strcmp($board, $this->board)) > 1)) {
            return false;
        }
        $board = str_split($board);
        $boardToCheck = str_split($this->board);
        $length = count($board);
        $diffCount = 0;
        for ($i = 0; $i < $length; $i++) {
            if ($board[$i] !== $boardToCheck[$i]) {
                if ($boardToCheck[$i] === '-') {
                    return false;
                }
                $diffCount++;
                if ($diffCount > 1) {
                    return false;
                }
            }
        }
        return true;
    }

    private function makeTurn(): void
    {
        $board = array_chunk(str_split($this->board), 3);
        $bestScore = -100;
        $turn = array();
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                // Is the cell available
                if ($board[$i][$j] === '-') {
                    $board[$i][$j] = 'O';
                    $score = $this->minimax($board, 0, false);
                    $board[$i][$j] = '-';
                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $turn = array('i' => $i, 'j' => $j);
                    }
                }
            }
        }
        $index = 3 * $turn['i'] + $turn['j'];
        $this->board = substr_replace($this->board, 'O', $index, 1);
    }

    private function minimax($board, $depth, $isMaximizing)
    {
        $boardCopy = $board;
        $status = $this->checkStatus(array_reduce($boardCopy, 'array_merge', array()));
        if (in_array($status, array('X_WON', 'O_WON', 'DRAW'), true)) {
            // calculating score
            switch ($status) {
                case 'X_WON':
                    return $depth - 100;
                case 'O_WON':
                    return 100 - $depth;
                case 'DRAW':
                    return 0;
                default:
                    break;
            }
        }
        if ($isMaximizing) {
            $bestScore = -100;
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    // Is the cell available
                    if ($board[$i][$j] === '-') {
                        $board[$i][$j] = 'O';
                        $score = $this->minimax($board, $depth + 1, false);
                        $board[$i][$j] = '-';
                        $bestScore = max($score, $bestScore);
                    }
                }
            }
            return $bestScore;
        }
        $bestScore = 100;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                // Is the cell available
                if ($board[$i][$j] === '-') {
                    $board[$i][$j] = 'X';
                    $score = $this->minimax($board, $depth + 1, true);
                    $board[$i][$j] = '-';
                    $bestScore = min($score, $bestScore);
                }
            }
        }
        return $bestScore;
    }

    // Update Game Status
    private function checkStatus($board): string
    {
        $lines = array(
            array(0, 1, 2),
            array(3, 4, 5),
            array(6, 7, 8),
            array(0, 3, 6),
            array(1, 4, 7),
            array(2, 5, 8),
            array(0, 4, 8),
            array(2, 4, 6),
        );

        foreach ($lines as $line) {
            [$a, $b, $c] = $line;
            if ($board[$a] && $board[$a] !== '-' && $board[$a] === $board[$b] && $board[$a] === $board[$c]) {
                switch ($board[$a]) {
                    case 'X':
                        return 'X_WON';
                    case 'O':
                        return 'O_WON';
                    default:
                        break;
                }
            }
        }

        if (substr_count($this->board, '-') === 0) {
            return 'DRAW';
        }
        return 'RUNNING';
    }
}