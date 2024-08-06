import { useNavigate } from "react-router-dom";
import { Layout, Result, Button } from "antd";

export default function Page404(props) {
	const { pageId } = props;
	const navigate = useNavigate();

	return (
		<Layout id={pageId ?? ""}>
			<Layout.Content>
				<Result
					status="404"
					title="404"
					subTitle="Sorry, the page you visited does not exist."
					extra={
						<Button type="primary" onClick={() => navigate("/")}>
							Back Home
						</Button>
					}
				/>
			</Layout.Content>
		</Layout>
	);
}
