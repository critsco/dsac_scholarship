import { useEffect } from "react";

import { Modal, Button, Form, notification, Select } from "antd";
import validateRules from "../../../../providers/validateRules";
import { POST } from "../../../../providers/useAxiosQuery";
import FloatInput from "../../../../providers/FloatInput";
import FloatTextArea from "../../../../providers/FloatTextArea";
import notificationErrors from "../../../../providers/notificationErrors";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateStatusCategory, loading: loadingStatusCategory } =
        POST(`api/ref_status_category`, "status_category_list");

    const onFinish = (values) => {
        let data = {
            ...values,
            id:
                toggleModalForm.data && toggleModalForm.data.id
                    ? toggleModalForm.data.id
                    : "",
        };

        mutateStatusCategory(data, {
            onSuccess: (res) => {
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
                    notification.success({
                        message: "Status Category",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "Status Category",
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
    }, [toggleModalForm]);

    return (
        <Modal
            title="FORM STATUS CATEGORY"
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
                    type="primary"
                    size="large"
                    onClick={() => form.submit()}
                    loading={loadingStatusCategory}
                    key={2}
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item
                    name="status_category"
                    rules={[validateRules.required()]}
                >
                    <FloatInput
                        label="Status Category"
                        placeholder="Status Category"
                        required={true}
                    />
                </Form.Item>
            </Form>
        </Modal>
    );
}
