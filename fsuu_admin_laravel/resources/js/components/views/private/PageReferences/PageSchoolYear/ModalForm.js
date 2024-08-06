import { useEffect } from "react";
import { Modal, Button, Form, notification, DatePicker } from "antd";

import { POST } from "../../../../providers/useAxiosQuery";
import notificationErrors from "../../../../providers/notificationErrors";

export default function ModalForm(props) {
	const { toggleModalForm, setToggleModalForm } = props;

	const [form] = Form.useForm();
	const { RangePicker } = DatePicker;

	const { mutate: mutateSchoolYear, loading: loadingSchoolYear } = POST(
		`api/ref_school_year`,
		"school_year_list"
	);

	const onFinish = (values) => {
		console.log("onFinish", values);

		const sy_from = values.schoolYear[0].format("YYYY");
		const sy_to = values.schoolYear[1].format("YYYY");

		let data = {
			...values,
			sy_from,
			sy_to,
			id:
				toggleModalForm.data && toggleModalForm.data.id
					? toggleModalForm.data.id
					: "",
		};

		console.log("data", data);
		console.log("sy_from", sy_from);
		console.log("sy_to", sy_to);

		mutateSchoolYear(data, {
			onSuccess: (res) => {
				console.log("res", res);
				if (res.success) {
					setToggleModalForm({
						open: false,
						data: null,
					});
					form.resetFields();
					notification.success({
						message: "School Year",
						description: res.message,
					});
				} else {
					notification.error({
						message: "School Year",
						description: res.message,
					});
				}
			},
			onError: (err) => {
				notificationErrors(err);
			},
		});
	};

	useEffect(() => {
		if (toggleModalForm.open) {
			form.setFieldsValue({
				...toggleModalForm.data,
			});
		}

		return () => {};
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [toggleModalForm]);

	return (
		<Modal
			title="FORM School Year"
			open={toggleModalForm.open}
			onCancel={() => {
				setToggleModalForm({
					open: false,
					data: null,
				});
				form.resetFields();
			}}
			forceRender
			footer={[
				<Button
					className="btn-main-primary outlined"
					size="large"
					key={1}
					onClick={() => {
						setToggleModalForm({
							open: false,
							data: null,
						});
						form.resetFields();
					}}
				>
					CANCEL
				</Button>,
				<Button
					className="btn-main-primary"
					type="primary"
					size="large"
					key={2}
					onClick={() => form.submit()}
					loading={loadingSchoolYear}
				>
					SUBMIT
				</Button>,
			]}
		>
			<Form form={form} onFinish={onFinish}>
				<Form.Item
					name="schoolYear"
					rules={[
						{
							required: true,
							validator: (_, value) => {
								const currentYear = new Date().getFullYear();
								const sy_from = parseInt(value[0].format("YYYY"));
								const sy_to = parseInt(value[1].format("YYYY"));

								if (
									isNaN(sy_from) ||
									isNaN(sy_to) ||
									sy_from < currentYear ||
									sy_to < currentYear ||
									sy_from > 2100 ||
									sy_to > 2100
								) {
									return Promise.reject("Invalid Input");
								}
								return Promise.resolve();
							},
						},
					]}
				>
					<RangePicker
						picker="year"
						size="large"
						disabledDate={(current) =>
							current && current.year() < new Date().getFullYear()
						}
					/>
				</Form.Item>
			</Form>
		</Modal>
	);
}
