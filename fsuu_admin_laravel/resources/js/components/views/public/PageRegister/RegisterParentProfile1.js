import React from "react";
import {
    Card,
    Typography,
    Col,
    Form,
    Input,
    Row,
    Select,
    Checkbox,
} from "antd";
import validateRules from "../../../providers/validateRules";
import { DatePicker } from "antd/lib";

export default function RegisterParentProfile1() {
    const checkboxSponsor = (checkedSponsors) => {
        console.log("Selected Sponsors:", checkedSponsors);
    };

    const checkboxSponsorIncome = (checkedSponsorIncome) => {
        console.log("Selected Sponsor Income:", checkedSponsorIncome);
    };

    return (
        <Card>
            <div className="parent-content parent-contact">
                <Col xs={24} sm={24} md={24} lg={12} className="left-side">
                    <Row gutter={[12, 0]}>
                        <>
                            <Typography.Title
                                level={3}
                                type="danger"
                                className="w-100"
                            >
                                Person to Contact in Case of Emergency
                            </Typography.Title>
                        </>
                        <Typography.Title level={5}>Guardian</Typography.Title>
                        <Col className="guardian-wrapper">
                            <Form.Item
                                name="lastname guardian-lastname"
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
                                name="firstname guardian-firstname"
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
                                name="middlename guardian-middlename"
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

                        <Col className="relationship-wrapper">
                            <Form.Item
                                name="relationship guardian-relationship"
                                rules={[validateRules.required()]}
                                label="Relationship"
                            >
                                <Select
                                    size="large"
                                    placeholder="Select Relationship"
                                />
                            </Form.Item>

                            <Form.Item
                                name="relationship guardian-relationship-specify"
                                rules={[validateRules.required()]}
                                label="If others, please specify"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>
                        </Col>

                        <Typography.Title level={5}>Spouse</Typography.Title>
                        <Col className="spouse-wrapper">
                            <Form.Item
                                name="lastname spouse-lastname"
                                label="Maiden Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Maiden Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="firstname spouse-firstname"
                                label="First Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="First Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="middlename spouse-middlename"
                                label="Middle Name"
                            >
                                <Input
                                    size="large"
                                    placeholder="Middle Name"
                                    required={true}
                                />
                            </Form.Item>

                            <Form.Item
                                name="occupation spouse-occupation"
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

                <Col
                    xs={24}
                    sm={24}
                    md={24}
                    lg={12}
                    className="right-side mt-75"
                >
                    <Row gutter={[12, 0]}>
                        <Typography.Title level={5} className="mt-25">
                            For Insurance Purposes
                        </Typography.Title>

                        <Col className="sponsor-wrapper">
                            <Form.Item
                                label="Who support or sponsor studies(not any scholarship grant)"
                                className="sponsor-select"
                            >
                                <Checkbox.Group onChange={checkboxSponsor}>
                                    <Checkbox value="Father">Father</Checkbox>
                                    <Checkbox value="Mother">Mother</Checkbox>
                                    <Checkbox value="GrandFather">
                                        GrandFather
                                    </Checkbox>
                                    <Checkbox value="GrandMother">
                                        GrandMother
                                    </Checkbox>
                                    <Checkbox value="Uncle">Uncle</Checkbox>
                                    <Checkbox value="Aunt">Aunt</Checkbox>
                                    <Checkbox value="Brother">Brother</Checkbox>
                                    <Checkbox value="Sister">Sister</Checkbox>
                                </Checkbox.Group>
                            </Form.Item>

                            <Form.Item className="custom-label" label="Others">
                                <Input
                                    size="large"
                                    placeholder="Please specify..."
                                />
                            </Form.Item>
                        </Col>

                        <Col className="income-wrapper">
                            <Form.Item
                                label="Monthly Income of Parent/ Sponsor"
                                className="income-select"
                            >
                                <Checkbox.Group
                                    onChange={checkboxSponsorIncome}
                                >
                                    <Checkbox value="10000">
                                        10,000 and below
                                    </Checkbox>
                                    <Checkbox value="10000-20000">
                                        10,000 - 20,000
                                    </Checkbox>
                                    <Checkbox value="20001">
                                        20,001 and above
                                    </Checkbox>
                                </Checkbox.Group>
                            </Form.Item>
                        </Col>

                        <Col className="beneficiary-wrapper">
                            <Form.Item label="Name of Insurance Beneficiary/ Breadwinner">
                                <Form.Item
                                    name="lastname beneficiary-lastname"
                                    label="Maiden Name"
                                >
                                    <Input
                                        size="large"
                                        placeholder="Maiden Name"
                                        required={true}
                                    />
                                </Form.Item>

                                <Form.Item
                                    name="firstname beneficiary-firstname"
                                    label="First Name"
                                >
                                    <Input
                                        size="large"
                                        placeholder="First Name"
                                        required={true}
                                    />
                                </Form.Item>

                                <Form.Item
                                    className="mb-0"
                                    name="middlename beneficiary-middlename"
                                    label="Middle Name"
                                >
                                    <Input
                                        size="large"
                                        placeholder="Middle Name"
                                        required={true}
                                    />
                                </Form.Item>
                            </Form.Item>
                        </Col>

                        <Col className="birth-wrapper">
                            <Form.Item className="date" label="Date of Birth">
                                <>
                                    <DatePicker
                                        size="large"
                                        placeholder="Date"
                                        format={"MM/DD/YYYY"}
                                    />
                                    <p className="date-format w-100">
                                        MM/DD/YYYY
                                    </p>
                                </>
                            </Form.Item>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
