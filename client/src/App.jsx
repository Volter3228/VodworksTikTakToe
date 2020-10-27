import React from 'react';
import TicTacToe from './components/TicTacToe';
import GameBoard from './components/TicTacToe/GameBoard';
import { BrowserRouter as Router, Route } from 'react-router-dom';
import './App.css';

const App = () => {
  return (
    <div className="App">
      <Router>
        <Route path="/game/:id" component={GameBoard} />
        <Route exact path="/" component={TicTacToe} />
      </Router>
    </div>
  );
};

export default App;
