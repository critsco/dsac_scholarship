import { useEffect, useState } from "react";
import { Modal, Button, Form, notification } from "antd";
import validateRules from "../../../../providers/validateRules";
import { POST } from "../../../../providers/useAxiosQuery";
import FloatInput from "../../../../providers/FloatInput";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateUserRole, loading: loadingUserRole } = POST(
        `api/user_role`,
        "user_role_list"
    );

    const onFinish = (values) => {
        let data = {
            ...values,
            id:
                toggleModalForm.data && toggleModalForm.data.id
                    ? toggleModalForm.data.id
                    : "",
        };

        mutateUserRole(data, {
            onSuccess: (res) => {
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
                    notification.success({
                        message: "User Role",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "User Role",
                        description: res.message,
                    });
                }
            },
            onError: (err) => {
                notification.error({
                    message: "User Role",
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
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [toggleModalForm]);

    return (
        <Modal
            title="FORM USER ROLE"
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
                    loading={loadingUserRole}
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item name="type" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Type"
                        placeholder="Type"
                        required={true}
                    />
                </Form.Item>
            </Form>
            <Form form={form} onFinish={onFinish}>
                <Form.Item name="role" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Role"
                        placeholder="Role"
                        required={true}
                    />
                </Form.Item>
            </Form>
        </Modal>
    );
}
