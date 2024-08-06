import { faScreenUsers } from "@fortawesome/pro-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Button, Table } from "antd";

export default function TableStudents(props) {
    const { dataSource, onChangeTable, setToggleModalShowSchedules } = props;

    return (
        <Table
            id="table-students"
            className="ant-table-default ant-table-striped"
            dataSource={
                dataSource && dataSource.data ? dataSource.data.data : []
            }
            rowKey={(record) => record.id}
            pagination={false}
            bordered={false}
            onChange={onChangeTable}
            scroll={{ x: "max-content" }}
            sticky
        >
            <Table.Column
                title="Created At"
                key="created_at_formatted"
                dataIndex="created_at_formatted"
                width={150}
                sorter
            />
            <Table.Column
                title="School ID"
                key="school_id"
                dataIndex="school_id"
                width={120}
                sorter
            />
            <Table.Column
                title="Name"
                key="fullname"
                dataIndex="fullname"
                width={120}
                sorter
            />
            <Table.Column
                title="Schedule"
                key="schedule_list"
                align="center"
                width={100}
                render={(text, record) => {
                    return (
                        <>
                            <Button
                                type="link"
                                className="p-0 w-auto h-auto"
                                onClick={() => {
                                    setToggleModalShowSchedules({
                                        open: true,
                                        data: record,
                                    });
                                }}
                                icon={<FontAwesomeIcon icon={faScreenUsers} />}
                            />
                        </>
                    );
                }}
            />
        </Table>
    );
}
