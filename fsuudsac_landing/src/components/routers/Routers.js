import React from "react";
import { BrowserRouter as Router } from "react-router-dom";
import { QueryClient, QueryClientProvider } from "react-query";

import "../assets/scss/app.scss";
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
