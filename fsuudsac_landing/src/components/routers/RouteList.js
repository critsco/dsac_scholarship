import { Routes, Route } from "react-router-dom";

import PublicRoute from "./PublicRoute";
// import PrivateRoute from "./PrivateRoute";

import Page404 from "../views/errors/Page404";

import PageLanding from "../views/public/PageLanding/PageLanding";

export default function RouteList() {
	return (
		<Routes>
			<Route
				path="/"
				element={
					<PublicRoute title="" pageId="PageLanding" component={PageLanding} />
				}
			/>

			<Route path="*" element={<Page404 pageId="Page404" />} />
		</Routes>
	);
}
