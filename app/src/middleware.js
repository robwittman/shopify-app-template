import agent from './agent';
import {
    ASYNC_START,
    ASYNC_END,
    GET_TOKEN
} from './constants/action-types';

const localStorageMiddleware = store => next => action => {
    if (action.type === GET_TOKEN) {
        if (!action.error) {
            window.localStorage.setItem('jwt', action.payload.token);
            agent.setToken(action.payload.token);
        }
    }
    next(action);
};

function isPromise(v) {
    return v && typeof v.then === 'function';
}


export { localStorageMiddleware }
