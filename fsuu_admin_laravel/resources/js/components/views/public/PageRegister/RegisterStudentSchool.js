import React, { useState } from "react";
import { Col, Row, Form, Card, Input, Radio, Typography } from "antd";
import validateRules from "../../../providers/validateRules";

export default function RegisterStudentProfile1() {
    const radioType = (e) => {
        const selectedType = e.target.value;
        console.log("Selected Type:", selectedType);
    };

    return (
        <Card>
            <div className="student-content student-school-content">
                <Col xs={24} sm={24} md={24} lg={24} className="left-side">
                    <Row gutter={[12, 0]}>
                        <Typography.Title level={5}>
                            School Last Attended in Elementary
                        </Typography.Title>
                        <Col className="elementary-wrapper">
                            <Form.Item
                                name="school_name school-name-elementary"
                                rules={[validateRules.required()]}
                                label="Name of School"
                            >
                                <Input
                                    size="large"
                                    placeholder="Name of School"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="school_address school-address-elementary"
                                rules={[validateRules.required()]}
                                label="Address"
                            >
                                <Input
                                    size="large"
                                    placeholder="Address"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="year_graduated year-graduated-elementary"
                                rules={[validateRules.required()]}
                                label="Year Graduated"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>

                            <div className="school-type-elementary">
                                <Form.Item
                                    label="Type of School"
                                    className="school-type-elementary-item w-100"
                                >
                                    <Radio.Group onChange={radioType}>
                                        <Radio
                                            className="radio-box"
                                            value="Private"
                                        >
                                            Private
                                        </Radio>
                                        <Radio
                                            className="radio-box"
                                            value="Public"
                                        >
                                            Public
                                        </Radio>
                                    </Radio.Group>
                                </Form.Item>
                            </div>
                        </Col>

                        <Typography.Title level={5}>
                            School Last Attended in Junior High School
                        </Typography.Title>
                        <Col className="jhs-wrapper">
                            <Form.Item
                                name="school_name school-name-jhs"
                                rules={[validateRules.required()]}
                                label="Name of School"
                            >
                                <Input
                                    size="large"
                                    placeholder="Name of School"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="school_address school-address-jhs"
                                rules={[validateRules.required()]}
                                label="Address"
                            >
                                <Input
                                    size="large"
                                    placeholder="Address"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="year_graduated year-graduated-jhs"
                                rules={[validateRules.required()]}
                                label="Year Graduated"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>

                            <div className="school-type-jhs">
                                <Form.Item
                                    label="Type of School"
                                    className="school-type-jhs-item w-100"
                                >
                                    <Radio.Group onChange={radioType}>
                                        <Radio
                                            className="radio-box"
                                            value="Private"
                                        >
                                            Private
                                        </Radio>
                                        <Radio
                                            className="radio-box"
                                            value="Public"
                                        >
                                            Public
                                        </Radio>
                                    </Radio.Group>
                                </Form.Item>
                            </div>
                        </Col>
                    </Row>

                    <Row gutter={[12, 0]}>
                        <Typography.Title level={5}>
                            School Last Attended in Senior High School
                        </Typography.Title>
                        <Col className="shs-wrapper">
                            <Form.Item
                                name="school_name school-name-shs"
                                rules={[validateRules.required()]}
                                label="Name of School"
                            >
                                <Input
                                    size="large"
                                    placeholder="Name of School"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="school_address school-address-shs"
                                rules={[validateRules.required()]}
                                label="Address"
                            >
                                <Input
                                    size="large"
                                    placeholder="Address"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="year_graduated year-graduated-shs"
                                rules={[validateRules.required()]}
                                label="Year Graduated"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>

                            <div className="school-type-shs">
                                <Form.Item
                                    label="Type of School"
                                    className="school-type-shs-item w-100"
                                >
                                    <Radio.Group onChange={radioType}>
                                        <Radio
                                            className="radio-box"
                                            value="Private"
                                        >
                                            Private
                                        </Radio>
                                        <Radio
                                            className="radio-box"
                                            value="Public"
                                        >
                                            Public
                                        </Radio>
                                    </Radio.Group>
                                </Form.Item>
                            </div>
                        </Col>

                        <Typography.Title level={5}>
                            School Last Attended in College and Master’s Degree
                        </Typography.Title>
                        <Col className="college-wrapper">
                            <Form.Item
                                name="school_name school-name-college"
                                rules={[validateRules.required()]}
                                label="Name of School"
                            >
                                <Input
                                    size="large"
                                    placeholder="Name of School"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="school_address school-address-college"
                                rules={[validateRules.required()]}
                                label="Address"
                            >
                                <Input
                                    size="large"
                                    placeholder="Address"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="year_graduated year-graduated-college"
                                rules={[validateRules.required()]}
                                label="Year Graduated"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>

                            <div className="school-type-college">
                                <Form.Item
                                    label="Type of School"
                                    className="school-type-college-item w-100"
                                >
                                    <Radio.Group onChange={radioType}>
                                        <Radio
                                            className="radio-box"
                                            value="Private"
                                        >
                                            Private
                                        </Radio>
                                        <Radio
                                            className="radio-box"
                                            value="Public"
                                        >
                                            Public
                                        </Radio>
                                    </Radio.Group>
                                </Form.Item>
                            </div>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
