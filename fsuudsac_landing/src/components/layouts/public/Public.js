import { useEffect } from "react";
import ClearCache from "react-clear-cache";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGifts, faRefresh } from "@fortawesome/pro-regular-svg-icons";
import { Button, Layout } from "antd";

import { name } from "../../providers/companyInfo";

export default function Public(props) {
	const { children, title, pageId } = props;

	useEffect(() => {
		if (title) {
			document.title = title + " | " + name;
		}

		return () => {};
	}, [title]);

	return (
		<ClearCache>
			{({ isLatestVersion, emptyCacheStorage }) => (
				<>
					{!isLatestVersion && (
						<div className="notification-notice">
							<div className="notification-notice-content">
								<div className="notification-notice-icon">
									<FontAwesomeIcon icon={faGifts} />
								</div>
								<div className="notification-notice-message">
									<div className="title">Updates Now Available</div>
									<div className="description">
										A new version of this Web App is ready
									</div>
									<div className="action">
										<Button
											onClick={(e) => {
												e.preventDefault();
												emptyCacheStorage();
											}}
											icon={<FontAwesomeIcon icon={faRefresh} />}
										>
											Refresh
										</Button>
									</div>
								</div>
							</div>
						</div>
					)}

					<Layout className="public-layout" id={pageId ?? ""}>
						{children}
					</Layout>
				</>
			)}
		</ClearCache>
	);
}
