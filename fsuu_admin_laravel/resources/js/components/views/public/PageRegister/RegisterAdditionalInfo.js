import React from "react";
import { Card, Typography, Col, Form, Input, Row, Checkbox } from "antd";
import validateRules from "../../../providers/validateRules";

export default function RegisterAdditionalInfo() {
    const checkboxQ1Heard = (checkboxQ1Heard) => {
        console.log("Selected Heard ", checkboxQ1Heard);
    };

    const checkboxInfluence = (checkedInfluence) => {
        console.log("Selected Influence:", checkedInfluence);
    };

    const checkboxQ2Factors = (checkedQ2Factors) => {
        console.log("Selected Factors:", checkedQ2Factors);
    };

    return (
        <Card>
            <div className="additional-content">
                <Col xs={24} sm={24} md={24} lg={24} className="left-side">
                    <Row gutter={[12, 0]}>
                        <Col className="Q1-wrapper">
                            <Form.Item
                                label="1. Have you ever heard about FATHER SATURNINO URIOS UNIVERSITY (FSUU) in the session conducted by the FSUU personnel in your school?"
                                className="degree-select"
                            >
                                <Checkbox.Group onChange={checkboxQ1Heard}>
                                    <Checkbox value="Yes">Yes</Checkbox>
                                    <Checkbox value="No">No</Checkbox>
                                </Checkbox.Group>
                            </Form.Item>
                            <Form.Item
                                label="If yes, Did it influence your decision to enroll in FATHER SATURNINO URIOS UNIVERSITY?"
                                className="working-select"
                            >
                                <Checkbox.Group onChange={checkboxInfluence}>
                                    <Checkbox value="Yes">Yes</Checkbox>
                                    <Checkbox value="No">No</Checkbox>
                                </Checkbox.Group>
                            </Form.Item>
                        </Col>
                        <Col className="Q2-wrapper">
                            <Form.Item
                                label="2. What were the factors that made you decide to
                                enroll at FATHER SATURNINO URIOS UNIVERSITY?"
                                className="accept-select w-50"
                            >
                                <Checkbox.Group onChange={checkboxQ2Factors}>
                                    <Checkbox value="A">
                                        A.) The university offers quality
                                        education
                                    </Checkbox>
                                    <Checkbox value="B">
                                        B.) Parent's choice
                                    </Checkbox>
                                    <Checkbox value="C">
                                        C.) It's near the residence
                                    </Checkbox>

                                    <div className="answer-D">
                                        <Checkbox value="D">
                                            D.) Other, please specify
                                        </Checkbox>
                                        <Input className="w-25"></Input>
                                    </div>
                                </Checkbox.Group>
                            </Form.Item>
                        </Col>
                    </Row>
                </Col>
            </div>
        </Card>
    );
}
