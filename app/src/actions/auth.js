import {
    GET_TOKEN
} from '../constants/action-types';

export const getToken = args => ({
    type: GET_TOKEN,
    payload: args
});
