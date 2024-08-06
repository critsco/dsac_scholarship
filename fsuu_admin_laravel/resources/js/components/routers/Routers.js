import React from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter as Router } from "react-router-dom";
import { QueryClient, QueryClientProvider } from "react-query";

import RouteList from "./RouteList";

const queryClient = new QueryClient();

export default function Routers() {
    return (
        <QueryClientProvider client={queryClient}>
            <Router>
                <RouteList />
            </Router>
        </QueryClientProvider>
    );
}

const root = createRoot(document.getElementById("root"));
root.render(<Routers />);
