import React, { useEffect } from "react";
import ClearCache from "react-clear-cache";
import { Button, Layout, notification } from "antd";
import { SpinnerDotted } from "spinners-react";
import { Network } from "@capacitor/network";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
	faArrowsRotate,
	faGifts,
	faRefresh,
	faWifi,
	faWifiSlash,
} from "@fortawesome/pro-regular-svg-icons";

import { bgColor, name } from "../../providers/companyInfo";
import Header from "./Header";
import Footer from "./Footer";

export default function Private(props) {
	const { children, title, subtitle, pageHeaderIcon, pageHeaderClass, pageId } =
		props;

	useEffect(() => {
		Network.addListener("networkStatusChange", (res) => {
			let description = "";
			let class_status = "success-color";
			let icon = faWifi;

			if (res.connected) {
				description = (
					<>
						<span>Your internet connection was restored.</span>
						<Button
							type="dashed"
							icon={<FontAwesomeIcon icon={faArrowsRotate} />}
							onClick={() => {
								window.location.reload();
							}}
						>
							Refresh
						</Button>
					</>
				);
				class_status = "success-color";
				icon = faWifi;
			} else {
				description = "You are currently offline.";
				class_status = "";
				icon = faWifiSlash;
			}

			notification.info({
				message: "Internet Connection",
				description,
				placement: "bottomLeft",
				icon: <FontAwesomeIcon icon={icon} className={class_status} />,
				duration: 10,
			});
		});

		return () => {
			Network.removeAllListeners();
		};
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

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

					<div className="globalLoading hide">
						<SpinnerDotted thickness="100" color={bgColor} enabled={true} />
					</div>

					<Layout className="private-layout" id={pageId ?? ""}>
						<Header
							pageHeaderClass={pageHeaderClass}
							pageHeaderIcon={pageHeaderIcon}
							title={title}
							subtitle={subtitle}
							pageId={pageId}
						/>

						<Layout.Content>{children}</Layout.Content>

						<Footer />
					</Layout>
				</>
			)}
		</ClearCache>
	);
}
