import React from 'react';
import { connect } from 'react-redux';
import agent from '../agent';
import {
    getToken
} from '../actions/auth';

import BlankPage from './BlankPage';

/**
 * Wrapps our application, and ensures we get a JWT token
 * from our API before bootstrapping the app
 */
class BootstrapWrapper extends React.Component {
    componentDidMount() {
        getToken(this.parseGetParams());
    }

    parseGetParams() {
        let parts = window.location.search.substr(1).split("&");
        let $_GET = {};
        for (let i = 0; i < parts.length; i++) {
            let temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }
        return $_GET;
    }

    render() {
        if (this.props.authenticated) {
            return this.props.children;
        }
        return (<BlankPage />)
    }
}

// BootstrapWrapper.contextTypes = {
//   router: PropTypes.object.isRequired
// };

const mapDispatchToProps = dispatch => ({
    getToken: (args) =>
        dispatch({ type: GET_TOKEN, payload: agent.Auth.token(args) })
});

const mapPropsToState = state => {
    return {
        authenticated: state.auth.authenticated
    }
};

export default connect(mapPropsToState, mapDispatchToProps)(BootstrapWrapper);
