import { useState } from "react";
import { Modal, Button, Form, notification } from "antd";
import FloatInput from "../../../../providers/FloatInput";
import validateRules from "../../../../providers/validateRules";
import { POST } from "../../../../providers/useAxiosQuery";
import { useEffect } from "react";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateSubject, loading: loadingSubject } = POST(
        `api/ref_subject`,
        "subject_list"
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

        mutateSubject(data, {
            onSuccess: (res) => {
                console.log("res", res);
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
                    notification.success({
                        message: "Subject",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "Subject",
                        description: res.message,
                    });
                }
            },
            onError: (err) => {
                notification.error({
                    message: "Subject",
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
            title="FORM SUBJECT"
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
                    loading={loadingSubject}
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item name="code" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Subject Code"
                        placeholder="Subject Code"
                        required={true}
                    />
                </Form.Item>
                <Form.Item name="name" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Subject Name"
                        placeholder="Subject Name"
                        required={true}
                    />
                </Form.Item>
                <Form.Item
                    name="description"
                    rules={[validateRules.required()]}
                >
                    <FloatInput label="description" placeholder="description" />
                </Form.Item>
            </Form>
        </Modal>
    );
}
