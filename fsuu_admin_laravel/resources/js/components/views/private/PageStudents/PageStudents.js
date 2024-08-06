import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import { Button, Col, Popconfirm, Row } from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFileExcel } from "@fortawesome/pro-regular-svg-icons";

import { GET } from "../../../providers/useAxiosQuery";
import {
    TableGlobalSearchAnimated,
    TablePageSize,
    TablePagination,
    TableShowingEntriesV2,
    useTableScrollOnTop,
} from "../../../providers/CustomTableFilter";
import TableStudents from "./components/TableStudents";
import ModalStudentFormUploadExcel from "./components/ModalStudentFormUploadExcel";
import ModalStudentShowSchedule from "./components/ModalStudentShowSchedule";

export default function PageStudents() {
    const location = useLocation();

    const [toggleModalUploadExcel, setToggleModalUploadExcel] = useState(false);
    const [toggleModalShowSchedules, setToggleModalShowSchedules] = useState({
        open: false,
        data: null,
    });

    const [tableFilter, setTableFilter] = useState({
        page: 1,
        page_size: 50,
        search: "",
        sort_field: "created_at",
        sort_order: "desc",
        status: "Active",
        from: location.pathname,
    });

    const { data: dataSchoolYear } = GET(
        `api/ref_school_year`,
        "school_year_dropdown"
    );

    const { data: dataSemester } = GET(`api/ref_semester`, "semester_dropdown");

    const { data: dataSource, refetch: refetchSource } = GET(
        `api/profile?${new URLSearchParams(tableFilter)}`,
        "student_list"
    );

    useEffect(() => {
        refetchSource();
        return () => {};
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [tableFilter]);

    const onChangeTable = (pagination, filters, sorter) => {
        setTableFilter((prevState) => ({
            ...prevState,
            sort_field: sorter.columnKey,
            sort_order: sorter.order ? sorter.order.replace("end", "") : null,
            page: 1,
            page_size: "50",
        }));
    };

    useTableScrollOnTop("table-students", location);

    return (
        <Row gutter={[12, 12]} id="tbl_wrapper">
            <Col xs={24} sm={24} md={24}>
                <Button
                    className="btn-main-primary min-w-150"
                    onClick={() => setToggleModalUploadExcel(true)}
                    name="btn_upload_excel"
                    icon={<FontAwesomeIcon icon={faFileExcel} />}
                >
                    Upload File Excel
                </Button>
            </Col>

            <Col xs={24} sm={24} md={24}>
                <div className="tbl-top-filter">
                    <div
                        style={{
                            display: "flex",
                            gap: 8,
                        }}
                    >
                        <Button
                            className={`btn-main-primary min-w-150 ${
                                tableFilter.status === "Active"
                                    ? "active"
                                    : "outlined"
                            }`}
                            onClick={() => {
                                setTableFilter((ps) => ({
                                    ...ps,
                                    status: "Active",
                                }));
                            }}
                        >
                            Active
                        </Button>

                        <Button
                            className={`btn-main-primary min-w-150 ${
                                tableFilter.status === "Archived"
                                    ? "active"
                                    : "outlined"
                            }`}
                            onClick={() => {
                                setTableFilter((ps) => ({
                                    ...ps,
                                    status: "Archived",
                                }));
                            }}
                        >
                            Archived
                        </Button>

                        <TableGlobalSearchAnimated
                            tableFilter={tableFilter}
                            setTableFilter={setTableFilter}
                        />
                    </div>

                    <div
                        style={{
                            display: "flex",
                            gap: 12,
                        }}
                    >
                        <TableShowingEntriesV2 />

                        <TablePageSize
                            tableFilter={tableFilter}
                            setTableFilter={setTableFilter}
                        />
                    </div>
                </div>
            </Col>

            <Col xs={24} sm={24} md={24}>
                <TableStudents
                    dataSource={dataSource}
                    onChangeTable={onChangeTable}
                    setToggleModalShowSchedules={setToggleModalShowSchedules}
                />
            </Col>

            <Col xs={24} sm={24} md={24}>
                <div className="tbl-bottom-filter">
                    <TableShowingEntriesV2 />
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

            <ModalStudentFormUploadExcel
                toggleModalUploadExcel={toggleModalUploadExcel}
                setToggleModalUploadExcel={setToggleModalUploadExcel}
                dataSchoolYear={dataSchoolYear}
                dataSemester={dataSemester}
            />

            <ModalStudentShowSchedule
                toggleModalShowSchedules={toggleModalShowSchedules}
                setToggleModalShowSchedules={setToggleModalShowSchedules}
            />
        </Row>
    );
}
