import { useEffect, useState } from "react";
import { Row, Button, Col, Table, notification, Popconfirm } from "antd";
import { DELETE, GET } from "../../../../providers/useAxiosQuery";
import {
	TableGlobalSearch,
	TablePageSize,
	TablePagination,
	TableShowingEntries,
} from "../../../../providers/CustomTableFilter";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPencil, faPlus, faTrash } from "@fortawesome/pro-regular-svg-icons";
import ModalForm from "./ModalForm";
import notificationErrors from "../../../../providers/notificationErrors";

export default function PageRate() {
	const [toggleModalForm, setToggleModalForm] = useState({
		open: false,
		data: null,
	});

	const [tableFilter, setTableFilter] = useState({
		page: 1,
		page_size: 50,
		search: "",
		sort_field: "name",
		sort_order: "asc",
	});

	const { data: dataSource, refetch: refetchSource } = GET(
		`api/ref_rate?${new URLSearchParams(tableFilter)}`,
		"rate_list"
	);

	const onChangeTable = (pagination, filters, sorter) => {
		setTableFilter((prevState) => ({
			...prevState,
			sort_field: sorter.columnKey,
			sort_order: sorter.order ? sorter.order.replace("end", "") : null,
			page: 1,
			page_size: "50",
		}));
	};

	useEffect(() => {
		if (dataSource) {
			refetchSource();
		}

		return () => {};
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [tableFilter]);

	const { mutate: mutateDeleteRate, loading: loadingDeleteRate } = DELETE(
		`api/ref_rate`,
		"rate_list"
	);

	const handleDelete = (record) => {
		mutateDeleteRate(record, {
			onSuccess: (res) => {
				console.log("res", res);
				if (res.success) {
					notification.success({
						message: "Rate",
						description: res.message,
					});
				} else {
					notification.error({
						message: "Rate",
						description: res.message,
					});
				}
			},
			onError: (err) => {
				notificationErrors(err);
			},
		});
	};

	return (
		<Row gutter={[12, 12]}>
			<Col xs={24} sm={24} md={24} lg={24} xl={24}>
				<Button
					className=" btn-main-primary btn-main-invert-outline b-r-none"
					icon={<FontAwesomeIcon icon={faPlus} />}
					onClick={() =>
						setToggleModalForm({
							open: true,
							data: null,
						})
					}
					size="large"
				>
					Add Rate
				</Button>
			</Col>

			<Col xs={24} sm={24} md={24} lg={24} xl={12}>
				<Row gutter={[12, 12]}>
					<Col xs={24} sm={24} md={24}>
						<div className="tbl-top-filter">
							<TablePageSize
								tableFilter={tableFilter}
								setTableFilter={setTableFilter}
							/>
							<TableGlobalSearch
								tableFilter={tableFilter}
								setTableFilter={setTableFilter}
							/>
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
						>
							<Table.Column
								title="Action"
								key="action"
								dataIndex="action"
								align="center"
								width={150}
								render={(text, record) => {
									return (
										<>
											<Button
												type="link"
												className="color-1"
												onClick={() =>
													setToggleModalForm({
														open: true,
														data: record,
													})
												}
											>
												<FontAwesomeIcon icon={faPencil} />
											</Button>
											<Popconfirm
												title="Are you sure to delete this data?"
												onConfirm={() => {
													handleDelete(record);
												}}
												onCancel={() => {
													notification.error({
														message: "Rate",
														description: "Data not deleted",
													});
												}}
												okText="Yes"
												cancelText="No"
											>
												<Button
													type="link"
													className="text-danger"
													loading={loadingDeleteRate}
												>
													<FontAwesomeIcon icon={faTrash} />
												</Button>
											</Popconfirm>
										</>
									);
								}}
							/>

							<Table.Column
								title="Name"
								key="name"
								dataIndex="name"
								sorter={true}
								defaultSortOrder="ascend"
							/>

							<Table.Column
								title="Rate"
								key="rate"
								dataIndex="rate"
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
			</Col>

			<ModalForm
				toggleModalForm={toggleModalForm}
				setToggleModalForm={setToggleModalForm}
			/>
		</Row>
	);
}
