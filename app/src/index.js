import React from 'react';
import { render } from "react-dom";
import { Provider } from "react-redux";
import { Route } from 'react-router'
import { ConnectedRouter } from 'react-router-redux'
import App from './components/App';
import { store, history } from './store';
import BootstrapWrapper from './components/BootstrapWrapper';
import { AppProvider } from '@shopify/polaris';

console.log('env', SHOPIFY_API_KEY);
window.store = store;

render(
    <Provider store={store}>
        <ConnectedRouter history={history}>
            <AppProvider
                apiKey={SHOPIFY_API_KEY}
                forceRedirect={true}
                debug={ENVIRONMENT !== 'production'}
                shopOrigin={'https://importer-testing.myshopify.com'}>
                <BootstrapWrapper>
                    <Route path="/" component={App} />
                </BootstrapWrapper>
            </AppProvider>
        </ConnectedRouter>
    </Provider>,
document.getElementById("app")
);
