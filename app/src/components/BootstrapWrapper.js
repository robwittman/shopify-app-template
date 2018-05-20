import React from 'react';
import { connect } from 'react-redux';
import agent from '../agent';
import {
    GET_TOKEN,
    UNAUTHORIZED,
    MISSING_SCOPES,
    CHARGE_REQUIRED,
    INSTALL
} from '../constants/action-types';
import BlankPage from './BlankPage';

class BootstrapWrapper extends React.Component {
    componentWillReceiveProps(nextProps) {
        switch (nextProps.type) {
            case UNAUTHORIZED:
            case MISSING_SCOPES:
                console.log(nextProps.type);
                // redirect to shopify for auth...
                break;
            case CHARGE_REQUIRED:
                console.log(nextProps.type);
                // Redirect to our charge url...
                break;
        }
    }
    componentDidMount() {
        let params = this.parseGetParams();
        console.log('params', params);
        if (params.code) {
            // We are returning from a redirect, finish auth and store
            console.log('Completing auth');
            this.props.install(params);
        } else {
            console.log('dispatching getToken');
            console.log(params);
            this.props.getToken(params);
        }
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
        dispatch({ type: GET_TOKEN, payload: agent.Auth.token(args) }),
    install: (args) =>
        dispatch({ type: INSTALL, payload: agent.Auth.install(args) })
});

const mapPropsToState = state => {
    return {
        authenticated: false
    }
};

export default connect(mapPropsToState, mapDispatchToProps)(BootstrapWrapper);
