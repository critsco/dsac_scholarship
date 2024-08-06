import React from "react";
import { Col, Form, Select, Card, Row, Checkbox } from "antd";
import validateRules from "../../../providers/validateRules";
import TextArea from "antd/lib/input/TextArea";

export default function RegisterStudentAddress() {
    const checkboxAddress = (checkedAddress) => {
        console.log("Selected Address:", checkedAddress);
    };

    return (
        <Card>
            <div className="student-content student-address">
                <Col xs={24} sm={24} md={24} lg={24} className="left-side">
                    <Row gutter={[12, 0]}>
                        <Col className="address-wrapper present-address w-100">
                            <Form.Item
                                name="address address-present"
                                rules={[validateRules.required()]}
                                label="Present Address"
                            >
                                <TextArea required />
                            </Form.Item>

                            <div className="address-select">
                                <Form.Item
                                    name="region reigion-present"
                                    rules={[validateRules.required()]}
                                    label="Region"
                                >
                                    <Select size="large" placeholder="Region" />
                                </Form.Item>
                                <Form.Item
                                    name="province province-present"
                                    rules={[validateRules.required()]}
                                    label="Province"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Province"
                                    />
                                </Form.Item>
                                <Form.Item
                                    name="municipality municipality-present"
                                    rules={[validateRules.required()]}
                                    label="Municipality"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Municipality"
                                    />
                                </Form.Item>
                            </div>
                        </Col>

                        <Col className="same-address-wrapper mb-20 w-100">
                            <Form.Item className="w-50">
                                <Checkbox.Group onChange={checkboxAddress}>
                                    <Checkbox value="Same">
                                        Same as the Present Address
                                    </Checkbox>
                                </Checkbox.Group>
                            </Form.Item>
                        </Col>

                        <Col className="address-wrapper permanent-address w-100">
                            <Form.Item
                                name="address address-permanent"
                                rules={[validateRules.required()]}
                                label="Permanent Address"
                            >
                                <TextArea required={true} />
                            </Form.Item>

                            <div className="address-select">
                                <Form.Item
                                    name="region region-permanent"
                                    rules={[validateRules.required()]}
                                    label="Region"
                                >
                                    <Select size="large" placeholder="Region" />
                                </Form.Item>
                                <Form.Item
                                    name="province province-permanent"
                                    rules={[validateRules.required()]}
                                    label="Province"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Province"
                                    />
                                </Form.Item>
                                <Form.Item
                                    name="municipality municipality-permanent"
                                    rules={[validateRules.required()]}
                                    label="Municipality"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Municipality"
                                    />
                                </Form.Item>
                            </div>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
