import React from "react";
import { Card, Typography, Col, Form, Input, Checkbox, Row } from "antd";
import validateRules from "../../../providers/validateRules";

export default function RegisterAcademicProfile() {
    const checkboxDegree = (checkedDegrees) => {
        console.log("Selected Degree:", checkedDegrees);
    };

    const checkboxWorking = (checkedWorking) => {
        console.log("Selected Working:", checkedWorking);
    };

    const checkboxAccept = (checkedAccept) => {
        console.log("Selected Accept:", checkedAccept);
    };

    const checkboxAttend = (checkedAttend) => {
        console.log("Selected Attend:", checkedAttend);
    };

    return (
        <Card>
            <div className="academic-content">
                <Col xs={24} sm={24} md={24} lg={24} className="left-side">
                    <Row gutter={[12, 0]}>
                        <Col className="second-courses-wrapper w-100">
                            <div className="sub-title">
                                <Typography.Title level={3} italic>
                                    *For Second Course Only
                                </Typography.Title>

                                <Typography.Title
                                    level={5}
                                    type="secondary"
                                    italic
                                    className="mt-5 ml-15"
                                >
                                    (Graduate in 2 or 4/5 year course)
                                </Typography.Title>
                            </div>
                            <Form.Item
                                label="What degree program do you intend to pursue?"
                                className="degree-select ml-50"
                            >
                                <Checkbox.Group onChange={checkboxDegree}>
                                    <Checkbox value="Graduate">
                                        Graduate
                                    </Checkbox>
                                    <Checkbox value="Baccalaureate">
                                        Baccalaureate
                                    </Checkbox>
                                    <Checkbox value="Law">Law</Checkbox>
                                    <Checkbox value="Tech-Voc">
                                        Tech-Voc
                                    </Checkbox>
                                </Checkbox.Group>
                            </Form.Item>
                            <Form.Item
                                label="Are you a working student?"
                                className="working-select ml-50"
                            >
                                <Checkbox.Group onChange={checkboxWorking}>
                                    <Checkbox value="Yes">Yes</Checkbox>
                                    <Checkbox value="No">No</Checkbox>
                                </Checkbox.Group>
                            </Form.Item>

                            <Form.Item
                                label="If yes, give name and address of most recent employer."
                                className="w-75 ml-50"
                            >
                                <Input size="large" required={true} />
                            </Form.Item>
                        </Col>

                        <Col className="transferee-wrapper w-100">
                            <Typography.Title level={3} italic>
                                For Transferee Only
                            </Typography.Title>

                            <Typography className="ml-50">
                                Please list all universities and/ or colleges
                                you have attended and year attended. Failure to
                                disclose previous attendance at another
                                institution could result to academic dismissal.
                            </Typography>

                            <Col className="school-wrapper">
                                <Form.Item
                                    label="Name of School"
                                    className="w-50 ml-50"
                                >
                                    <Input size="large" required={true} />
                                </Form.Item>
                                <Form.Item
                                    label="Year Attended"
                                    className="w-25 ml-20"
                                >
                                    <Input size="large" required={true} />
                                </Form.Item>
                            </Col>

                            <Typography className="ml-50">
                                Have you ever applied to FATHER SATURNINO URIOS
                                UNIVERSITY before?
                            </Typography>

                            <Col className="accepted-wrapper">
                                <Form.Item
                                    label="Were you Accepted?"
                                    className="accept-select w-50 ml-50"
                                >
                                    <Checkbox.Group onChange={checkboxAccept}>
                                        <Checkbox value="Yes">Yes</Checkbox>
                                        <Checkbox value="No">No</Checkbox>
                                    </Checkbox.Group>
                                </Form.Item>

                                <Form.Item label="Year of Application">
                                    <Input size="large" required={true} />
                                </Form.Item>
                            </Col>

                            <Col className="attended-wrapper">
                                <Form.Item
                                    label="Did you attend?"
                                    className="attend-select w-50 ml-50"
                                >
                                    <Checkbox.Group onChange={checkboxAttend}>
                                        <Checkbox value="Yes">Yes</Checkbox>
                                        <Checkbox value="No">No</Checkbox>
                                    </Checkbox.Group>
                                </Form.Item>

                                <Form.Item label="Year Attended">
                                    <Input size="large" required={true} />
                                </Form.Item>
                            </Col>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
