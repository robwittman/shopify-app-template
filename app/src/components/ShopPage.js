import React from 'react';
import { connect } from 'react-redux';

class ShopPage extends React.Component {
    componentWillReceiveProps(nextProps) {
        console.log('nextProps', nextProps);
    }

    componentDidMount() {
        console.log(this.props);
    }
    render() {
        console.log(this.props);
        const shop = this.props.shop;
        return (
            <h1>{ shop.myshopify_domain }</h1>
        )
    }
}

const mapStateToProps = state => {
    console.log(state);
    return {
        shop: state.shop
    }};

const mapDispatchToProps = dispatch => ({

});

export default connect(mapStateToProps, mapDispatchToProps)(ShopPage);
