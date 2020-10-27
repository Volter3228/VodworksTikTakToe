import React from 'react';
import './styles.css';

import { create } from '../../api/games';

const TicTacToe = (props) => {
  const handleStart = async () => {
    let res = null;
    res = await create('-'.repeat(9));
    if (res) {
      props.history.push(res.data.location);
    }
  };

  return (
    <div className="tic-tac-toe">
      <h1>Welcome to Tic-Tac-Toe, Vodworks Warriors! Let's play ;^ь</h1>
      <button className="start" onClick={() => handleStart()}>
        Start the game
      </button>
    </div>
  );
};

export default TicTacToe;
