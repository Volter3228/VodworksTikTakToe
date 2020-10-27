import React from 'react';
import './styles.css';

const Cell = (props) => {
  return (
    <button className="cell" onClick={() => props.onClick()}>
      {props.value !== '-' ? props.value : null}
    </button>
  );
};

export default Cell;
