import { useLocation, useNavigate } from "react-router-dom";
import { Layout, Button } from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHome, faNewspaper } from "@fortawesome/pro-regular-svg-icons";

export default function Footer() {
	const navigate = useNavigate();
	const location = useLocation();

	return (
		<Layout.Footer className="navigation">
			<Button
				className={`icon home ${
					location.pathname === "/dashboard" ? "active" : ""
				}`}
				onClick={() => navigate("/")}
				type="link"
			>
				<div className="item">
					<FontAwesomeIcon icon={faHome} />
					<div className="label">Home</div>
				</div>
			</Button>
			<Button
				className={`icon news ${location.pathname === "/news" ? "active" : ""}`}
				onClick={() => navigate("/news")}
				type="link"
			>
				<div className="item">
					<FontAwesomeIcon icon={faNewspaper} />
					<div className="label">News</div>
				</div>
			</Button>
		</Layout.Footer>
	);
}
