import { useEffect, useState } from "react";
import { Modal, Button, Form, notification } from "antd";
import validateRules from "../../../../providers/validateRules";
import { POST, GET } from "../../../../providers/useAxiosQuery";
import FloatInput from "../../../../providers/FloatInput";
import FloatSelect from "../../../../providers/FloatSelect";

export default function ModalForm(props) {
    const { toggleModalForm, setToggleModalForm } = props;

    const [form] = Form.useForm();

    const { mutate: mutateRoom, loading: loadingRoom } = POST(
        `api/ref_room`,
        "room_list"
    );

    const { data: dataBuildings } = GET(
        `api/ref_building`,
        "building_select",
        (res) => {},
        false
    );

    const { data: dataFloors } = GET(
        `api/ref_floor`,
        "floor_select",
        (res) => {},
        false
    );

    const onFinish = (values) => {
        let data = {
            ...values,
            id:
                toggleModalForm.data && toggleModalForm.data.id
                    ? toggleModalForm.data.id
                    : "",
        };

        mutateRoom(data, {
            onSuccess: (res) => {
                if (res.success) {
                    setToggleModalForm({
                        open: false,
                        data: null,
                    });
                    form.resetFields();
                    notification.success({
                        message: "Room",
                        description: res.message,
                    });
                } else {
                    notification.error({
                        message: "Room",
                        description: res.message,
                    });
                }
            },
            onError: (err) => {
                notification.error({
                    message: "Room",
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
            title="FORM ROOM"
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
                    loading={loadingRoom}
                >
                    SUBMIT
                </Button>,
            ]}
        >
            <Form form={form} onFinish={onFinish}>
                <Form.Item
                    name="building_id"
                    rules={[validateRules.required()]}
                >
                    <FloatSelect
                        label="Building"
                        placeholder="Building"
                        showSearch
                        options={
                            dataBuildings
                                ? dataBuildings.data.map((item) => {
                                      return {
                                          label: item.building,
                                          value: item.id,
                                      };
                                  })
                                : []
                        }
                        required={true}
                    />
                </Form.Item>
                <Form.Item name="floor_id" rules={[validateRules.required()]}>
                    <FloatSelect
                        label="Floor"
                        placeholder="Floor"
                        showSearch
                        options={
                            dataFloors
                                ? dataFloors.data.map((item, index) => {
                                      return {
                                          label: item.floor,
                                          value: item.id,
                                      };
                                  })
                                : []
                        }
                        required={true}
                    />
                </Form.Item>
                <Form.Item name="room_code" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Room Code"
                        placeholder="Room Code"
                        required={true}
                    />
                </Form.Item>

                <Form.Item name="max_slot" rules={[validateRules.required()]}>
                    <FloatInput
                        label="Max Slot"
                        placeholder="Max Slot"
                        required={true}
                    ></FloatInput>
                </Form.Item>
            </Form>
        </Modal>
    );
}
