import axios from 'axios';

const BASE_URL = 'http://localhost/tictactoe';

export const getAll = async () => {
  let res = null;
  res = await axios
    .get(`${BASE_URL}/games`)
    .catch((err) => console.log(err));

  return res;
};

export const getSingle = async (id) => {
  let res = null;
  res = await axios
    .get(`${BASE_URL}/games/${id}`)
    .catch((err) => console.log(err));

  return res;
};

export const create = async (board) => {
  let res = null;
  res = await axios
    .post(`${BASE_URL}/games`, { board })
    .catch((err) => console.log(err));

  return res;
};

export const update = async (id, board) => {
  let res = null;
  res = await axios
    .put(`${BASE_URL}/games/${id}`, { board })
    .catch((err) => console.log(err));

  return res;
};

export const remove = async (id) => {
  let res = null;
  res = await axios
    .delete(`${BASE_URL}/games/${id}`)
    .catch((err) => console.log(err));

  return res;
};
