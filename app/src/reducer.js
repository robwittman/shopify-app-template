import auth from './reducers/auth';
import { combineReducers } from 'redux';
import common from './reducers/common';
import shop from './reducers/shop';
import { routerReducer } from 'react-router-redux';

export default combineReducers({
    auth,
    common,
    shop,
    router: routerReducer,
});
