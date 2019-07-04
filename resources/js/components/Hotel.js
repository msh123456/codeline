import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import baseUrl from './Config';

class Hotel extends Component {
    render(){
        return "";
    }
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            items: []
        };
    }
    componentDidMount() {
        console.log(window.get.url)
        fetch(baseUrl+`v1/hotel/${this.props.hotelId}`)
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        items: result.items
                    });
                },
                // Note: it's important to handle errors here
                // instead of a catch() block so that we don't swallow
                // exceptions from actual bugs in components.
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }

}

export default Hotel;
