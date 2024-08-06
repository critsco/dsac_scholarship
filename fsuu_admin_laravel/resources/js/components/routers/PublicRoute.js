import React from "react";
import { Navigate } from "react-router-dom";

import PublicLayout from "../layouts/public/Public";

const isLoggedIn = localStorage.getItem("token");

const PublicRoute = (props) => {
    const { component: Component } = props;

    if (!isLoggedIn) {
        return (
            <PublicLayout {...props}>
                <Component {...props} />
            </PublicLayout>
        );
    } else {
        return <Navigate to="/dashboard" />;
    }
};

export default PublicRoute;
