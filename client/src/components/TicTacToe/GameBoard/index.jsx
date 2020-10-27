import React, { useState, useEffect } from 'react';
import Cell from '../Cell';
import { update, getSingle, create } from '../../../api/games';
import './styles.css';

const GameBoard = (props) => {
  let [cells, setCells] = useState(Array(9).fill('-'));
  let [status, setStatus] = useState('');

  useEffect(() => {
    const fetchData = async () => {
      if (props.match.params.id) {
        let res = null;
        res = await getSingle(props.match.params.id);
        if (res) {
          setCells(res.data.board.split(''));
          setStatus(res.data.status);
        } else {
          setStatus('INVALID_GAME_LOCATION');
        }
      }
    };
    fetchData();
  }, [props.match.params.id]);

  const handleClick = async (i) => {
    if (status !== 'RUNNING' || cells[i] !== '-') {
      return;
    }
    const cellsCopy = cells.slice();
    cellsCopy[i] = 'X';
    const res = await update(props.match.params.id, cellsCopy.join(''));
    if (res) {
      setCells(res.data.board.split(''));
      setStatus(res.data.status);
    }
  };

  const handleStart = async () => {
    let res = null;
    res = await create('-'.repeat(9));
    if (res) {
      props.history.push(res.data.location);
    }
  };

  const renderCell = (i) => {
    return <Cell value={cells[i]} onClick={() => handleClick(i)} />;
  };

  return (
    <div className="game-board">
      <h2 className="status">Status: {status}</h2>
      <div className="row">
        {renderCell(0)}
        {renderCell(1)}
        {renderCell(2)}
      </div>
      <div className="row">
        {renderCell(3)}
        {renderCell(4)}
        {renderCell(5)}
      </div>
      <div className="row">
        {renderCell(6)}
        {renderCell(7)}
        {renderCell(8)}
      </div>
      {status !== 'RUNNING' ? (
        <button className="start_new" onClick={handleStart}>
          Start new
        </button>
      ) : null}
    </div>
  );
};

export default GameBoard;
