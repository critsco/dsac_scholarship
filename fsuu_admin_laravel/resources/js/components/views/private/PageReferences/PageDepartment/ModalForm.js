import { useState, useEffect } from "react";
import { Modal, Button, Form, notification, Select } from "antd";
import FloatInput from "../../../../providers/FloatInput";
import validateRules from "../../../../providers/validateRules";
import { POST } from "../../../../providers/useAxiosQuery";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateDepartment, loading: loadingDepartment } = POST(
        `api/ref_department`,
        "department_list"
    );

    const onFinish = (values) => {
        console.log("onFinish", values);

        let data = {
            ...values,
            id:
                toggleModalForm.data && toggleModalForm.data.id
                    ? toggleModalForm.data.id
                    : "",
        };

        mutateDepartment(data, {
            onSuccess: (res) => {
                console.log("res", res);
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
                    notification.success({
                        message: "Department",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "Department",
                        description: res.message,
                    });
                }
            },
            onError: (err) => {
                notification.error({
                    message: "Department",
                    description: "Something went wrong",
                });
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
    }, [toggleModalForm]);

    return (
        <Modal
            title="FORM DEPARTMENT"
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
                    onClick={() => {
                        setToggleModalForm({
                            open: false,
                            data: null,
                        });
                        form.resetFields();
                    }}
                    key={1}
                >
                    CANCEL
                </Button>,
                <Button
                    className="btn-main-primary"
                    size="large"
                    onClick={() => form.submit()}
                    loading={loadingDepartment}
                    key={2}
                    type="primary"
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item
                    name="department_name"
                    rules={[validateRules.required()]}
                >
                    <FloatInput
                        label="Department"
                        placeholder="Department"
                        required={true}
                    />
                </Form.Item>
            </Form>
        </Modal>
    );
}
