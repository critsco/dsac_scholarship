import { Menu } from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faHome,
    faUsers,
    faShieldKeyhole,
    faCog,
    faMicrochip,
    faPaperclip,
} from "@fortawesome/pro-regular-svg-icons";

export const adminHeaderMenuLeft = (
    <>
        {/* <div className="ant-menu-left-icon">
            <Link to="/subscribers/current">
                <span className="anticon">
                    <FontAwesomeIcon icon={faUsers} />
                </span>
                <Typography.Text>Subscribers</Typography.Text>
            </Link>
        </div> */}
    </>
);

export const adminHeaderDropDownMenuLeft = () => {
    const items = [
        // {
        //     key: "/subscribers/current",
        //     icon: <FontAwesomeIcon icon={faUsers} />,
        //     label: <Link to="/subscribers/current">Subscribers</Link>,
        // },
    ];

    return <Menu items={items} />;
};

export const adminSideMenu = [
    {
        title: "Dashboard",
        path: "/dashboard",
        icon: <FontAwesomeIcon icon={faHome} />,
        moduleCode: "M-01",
    },
    {
        title: "Students",
        path: "/students",
        icon: <FontAwesomeIcon icon={faUsers} />,
    },
    {
        title: "Faculty",
        path: "/faculty",
        icon: <FontAwesomeIcon icon={faUsers} />,
    },
    {
        title: "System Link",
        path: "/system-link",
        icon: <FontAwesomeIcon icon={faPaperclip} />,
    },
    {
        title: "Users",
        path: "/users",
        icon: <FontAwesomeIcon icon={faUsers} />,
    },
    {
        title: "Permissions",
        path: "/permission",
        icon: <FontAwesomeIcon icon={faShieldKeyhole} />,
        children: [
            {
                title: "OPIS",
                path: "/permission/opis",
                // moduleCode: "M-04",
            },
            {
                title: "Faculty Monitoring",
                path: "/permission/faculty-monitoring",
                // moduleCode: "M-05",
            },
            {
                title: "Guidance",
                path: "/permission/guidance",
                // moduleCode: "M-05",
            },
        ],
    },
    {
        title: "System Settings",
        path: "/system-settings",
        icon: <FontAwesomeIcon icon={faCog} />,
        children: [
            {
                title: "Email Templates",
                path: "/system-settings/email-templates",
                // moduleCode: "M-06",
            },

            {
                title: "Admin Settings",
                path: "/system-settings/admin-settings",
                // moduleCode: "M-06",
            },
        ],
    },
];
