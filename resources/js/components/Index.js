import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Hotel from './Hotel';


export default class Index extends Component {
    render() {
        return (
            <div className="container">
                <Hotel hotelId={}/>
            </div>
        );
    }
}

if (document.getElementById('root')) {
    ReactDOM.render(<Index />, document.getElementById('root'));
}
