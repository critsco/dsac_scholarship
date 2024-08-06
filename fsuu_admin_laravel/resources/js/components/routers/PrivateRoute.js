import React from "react";
import { Navigate } from "react-router-dom";

import PrivateLayout from "../layouts/private/Private";

let isLoggedIn = localStorage.getItem("token");

const PrivateRoute = (props) => {
    const { component: Component } = props;

    if (isLoggedIn) {
        return (
            <PrivateLayout {...props}>
                <Component {...props} />
            </PrivateLayout>
        );
    } else {
        return <Navigate to="/" />;
    }
};

export default PrivateRoute;
