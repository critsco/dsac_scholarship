import { useEffect } from "react";
import { Modal, Button, Form, notification, Select } from "antd";
import FloatInput from "../../../../providers/FloatInput";
import validateRules from "../../../../providers/validateRules";
import { POST } from "../../../../providers/useAxiosQuery";
import notificationErrors from "../../../../providers/notificationErrors";
import FloatInputNumber from "../../../../providers/FloatInputNumber";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateRate, loading: loadingRate } = POST(
        `api/ref_rate`,
        "rate_list"
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

        mutateRate(data, {
            onSuccess: (res) => {
                console.log("res", res);
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
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
            title="FORM RATE"
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
                    loading={loadingRate}
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item name="name" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Name"
                        placeholder="Name"
                        required={true}
                    />
                </Form.Item>

                <Form.Item name="rate" rules={[validateRules.required()]}>
                    <FloatInputNumber
                        label="Rate"
                        placeholder="Rate"
                        required={true}
                    />
                </Form.Item>
            </Form>
        </Modal>
    );
}
