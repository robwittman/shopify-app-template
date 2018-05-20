import agent from './agent';
import {
    ASYNC_START,
    ASYNC_END,
    LOGIN,
    LOGOUT,
    REGISTER
} from './constants/action-types';

console.log(process.env);
const promiseMiddleware = store => next => action => {
    console.log(action);
    if (isPromise(action.payload)) {
        store.dispatch({ type: ASYNC_START, subtype: action.type });

        const currentView = store.getState().viewChangeCounter;
        const skipTracking = action.skipTracking;

        action.payload.then(
            res => {
                const currentState = store.getState();
                if (!skipTracking && currentState.viewChangeCounter !== currentView) {
                    return
                }
                console.log('RESULT', res);
                action.payload = res;
                store.dispatch({ type: ASYNC_END, promise: action.payload });
                store.dispatch(action);
            },
            error => {
                const currentState = store.getState();
                if (!skipTracking && currentState.viewChangeCounter !== currentView) {
                    return
                }
                console.log('ERROR', error);
                action.error = true;
                action.payload = error.response.body;
                if (action.payload.error === 'UNAUTHORIZED') {
                    // Redirect to authentication
                    let url = `https://importer-testing.myshopify.com/admin/oauth/authorize?client_id=${SHOPIFY_API_KEY}&scope=read_products&redirect_uri=${SHOPIFY_REDIRECT_URI}`;
                    console.log(url);
                    window.top.location.href = url;
                } else if (action.payload.error === 'CHARGE_REQUIRED') {
                    // Redirect to accept charge
                }
                // if (!action.skipTracking) {
                //     store.dispatch({ type: ASYNC_END, promise: action.payload });
                // } else if (action.payload.error ===)
                store.dispatch(action);
            }
        );

        return;
    }

    next(action);
};

const localStorageMiddleware = store => next => action => {
    if (action.type === REGISTER || action.type === LOGIN) {
        console.log('payload', action.payload);
        if (!action.error) {
            window.localStorage.setItem('jwt', action.payload.token);
            agent.setToken(action.payload.token);
        }
    } else if (action.type === LOGOUT) {
        window.localStorage.setItem('jwt', '');
        agent.setToken(null);
    }

    next(action);
};

function isPromise(v) {
    return v && typeof v.then === 'function';
}


export { promiseMiddleware, localStorageMiddleware }
