import {
    TOKEN_RECEIVED,
    SHOP_RECEIVED
} from '../constants/action-types';
import { dispatch } from '../store';
import jwt_decode from 'jwt-decode';
import agent from '../agent';

export const getToken = function(args) {
    agent.Auth.token(args).then(res => {
        localStorage.setItem('jwt', res.token);
        let payload = getPayload(res.token);
        console.log(payload);
        localStorage.setItem('shop', payload.shop);
        dispatch({ type: SHOP_RECEIVED , payload: payload.shop });
        dispatch({ type: TOKEN_RECEIVED });
    }).catch(err => {
        console.log('error', err);
    });
    // return async(dispatch) => {
    //     try {
    //         const res = await agent.Auth.token(args);
    //         localStorage.setItem('jwt', res.data.token);
    //         // localStorage.setItem('shop', getPayload(res.data.token));
    //         dispatch({ type: TOKEN_RECEIVED });
    //     } catch(error) {
    //         console.log(error);
    //     }
    // }
};

function getPayload(token) {
    return jwt_decode(token);
}
