import { useEffect, useState } from "react";
import { CaretDownOutlined } from "@ant-design/icons";
import { Button, Input, Pagination, Select, Typography } from "antd";
import optionAlphabet from "./optionAlphabet";

export function TablePagination(props) {
    const {
        showLessItems,
        showSizeChanger,
        tableFilter,
        setTableFilter,
        setPaginationTotal,
        tblIdWrapper,
    } = props;

    const [paginationSize, setPaginationSize] = useState("default");

    useEffect(() => {
        window.onresize = function () {
            if (window.offsetWidth <= 768) {
                setPaginationSize("small");
            } else {
                setPaginationSize("default");
            }
        };
    }, []);

    return (
        <>
            <Pagination
                current={tableFilter.page}
                total={setPaginationTotal}
                size={paginationSize}
                showLessItems={showLessItems ?? false}
                showSizeChanger={showSizeChanger ?? true}
                showTotal={(total, range) => {
                    let tbl_wrapper = document.getElementById(tblIdWrapper);
                    if (tbl_wrapper) {
                        let span_page_from =
                            tbl_wrapper.querySelector(".span_page_from");
                        let span_page_to =
                            tbl_wrapper.querySelector(".span_page_to");
                        let span_page_total =
                            tbl_wrapper.querySelector(".span_page_total");
                        if (span_page_from) {
                            span_page_from.textContent = range[0];
                        }
                        if (span_page_to) {
                            span_page_to.textContent = range[1];
                        }
                        if (span_page_total) {
                            span_page_total.textContent = total;
                        }
                    } else {
                        let span_page_from =
                            document.querySelector(".span_page_from");
                        let span_page_to =
                            document.querySelector(".span_page_to");
                        let span_page_total =
                            document.querySelector(".span_page_total");
                        if (span_page_from) {
                            span_page_from.textContent = range[0];
                        }
                        if (span_page_to) {
                            span_page_to.textContent = range[1];
                        }
                        if (span_page_total) {
                            span_page_total.textContent = total;
                        }
                    }
                }}
                pageSize={tableFilter.page_size}
                onChange={(page, pageSize) =>
                    setTableFilter({
                        ...tableFilter,
                        page,
                        page_size: pageSize,
                    })
                }
                itemRender={(current, type, originalElement) => {
                    if (type === "prev") {
                        return <Button>Previous</Button>;
                    }
                    if (type === "next") {
                        return <Button>Next</Button>;
                    }
                    return originalElement;
                }}
            />
        </>
    );
}

export function TableShowingEntries() {
    return (
        <Typography.Text>
            Showing <span className="span_page_from"></span> to{" "}
            <span className="span_page_to"></span> of{" "}
            <span className="span_page_total"></span> entries
        </Typography.Text>
    );
}

export function TablePageSize(props) {
    const { tableFilter, setTableFilter, className, option } = props;

    return (
        <div className="tbl-page-size">
            <Select
                value={tableFilter.page_size}
                onChange={(e) =>
                    setTableFilter({ ...tableFilter, page_size: e })
                }
                className={className ?? "ant-select-table-pagesize"}
                suffixIcon={<CaretDownOutlined />}
                size="large"
            >
                {option && option.length > 0 ? (
                    option.map((item, index) => {
                        return (
                            <Select.Option value={item} key={index}>
                                {item}
                            </Select.Option>
                        );
                    })
                ) : (
                    <>
                        <Select.Option value={10}>10</Select.Option>
                        <Select.Option value={25}>25</Select.Option>
                        <Select.Option value={50}>50</Select.Option>
                        <Select.Option value={75}>75</Select.Option>
                        <Select.Option value={100}>100</Select.Option>
                    </>
                )}
            </Select>
            <Typography.Text> / Page</Typography.Text>
        </div>
    );
}

export function TableGlobalSearch(props) {
    const { tableFilter, setTableFilter, placeholder, size, className } = props;

    const [searchTextTimeout, setSearchTextTimeout] = useState(0);

    return (
        <div className="tbl-search-wrapper">
            <Input.Search
                placeholder={placeholder ?? "Search..."}
                size={size ?? "large"}
                className={className ?? "ant-input-padding-inherit"}
                onChange={(e) => {
                    if (searchTextTimeout) clearTimeout(searchTextTimeout);
                    clearTimeout(searchTextTimeout);
                    let timeoutTemp = setTimeout(() => {
                        setTableFilter({
                            ...tableFilter,
                            search: e.target.value,
                            page: 1,
                        });
                    }, 1000);
                    setSearchTextTimeout(timeoutTemp);
                }}
            />
        </div>
    );
}

export function TableGlobalAlphaSearch(props) {
    const { tableFilter, setTableFilter, size, className } = props;
    const [active, setActive] = useState("");

    return (
        <div className={"flex table-filter-alphabet " + (className ?? "")}>
            {optionAlphabet.map((item, index) => (
                <Button
                    key={index}
                    type="link"
                    size={size ?? "large"}
                    onClick={() => {
                        setTableFilter({
                            ...tableFilter,
                            letter: item,
                            page: 1,
                        });
                        setActive(item);
                    }}
                    className={active === item ? "btn-main-outline" : ""}
                >
                    {item}
                </Button>
            ))}
        </div>
    );
}
