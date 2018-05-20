import {
    GET_SHOP,
    UPDATE_SHOP,
} from '../constants/action-types';

export const getShop = () => ({
    type: GET_SHOP
});

export const updateShop = data => ({
    type: UPDATE_SHOP,
    payload: data
});
