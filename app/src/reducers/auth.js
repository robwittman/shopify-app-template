import {
    GET_TOKEN,
    TOKEN_RECEIVED
} from '../constants/action-types';

export default (state = {
    authenticated: false,
    token: null
}, action) => {
    switch (action.type) {
        case GET_TOKEN:
            return Object.assign({}, state, {
                authenticated: false
            });
        case TOKEN_RECEIVED:
            return Object.assign({}, state, {
                authenticated: true
            });
        default:
            return state;
    }
};
