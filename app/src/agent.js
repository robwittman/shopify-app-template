import superagentPromise from 'superagent-promise';
import _superagent from 'superagent';

const superagent = superagentPromise(_superagent, global.Promise);

const API_ROOT = 'https://api.local/index.php';

const encode = encodeURIComponent;
const responseBody = res => res.body;

let token = null;
const tokenPlugin = req => {
    if (token) {
        console.log(token);
        req.set('Authorization', `Token ${token}`);
    }
};

const requests = {
    del: url => superagent.del(`${API_ROOT}${url}`).use(tokenPlugin).then(responseBody),
    get: url => superagent.get(`${API_ROOT}${url}`).use(tokenPlugin).then(responseBody),
    put: (url, body) => superagent.put(`${API_ROOT}${url}`, body).use(tokenPlugin).then(responseBody),
    post: (url, body) => superagent.post(`${API_ROOT}${url}`, body).use(tokenPlugin).then(responseBody)
};

const Auth = {
    token: (params) => requests.post('/auth/token', params),
    install: (params) => requests.post('/auth/install', params)
};

const Shops = {
    get: page => requests.get('/shop'),
    update: (id, data) => requests.get(`/shops/${ id }`, data),
};

const limit = (count, p) => `limit=${count}&offset=${p ? p * count : 0}`;
const omitSlug = article => Object.assign({}, article, { slug: undefined })

export default {
    Auth,
    Shops,
    setToken: _token => { token = _token; }
};
