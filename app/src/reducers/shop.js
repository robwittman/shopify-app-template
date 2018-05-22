import {
    SHOP_RECEIVED
} from '../constants/action-types';

export default (state = {}, action) => {
    console.log(action);
    switch (action.type) {
        case SHOP_RECEIVED:
            return action.payload;
        default:
            return state;
    }
};
