import { Routes, Route } from "react-router-dom";
import {
    faBooks,
    faCog,
    faHome,
    faMicrochip,
    faPaperclip,
    faUsers,
} from "@fortawesome/pro-regular-svg-icons";

import PublicRoute from "./PublicRoute";
import PrivateRoute from "./PrivateRoute";

import Page404 from "../views/errors/Page404";
import PageRequestPermission from "../views/errors/PageRequestPermission";

import PageLogin from "../views/public/PageLogin/PageLogin";

import PageEditProfile from "../views/private/PageEditProfile/PageEditProfile";
import PageDashboard from "../views/private/PageDashboard/PageDashboard";
import PageUser from "../views/private/PageUser/PageUser";
import PageUserForm from "../views/private/PageUser/PageUserForm";
import PageUserPermission from "../views/private/PageUser/PageUserPermission";
import PagePermission from "../views/private/PagePermission/PagePermission";
import PageEmailTemplate from "../views/private/PageSystemSettings/PageEmailTemplate/PageEmailTemplate";
import PageSettings from "../views/private/PageReferences/PageSettings/PageSettings";
import PageComponents from "../views/private/PageComponents/PageComponents";
import PageStudents from "../views/private/PageStudents/PageStudents";
import PageFaculty from "../views/private/PageFaculty/PageFaculty";
import PageSystemLink from "../views/private/PageSystemLink/PageSystemLink";

export default function RouteList() {
    return (
        <Routes>
            <Route
                path="/"
                element={
                    <PublicRoute
                        title="LOGIN"
                        pageId="PageLogin"
                        component={PageLogin}
                    />
                }
            />

            <Route
                path="/edit-profile"
                element={
                    <PrivateRoute
                        moduleName="Edit Profile"
                        title="User"
                        subtitle="VIEW / EDIT"
                        pageId="PageUserProfile"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Edit Profile",
                            },
                        ]}
                        component={PageEditProfile}
                    />
                }
            />

            <Route
                path="/dashboard"
                element={
                    <PrivateRoute
                        // moduleCode="M-01"
                        moduleName="Dashboard"
                        title="Dashboard"
                        subtitle="ADMIN"
                        pageId="PageDashboard"
                        pageHeaderIcon={faHome}
                        breadcrumb={[
                            {
                                name: "Dashboard",
                            },
                        ]}
                        component={PageDashboard}
                    />
                }
            />

            <Route
                path="/students"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="Students"
                        title="Students"
                        subtitle="VIEW / EDIT"
                        pageId="PageStudents"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Students",
                            },
                        ]}
                        component={PageStudents}
                    />
                }
            />

            <Route
                path="/faculty"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="Faculty"
                        title="Faculty"
                        subtitle="VIEW / EDIT"
                        pageId="PageFaculty"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Faculty",
                            },
                        ]}
                        component={PageFaculty}
                    />
                }
            />

            <Route
                path="/system-link"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="System Link"
                        title="System Link"
                        subtitle="VIEW / EDIT"
                        pageId="PageSystemLink"
                        pageHeaderIcon={faPaperclip}
                        breadcrumb={[
                            {
                                name: "System Link",
                            },
                        ]}
                        component={PageSystemLink}
                    />
                }
            />

            {/* users */}
            <Route
                path="/users"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="User"
                        title="Users"
                        subtitle="VIEW / EDIT"
                        pageId="PageUser"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Users",
                            },
                        ]}
                        component={PageUser}
                    />
                }
            />

            <Route
                path="/users/add"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="User Add"
                        title="Users"
                        subtitle="ADD"
                        pageId="PageUserAdd"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Users",
                                link: "/users",
                            },
                            {
                                name: "Add User",
                            },
                        ]}
                        component={PageUserForm}
                    />
                }
            />

            <Route
                path="/users/edit/:id"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="User Edit"
                        title="Users"
                        subtitle="EDIT"
                        pageId="PageUserEdit"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Users",
                                link: "/users",
                            },
                            {
                                name: "Edit User",
                            },
                        ]}
                        component={PageUserForm}
                    />
                }
            />

            <Route
                path="/users/permission/:id"
                element={
                    <PrivateRoute
                        // moduleCode="M-02"
                        moduleName="User Edit Permission"
                        title="User's Edit Permission"
                        subtitle="EDIT"
                        pageId="PageUserEdit"
                        pageHeaderIcon={faUsers}
                        breadcrumb={[
                            {
                                name: "Users",
                                link: "/users",
                            },
                            {
                                name: "Edit Permission",
                            },
                        ]}
                        component={PageUserPermission}
                    />
                }
            />

            {/* end users */}

            {/* permission */}

            <Route
                path="/permission/:system"
                element={
                    <PrivateRoute
                        // moduleCode="M-04"
                        moduleName="Permission"
                        title="Permission"
                        subtitle="Permission"
                        pageId="PagePermission"
                        pageHeaderIcon={faBooks}
                        breadcrumb={[
                            {
                                name: "Permission",
                            },
                            {
                                name: "Permission",
                            },
                        ]}
                        component={PagePermission}
                    />
                }
            />

            {/* end permission */}

            {/* system settings */}
            <Route
                path="/system-settings/email-templates"
                element={
                    <PrivateRoute
                        // moduleCode="M-04"
                        moduleName="System Settings - Email Template"
                        title="Templates"
                        subtitle="EMAIL"
                        pageId="PageSystemSettingsEmailTemplate"
                        pageHeaderIcon={faCog}
                        breadcrumb={[
                            {
                                name: "System Settings",
                            },
                            {
                                name: "Email Templates",
                            },
                        ]}
                        component={PageEmailTemplate}
                    />
                }
            />

            <Route
                path="/system-settings/admin-settings"
                element={
                    <PrivateRoute
                        // moduleCode="M-04"
                        moduleName="System Settings - Admin Settings"
                        title="Settings"
                        subtitle="ADMIN"
                        pageId="PageSystemSettingsEmailTemplate"
                        pageHeaderIcon={faCog}
                        breadcrumb={[
                            {
                                name: "System Settings",
                            },
                            {
                                name: "Admin Settings",
                            },
                        ]}
                        component={PageSettings}
                    />
                }
            />

            {/* end system settings */}

            <Route
                path="/components"
                element={
                    <PrivateRoute
                        // moduleCode="M-04"
                        moduleName="Components"
                        title="Components"
                        subtitle="LIST"
                        pageId="PageComponents"
                        pageHeaderIcon={faMicrochip}
                        breadcrumb={[
                            {
                                name: "Components",
                            },
                        ]}
                        component={PageComponents}
                    />
                }
            />

            <Route
                path="/request-permission"
                element={<PageRequestPermission />}
            />

            <Route path="*" element={<Page404 />} />
        </Routes>
    );
}
