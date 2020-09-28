import React from 'react';
import ReactDOM from 'react-dom';
import Publications from './components/Publications';

ReactDOM.render(
  <React.StrictMode>
    <Publications isLoggedIn={true} />
  </React.StrictMode>,
  document.getElementById('root')
);
