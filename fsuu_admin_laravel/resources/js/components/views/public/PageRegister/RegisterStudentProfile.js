import React, { useState } from "react";
import { Col, Form, Select, Card, Input, Checkbox, Radio, Row } from "antd";
import { DatePicker } from "antd/lib";
import validateRules from "../../../providers/validateRules";

export default function RegisterStudentProfile() {
    const checkboxLanguage = (checkedLanguages) => {
        console.log("Selected Languages:", checkedLanguages);
    };

    const radioGender = (e) => {
        const selectedGender = e.target.value;
        console.log("Selected Gender:", selectedGender);
    };

    return (
        <Card>
            <div className="student-content">
                <Col xs={24} sm={24} md={24} lg={12} className="left-side">
                    <Row gutter={[12, 0]}>
                        <Col className="name-wrapper">
                            <Form.Item
                                name="lastname"
                                rules={[validateRules.required()]}
                                label="Family Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Family Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="firstname"
                                rules={[validateRules.required()]}
                                label="Given Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Given Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="middlename"
                                rules={[validateRules.required()]}
                                label="Middle Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Middle Name"
                                    required={true}
                                />
                            </Form.Item>
                        </Col>
                        <Col className="birth-wrapper ">
                            <Form.Item label="Date of Birth">
                                <DatePicker
                                    size="large"
                                    placeholder="Date"
                                    format={"MM/DD/YYYY"}
                                />
                                <p className="date-format">MM/DD/YYYY</p>
                            </Form.Item>
                            <Form.Item
                                name="birth-place"
                                rules={[validateRules.required()]}
                                label="Place of Birth"
                                className="birth-place "
                            >
                                <>
                                    <Input
                                        size="large"
                                        placeholder="Place of Birth"
                                        required={true}
                                    />
                                </>
                            </Form.Item>
                        </Col>

                        <Col className="contact_information">
                            <Form.Item
                                autoComplete="off"
                                name="email"
                                rules={[validateRules.required()]}
                                label="Email"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>

                            <Form.Item
                                autoComplete="off"
                                name="contact_number student_contact_number"
                                rules={[validateRules.required()]}
                                label="Contact Number"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>
                        </Col>
                    </Row>
                </Col>

                <Col xs={24} sm={24} md={24} lg={12} className="right-side">
                    <Row gutter={[12, 0]}>
                        <Col className="more-info">
                            <div className="religion-nationality">
                                <Form.Item
                                    name="religion"
                                    rules={[validateRules.required()]}
                                    label="Religion"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Religion"
                                    />
                                </Form.Item>

                                <Form.Item
                                    name="nationality_id"
                                    rules={[validateRules.required()]}
                                    label="Nationality"
                                >
                                    <Select
                                        size="large"
                                        placeholder="Nationality"
                                    />
                                </Form.Item>
                            </div>

                            <Form.Item
                                name="civil_status"
                                rules={[validateRules.required()]}
                                label="Civil Status"
                            >
                                <Select
                                    size="large"
                                    placeholder="Civil Status"
                                />
                            </Form.Item>
                        </Col>
                        <Col className="language-wrapper">
                            <Form.Item
                                label="Languages Spoken"
                                className="w-50"
                            >
                                <Checkbox.Group onChange={checkboxLanguage}>
                                    <Checkbox value="English">English</Checkbox>
                                    <Checkbox value="Filipino">
                                        Filipino
                                    </Checkbox>
                                    <Checkbox value="Bisaya">Bisaya</Checkbox>
                                </Checkbox.Group>
                            </Form.Item>

                            <Form.Item className="custom-label">
                                <div style={{ padding: "5px 10px 0px" }}>
                                    Others:
                                </div>
                                <div style={{ width: "100%" }}>
                                    <Input
                                        size="large"
                                        placeholder="Please specify..."
                                    />
                                </div>
                            </Form.Item>
                        </Col>
                        <Col className="gender-bloodtype">
                            <div className="gender-wrapper">
                                <Form.Item label="Gender" className="w-100">
                                    <Radio.Group onChange={radioGender}>
                                        <Radio
                                            className="radio-box"
                                            value="Male"
                                        >
                                            Male
                                        </Radio>
                                        <Radio
                                            className="radio-box"
                                            value="Female"
                                        >
                                            Female
                                        </Radio>
                                    </Radio.Group>
                                </Form.Item>
                            </div>

                            <Form.Item
                                className="w-50"
                                name="blood_type"
                                label="Blood Type"
                            >
                                <Select
                                    size="large"
                                    placeholder="Select Blood Type"
                                />
                            </Form.Item>
                        </Col>

                        <Col className="height-weight mt-15">
                            <Form.Item
                                autoComplete="off"
                                name="height"
                                rules={[validateRules.required()]}
                                label="Height"
                            >
                                <Input
                                    size="large"
                                    placeholder="' ''"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                autoComplete="off"
                                name="weight"
                                rules={[validateRules.required()]}
                                label="Weight"
                            >
                                <Input
                                    size="large"
                                    placeholder="kg"
                                    required={true}
                                />
                            </Form.Item>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
