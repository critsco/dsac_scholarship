import React from "react";
import { Card, Typography, Col, Row, Form, Input, Select } from "antd";
import validateRules from "../../../providers/validateRules";
import TextArea from "antd/lib/input/TextArea";

export default function RegisterParentProfile() {
    return (
        <Card>
            <div className="parent-content">
                <Col xs={24} sm={24} md={24} lg={12} className="left-side">
                    <Row gutter={[12, 0]}>
                        <Typography.Title level={5}>Mother</Typography.Title>
                        <Col className="mother-wrapper">
                            <Form.Item
                                name="lastname  mother-lastname "
                                rules={[validateRules.required()]}
                                label="Maiden Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Maiden Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="firstname mother-firstname"
                                rules={[validateRules.required()]}
                                label="First Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="First Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="middlename mother-middlename"
                                rules={[validateRules.required()]}
                                label="Middle Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Middle Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="occupation mother-occupation"
                                rules={[validateRules.required()]}
                                label="Occupation"
                            >
                                <Input
                                    size="large"
                                    placeholder="Occupation"
                                    required={true}
                                />
                            </Form.Item>
                        </Col>

                        <Typography.Title level={5}>Father</Typography.Title>
                        <Col className="father-wrapper">
                            <Form.Item
                                name="lastname father-lastname"
                                rules={[validateRules.required()]}
                                label="Maiden Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Maiden Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="firstname father-firstname"
                                rules={[validateRules.required()]}
                                label="First Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="First Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="middlename father-middlename"
                                rules={[validateRules.required()]}
                                label="Middle Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Middle Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="occupation father-occupation"
                                rules={[validateRules.required()]}
                                label="Occupation"
                            >
                                <Input
                                    size="large"
                                    placeholder="Occupation"
                                    required={true}
                                />
                            </Form.Item>
                        </Col>
                    </Row>
                </Col>

                <Col xs={24} sm={24} md={24} lg={12} className="right-side">
                    <Row gutter={[12, 0]}>
                        <Typography.Title level={5}>Siblings</Typography.Title>
                        <Col className="siblings-wrapper">
                            <Form.Item
                                name="brothers"
                                rules={[validateRules.required()]}
                                label="Number of Brothers"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>

                            <Form.Item
                                name="sisters"
                                rules={[validateRules.required()]}
                                label="Number of Sisters"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>
                        </Col>

                        <Col className="address-wrapper">
                            <Form.Item
                                name="address"
                                rules={[validateRules.required()]}
                                label="Home Address"
                            >
                                <TextArea required={true} />
                            </Form.Item>

                            <div className="address-select">
                                <Form.Item
                                    name="region"
                                    rules={[validateRules.required()]}
                                    label="Region"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Select Region"
                                    />
                                </Form.Item>
                                <Form.Item
                                    name="province"
                                    rules={[validateRules.required()]}
                                    label="Province"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Select Province"
                                    />
                                </Form.Item>
                            </div>
                            <Form.Item
                                className="municipality"
                                name="municipality"
                                rules={[validateRules.required()]}
                                label="Municipality"
                            >
                                <Select
                                    size="large"
                                    placeholder="Select Municipality"
                                />
                            </Form.Item>
                        </Col>

                        <Col>
                            <Form.Item
                                name="contact_number parent_contact_number"
                                rules={[validateRules.required()]}
                                label="Contact Number"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>
                        </Col>

                        <Col>
                            <Form.Item
                                className="w-50"
                                name="zipcode"
                                rules={[validateRules.required()]}
                                label="Zip Code"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
