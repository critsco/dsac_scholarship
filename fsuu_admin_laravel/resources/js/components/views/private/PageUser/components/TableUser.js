import {
    Row,
    Col,
    Table,
    Button,
    notification,
    Popconfirm,
    Tooltip,
    Checkbox,
} from "antd";
import { POST, GET } from "../../../../providers/useAxiosQuery";
import {
    TableDropdownFilter,
    TableGlobalSearch,
    TableGlobalSearchAnimated,
    TablePageSize,
    TablePagination,
    TableShowingEntries,
} from "../../../../providers/CustomTableFilter";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faPencil,
    faTrash,
    faUserGear,
} from "@fortawesome/pro-regular-svg-icons";
import notificationErrors from "../../../../providers/notificationErrors";
import { useNavigate } from "react-router-dom";
import dayjs from "dayjs";

export default function TableUser(props) {
    const {
        dataSource,
        tableFilter,
        setTableFilter,
        selectedRowKeys,
        setSelectedRowKeys,
    } = props;

    const navigate = useNavigate();

    const { mutate: mutateDeactivateUser, loading: loadingDeactivateUser } =
        POST(`api/user_deactivate`, "users_active_list");

    const handleDeactivate = (record) => {
        mutateDeactivateUser(record, {
            onSuccess: (res) => {
                if (res.success) {
                    notification.success({
                        message: "User",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "User",
                        description: res.message,
                    });
                }
            },
            onError: (err) => {
                notificationErrors(err);
            },
        });
    };

    const onChangeTable = (pagination, filters, sorter) => {
        setTableFilter((ps) => ({
            ...ps,
            sort_field: sorter.columnKey,
            sort_order: sorter.order ? sorter.order.replace("end", "") : null,
            page: 1,
            page_size: "50",
        }));
    };

    return (
        <Row gutter={[12, 12]} id="tbl_wrapper">
            <Col xs={24} sm={24} md={24}>
                <div className="tbl-top-filter">
                    <div
                        style={{
                            display: "flex",
                            gap: 8,
                        }}
                    >
                        <TableDropdownFilter
                            items={[
                                {
                                    key: "1",
                                    label: (
                                        <>
                                            <Checkbox
                                                id="chck_archived"
                                                checked={
                                                    tableFilter.status.filter(
                                                        (f) => f === "Archived"
                                                    ).length > 0
                                                }
                                                onChange={(e) => {
                                                    let tableFilterCopy =
                                                        tableFilter;
                                                    let status =
                                                        tableFilterCopy.status;
                                                    let statusFilter =
                                                        tableFilterCopy.status.filter(
                                                            (f) =>
                                                                f === "Archived"
                                                        );
                                                    if (e.target.checked) {
                                                        if (
                                                            statusFilter.length ===
                                                            0
                                                        ) {
                                                            status.push(
                                                                "Archived"
                                                            );
                                                        }
                                                    } else {
                                                        status = status.filter(
                                                            (f) =>
                                                                f !== "Archived"
                                                        );
                                                    }

                                                    setTableFilter((ps) => ({
                                                        ...ps,
                                                        status,
                                                    }));

                                                    setSelectedRowKeys([]);
                                                }}
                                            />
                                            <label htmlFor="chck_archived">
                                                Archived
                                            </label>
                                        </>
                                    ),
                                },
                                {
                                    key: "2",
                                    label: (
                                        <>
                                            <Checkbox
                                                id="chck_active"
                                                checked={
                                                    tableFilter.status.filter(
                                                        (f) => f === "Active"
                                                    ).length > 0
                                                }
                                                onChange={(e) => {
                                                    let tableFilterCopy =
                                                        tableFilter;
                                                    let status =
                                                        tableFilterCopy.status;
                                                    let statusFilter =
                                                        tableFilterCopy.status.filter(
                                                            (f) =>
                                                                f === "Active"
                                                        );
                                                    if (e.target.checked) {
                                                        if (
                                                            statusFilter.length ===
                                                            0
                                                        ) {
                                                            status.push(
                                                                "Active"
                                                            );
                                                        }
                                                    } else {
                                                        status = status.filter(
                                                            (f) =>
                                                                f !== "Active"
                                                        );
                                                    }

                                                    setTableFilter((ps) => ({
                                                        ...ps,
                                                        status,
                                                    }));

                                                    setSelectedRowKeys([]);
                                                }}
                                            />
                                            <label htmlFor="chck_active">
                                                Active
                                            </label>
                                        </>
                                    ),
                                },
                            ]}
                        />

                        <TableGlobalSearchAnimated
                            tableFilter={tableFilter}
                            setTableFilter={setTableFilter}
                        />

                        {selectedRowKeys.length > 0 && (
                            <Popconfirm
                                title={
                                    <>
                                        Are you sure you want to
                                        <br />
                                        {tableFilter.status.filter(
                                            (f) => f === "Active"
                                        ).length > 0
                                            ? "archive"
                                            : "activate"}{" "}
                                        the selected{" "}
                                        {selectedRowKeys.length > 1
                                            ? "users"
                                            : "user"}
                                        ?
                                    </>
                                }
                                okText="Yes"
                                cancelText="No"
                                onConfirm={() => {
                                    handleSelectedArchived(tableFilter.status);
                                }}
                            >
                                <Button
                                    className="btn-main-secondary"
                                    name="btn_active_archive"
                                    loading={loadingDeactivateUser}
                                >
                                    {tableFilter.status.filter(
                                        (f) => f === "Active"
                                    ).length > 0
                                        ? "ARCHIVE"
                                        : "ACTIVATE"}{" "}
                                    SELECTED
                                </Button>
                            </Popconfirm>
                        )}
                    </div>

                    <div
                        style={{
                            display: "flex",
                            gap: 12,
                        }}
                    >
                        <TableShowingEntries />

                        <TablePageSize
                            tableFilter={tableFilter}
                            setTableFilter={setTableFilter}
                        />
                    </div>
                </div>
            </Col>

            <Col xs={24} sm={24} md={24}>
                <Table
                    className="ant-table-default ant-table-striped"
                    dataSource={dataSource && dataSource.data.data}
                    rowKey={(record) => record.id}
                    pagination={false}
                    bordered={false}
                    onChange={onChangeTable}
                    scroll={{ x: "max-content" }}
                    rowSelection={{
                        selectedRowKeys,
                        onChange: (selectedRowKeys) => {
                            setSelectedRowKeys(selectedRowKeys);
                        },
                        getCheckboxProps: (record) => ({
                            disabled: tableFilter.status.length === 2,
                            // Column configuration not to be checked
                            // name: record.name,
                        }),
                    }}
                    sticky
                >
                    <Table.Column
                        title="Action"
                        key="action"
                        dataIndex="action"
                        align="center"
                        width={200}
                        render={(text, record) => {
                            return (
                                <div
                                    style={{
                                        display: "flex",
                                        gap: 8,
                                        justifyContent: "center",
                                    }}
                                >
                                    <Button
                                        type="link"
                                        className="w-auto h-auto p-0"
                                        onClick={() => {
                                            navigate(
                                                `${location.pathname}/permission/${record.id}`
                                            );
                                        }}
                                        name="btn_edit_permission"
                                        title="Edit Permission"
                                        icon={
                                            <FontAwesomeIcon
                                                icon={faUserGear}
                                            />
                                        }
                                    />
                                    <Button
                                        type="link"
                                        className="w-auto h-auto p-0"
                                        onClick={() => {
                                            navigate(
                                                `${location.pathname}/edit/${record.id}`
                                            );
                                        }}
                                        name="btn_edit"
                                        icon={
                                            <FontAwesomeIcon icon={faPencil} />
                                        }
                                    />
                                    <Popconfirm
                                        title="Are you sure to deactivate this data?"
                                        onConfirm={() => {
                                            handleDeactivate(record);
                                        }}
                                        onCancel={() => {
                                            notification.error({
                                                message: "User",
                                                description:
                                                    "Data not deactivated",
                                            });
                                        }}
                                        okText="Yes"
                                        cancelText="No"
                                    >
                                        <Button
                                            type="link"
                                            className="w-auto h-auto p-0 text-danger"
                                            loading={loadingDeactivateUser}
                                            name="btn_delete"
                                            icon={
                                                <FontAwesomeIcon
                                                    icon={faTrash}
                                                />
                                            }
                                        />
                                    </Popconfirm>
                                </div>
                            );
                        }}
                    />
                    <Table.Column
                        title="Start Date"
                        key="created_at"
                        dataIndex="created_at"
                        render={(text, _) =>
                            text ? dayjs(text).format("MM/DD/YYYY") : ""
                        }
                        sorter
                    />
                    <Table.Column
                        title="Email"
                        key="email"
                        dataIndex="email"
                        sorter={true}
                    />
                    <Table.Column
                        title="Username"
                        key="username"
                        dataIndex="username"
                        sorter
                    />
                    <Table.Column
                        title="Type"
                        key="type"
                        dataIndex="type"
                        sorter
                    />
                    <Table.Column
                        title="Role"
                        key="role"
                        dataIndex="role"
                        sorter={true}
                    />
                </Table>
            </Col>
            <Col xs={24} sm={24} md={24}>
                <div className="tbl-bottom-filter">
                    <TableShowingEntries />
                    <TablePagination
                        tableFilter={tableFilter}
                        setTableFilter={setTableFilter}
                        setPaginationTotal={dataSource?.data.total}
                        showLessItems={true}
                        showSizeChanger={false}
                        tblIdWrapper="tbl_wrapper"
                    />
                </div>
            </Col>
        </Row>
    );
}
