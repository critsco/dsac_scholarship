import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import { Row, Col, Tabs } from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faMicrochip } from "@fortawesome/pro-regular-svg-icons";

import TabPermissionModule from "./components/TabPermissionModule";
import TabPermissionUserRole from "./components/TabPermissionUserRole";

export default function PagePermission() {
    const location = useLocation();

    return (
        <Row gutter={[12, 12]}>
            <Col xs={24} sm={24} md={24}>
                <Tabs
                    defaultActiveKey="0"
                    type="card"
                    items={[
                        {
                            key: "0",
                            label: "Module",
                            icon: <FontAwesomeIcon icon={faMicrochip} />,
                            children: (
                                <TabPermissionModule location={location} />
                            ),
                        },
                        {
                            key: "1",
                            label: "User Role",
                            icon: <FontAwesomeIcon icon={faMicrochip} />,
                            children: (
                                <TabPermissionUserRole location={location} />
                            ),
                        },
                    ]}
                />
            </Col>
        </Row>
    );
}
