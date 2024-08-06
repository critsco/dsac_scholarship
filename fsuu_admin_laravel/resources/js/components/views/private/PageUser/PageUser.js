import { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { Row, Button, Col } from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/pro-regular-svg-icons";

import { GET } from "../../../providers/useAxiosQuery";
import TableUser from "./components/TableUser";

export default function PageUser() {
    const navigate = useNavigate();
    const location = useLocation();

    const [selectedRowKeys, setSelectedRowKeys] = useState([]);

    const [tableFilter, setTableFilter] = useState({
        page: 1,
        page_size: 50,
        search: "",
        sort_field: "created_at",
        sort_order: "desc",
        status: ["Active"],
        from: location.pathname,
    });

    const { data: dataSource, refetch: refetchSource } = GET(
        `api/users?${new URLSearchParams(tableFilter)}`,
        ["users_active_list", "check_user_permission"]
    );

    useEffect(() => {
        refetchSource();

        return () => {};
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [tableFilter]);

    return (
        <>
            <Row gutter={[12, 12]}>
                <Col xs={24} sm={24} md={24}>
                    <Button
                        className=" btn-main-primary"
                        icon={<FontAwesomeIcon icon={faPlus} />}
                        onClick={() => navigate(`/users/add`)}
                        name="btn_add"
                    >
                        Add User
                    </Button>
                </Col>

                <Col xs={24} sm={24} md={24}>
                    <TableUser
                        dataSource={dataSource}
                        tableFilter={tableFilter}
                        setTableFilter={setTableFilter}
                        selectedRowKeys={selectedRowKeys}
                        setSelectedRowKeys={setSelectedRowKeys}
                    />
                </Col>
            </Row>
        </>
    );
}
