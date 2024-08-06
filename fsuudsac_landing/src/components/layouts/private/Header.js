import { useState, useEffect } from "react";
import { Layout, Dropdown, Typography, Image } from "antd";
import { PageHeader } from "@ant-design/pro-layout";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowsRotate, faPowerOff } from "@fortawesome/pro-regular-svg-icons";
import { faWifi, faWifiSlash } from "@fortawesome/pro-solid-svg-icons";
import { Network } from "@capacitor/network";

import {
	apiUrl,
	defaultProfile,
	userData,
	role,
} from "../../providers/companyInfo";
import {
	checkNetworkStatus,
	deleteFile,
} from "../../providers/useCapacitorStorage";

export default function Header(props) {
	const { pageHeaderClass, pageHeaderIcon, title, subtitle, pageId } = props;
	const [networkStatus, setNetworkStatus] = useState(false);

	const handleLogout = () => {
		deleteFile("dsac_fsuu_evaluation/form_list.txt").then((res) => {
			deleteFile("dsac_fsuu_evaluation/survey_pending_list.txt").then((res) => {
				localStorage.clear();
				window.location.replace("/");
			});
		});
	};

	const [profileImage, setProfileImage] = useState(defaultProfile);

	useEffect(() => {
		if (userData() && userData().profile_picture) {
			let profileImage = userData().profile_picture.split("//");
			if (profileImage[0] === "http:" || profileImage[0] === "https:") {
				setProfileImage(userData().profile_picture);
			} else {
				setProfileImage(apiUrl(userData().profile_picture));
			}
		}

		return () => {};
	}, []);

	const menuProfile = () => {
		const items = [
			{
				key: "/account/details",
				className: "menu-item-profile-details",
				label: (
					<div className="menu-item-details-wrapper">
						<Image
							preview={false}
							src={profileImage}
							alt={userData().username}
						/>

						<div className="info-wrapper">
							<Typography.Text className="info-username">
								{userData().firstname} {userData().lastname}
							</Typography.Text>

							<br />
							<Typography.Text className="info-role">{role()}</Typography.Text>
						</div>
					</div>
				),
			},
		];

		items.push({
			key: "/signout",
			className: "ant-menu-item-logout",
			icon: <FontAwesomeIcon icon={faPowerOff} />,
			label: <Typography.Link onClick={handleLogout}>Sign Out</Typography.Link>,
		});

		return { items };
	};

	useEffect(() => {
		Network.addListener("networkStatusChange", (res) => {
			setNetworkStatus(res.connected);
		});

		checkNetworkStatus().then((res) => {
			setNetworkStatus(res.connected);
		});

		return () => {};
	}, []);

	return (
		<Layout.Header>
			<div className="header-left-menu">
				<PageHeader
					className={pageHeaderClass}
					title={
						<>
							<div className="ant-page-header-icon">
								<FontAwesomeIcon icon={pageHeaderIcon} />
							</div>
							<div className="ant-page-header-text">
								<div className="sub-title" id="pageHeaderSubtitle">
									{pageId === "PageDashboard"
										? `Hi ${userData().firstname}!`
										: subtitle}
								</div>

								<div className="title" id="pageHeaderTitle">
									{pageId === "PageDashboard" ? "Your Surveys" : title}
								</div>
							</div>
						</>
					}
				/>
			</div>

			<div className="header-right-menu">
				<div className={`icon-menu-sync hide`} id="datasync">
					<FontAwesomeIcon icon={faArrowsRotate} spin />
				</div>
				<div
					className={`icon-menu-network ${
						networkStatus ? "online" : "offline"
					}`}
				>
					<FontAwesomeIcon icon={networkStatus ? faWifi : faWifiSlash} />
				</div>
				<Dropdown
					menu={menuProfile()}
					placement="bottomRight"
					overlayClassName="menu-submenu-profile-popup"
					trigger={["click"]}
				>
					<Image
						preview={false}
						rootClassName="menu-submenu-profile"
						src={profileImage}
						alt={userData().username}
					/>
				</Dropdown>
			</div>
		</Layout.Header>
	);
}
