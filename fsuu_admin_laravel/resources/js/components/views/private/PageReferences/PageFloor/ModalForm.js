import { useEffect } from "react";
import { Modal, Button, Form, notification } from "antd";
import FloatInput from "../../../../providers/FloatInput";
import validateRules from "../../../../providers/validateRules";
import { POST, GET } from "../../../../providers/useAxiosQuery";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateFloor, loading: loadingFloor } = POST(
        `api/ref_floor`,
        "floor_list"
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

        mutateFloor(data, {
            onSuccess: (res) => {
                console.log("res", res);
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
                    notification.success({
                        message: "Floor",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "Floor",
                        description: res.message,
                    });
                }
            },
            onError: (err) => {
                notification.error({
                    message: "Floor",
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
            title="FORM FLOOR"
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
                    loading={loadingFloor}
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item name="floor" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Floor Level"
                        placeholder="Floor Level"
                        required={true}
                    />
                </Form.Item>
            </Form>
        </Modal>
    );
}
