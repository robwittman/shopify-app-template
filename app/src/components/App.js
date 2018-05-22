import agent from '../agent';
import React from 'react';
import { connect } from 'react-redux';
import { APP_LOAD, REDIRECT } from '../constants/action-types';
import { Route, Switch } from 'react-router-dom';
import { store } from '../store';
import { push } from 'react-router-redux';
import ShopPage from './ShopPage';

const mapStateToProps = state => {
    return {
        appLoaded: state.common.appLoaded,
        appName: state.common.appName,
        currentUser: state.common.currentUser,
        redirectTo: state.common.redirectTo
    }};

const mapDispatchToProps = dispatch => ({
    onLoad: (payload, token) =>
        dispatch({ type: APP_LOAD, payload, token, skipTracking: true }),
    onRedirect: () =>
        dispatch({ type: REDIRECT })
});

class App extends React.Component {
    componentWillMount() {
        console.log('App Loaded');
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.redirectTo) {
            // this.context.router.replace(nextProps.redirectTo);
            store.dispatch(push(nextProps.redirectTo));
            this.props.onRedirect();
        }
    }

    render() {
        let currentUser = this.props.currentUser;
        return (
            <div>
                <div className={'container-fluid'}>
                    <Switch>
                        <Route path={"/"} component={ShopPage} />
                    </Switch>
                </div>
            </div>
        );
    }
}

// App.contextTypes = {
//   router: PropTypes.object.isRequired
// };

export default connect(mapStateToProps, mapDispatchToProps)(App);
