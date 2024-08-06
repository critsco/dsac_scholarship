import { Form, Typography, Row, Col, Select, Button, theme } from "antd";
import React, { useState } from "react";
import RegisterStudentProfile from "./RegisterStudentProfile";
import RegisterParentProfile from "./RegisterParentProfile";
import RegisterAcademicProfile from "./RegisterAcademicProfile";
import RegisterAdditionalInfo from "./RegisterAdditionalInfo";
import RegisterStudentSchool from "./RegisterStudentSchool";
import RegisterParentProfile1 from "./RegisterParentProfile1";
import RegisterRigthStep from "./RegisterRigthStep";
import RegisterStudentAddress from "./RegisterStudentAddress";
import ModalRegisterReview from "./ModalRegisterReview";

export default function RegisterRightContent() {
    const [form] = Form.useForm();
    const { Text } = Typography;

    const { token } = theme.useToken();
    const [current, setCurrent] = useState(0);

    const steps = [
        {
            content: <RegisterStudentProfile />,
            status: current === 0 ? "process" : "wait",
        },
        {
            content: <RegisterStudentSchool />,
            status: current === 1 ? "process" : "wait",
        },
        {
            content: <RegisterStudentAddress />,
            status: current === 2 ? "process" : "wait",
        },
        {
            content: <RegisterParentProfile />,
            status: current === 3 ? "process" : "wait",
        },
        {
            content: <RegisterParentProfile1 />,
            status: current === 4 ? "process" : "wait",
        },
        {
            content: <RegisterAcademicProfile />,
            status: current === 5 ? "process" : "wait",
        },
        {
            content: <RegisterAdditionalInfo />,
            status: current === 6 ? "process" : "wait",
        },
    ];

    const next = () => {
        setCurrent(current + 1);
        console.log("current", current);
    };
    const prev = () => {
        setCurrent(current - 1);
        console.log("current", current);
    };

    const contentStyle = {
        lineHeight: "260px",
        textAlign: "center",
        color: token.colorTextTertiary,
        backgroundColor: token.colorFillAlter,
        borderRadius: token.borderRadiusLG,
        border: `1px dashed ${token.colorBorder}`,
        marginTop: 16,
    };

    const [toggleModalRegisterReview, setToggleModalRegisterReview] = useState({
        open: false,
    });

    return (
        <Form form={form} layout="vertical">
            <Row gutter={[12, 0]}>
                <Col xs={24} sm={24} md={24} lg={24}>
                    <Typography.Title>
                        <p className="title">Applicant Information</p>
                        <div className="sub-title">
                            <p className="sub-title1">Instructions</p>
                            1. Kindly type 'NA' in boxes where there are no
                            possible answers to the information being requested.
                            <br />
                            2. To maje use of the letter 'Ñ', please press ALT
                            white typing "165"; while for 'ñ', please press ALT
                            while type "164".
                        </div>
                    </Typography.Title>
                </Col>

                <Col xs={24} sm={24} md={24} lg={24}>
                    <div className="select-wrapper">
                        <Col xs={24} sm={12} md={12} lg={6}>
                            <Form.Item name="department_id">
                                <Select
                                    placeholder="Department"
                                    className="w-100"
                                    size="large"
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} md={12} lg={6}>
                            <Form.Item name="course_id">
                                <Select
                                    placeholder="Course"
                                    className="w-100"
                                    size="large"
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} md={12} lg={6}>
                            <Form.Item name="classified_id">
                                <Select
                                    placeholder="Classified"
                                    className="w-100"
                                    size="large"
                                />
                            </Form.Item>
                        </Col>
                    </div>
                </Col>

                <Col xs={24} sm={24} md={24} lg={24}>
                    <div className="applicant">
                        <Text strong>Application ID:</Text>

                        <Col xs={24} sm={12} md={12} lg={6}>
                            <Form.Item name="applicant_id" shouldUpdate>
                                <textarea
                                    disabled
                                    rows="1"
                                    cols="30"
                                    placeholder="Applicant ID"
                                />
                            </Form.Item>
                        </Col>

                        <Text strong>Application Name:</Text>
                        <Col xs={24} sm={12} md={12} lg={6}>
                            <Form.Item name="fullname">
                                <textarea
                                    disabled
                                    rows="1"
                                    cols="30"
                                    placeholder="Applicant Name"
                                />
                            </Form.Item>
                        </Col>
                    </div>
                </Col>

                <Col xs={24} sm={24} md={12} lg={12} className="banner-wrapper">
                    {current >= 0 && current <= 2 && (
                        <div className="student-banner">
                            <p>Student Profile</p>
                        </div>
                    )}
                    {current >= 3 && current <= 4 && (
                        <div className="parent-banner">
                            <p>Parent Profile</p>
                        </div>
                    )}
                    {current === 5 && (
                        <div className="academic-banner">
                            <p>Academic Profile</p>
                        </div>
                    )}
                    {current === 6 && (
                        <div className="additional-banner">
                            <p>Additional Information</p>
                        </div>
                    )}
                </Col>

                <Col
                    xs={24}
                    sm={24}
                    md={12}
                    lg={12}
                    className="progress-wrapper"
                >
                    <RegisterRigthStep current={current} />
                </Col>

                <Col
                    xs={24}
                    sm={24}
                    md={24}
                    lg={24}
                    className="progress-content"
                >
                    {steps[current].content}
                </Col>

                <Col
                    xs={24}
                    sm={24}
                    md={24}
                    lg={24}
                    className="progress-btn pr-75 mb-30 mt-20"
                >
                    {current === 5 && (
                        <div className="academic-footer">
                            <Typography.Text level={5} italic>
                                If this section does not apply to you click
                                continue
                            </Typography.Text>
                        </div>
                    )}
                    {current > 0 && (
                        <Button
                            className="btn-back"
                            onClick={() => {
                                prev();
                                const section = document.querySelector("body");
                                section.scrollIntoView({
                                    behavior: "smooth",
                                    block: "start",
                                });
                            }}
                        >
                            Back
                        </Button>
                    )}
                    {current < steps.length - 1 && (
                        <Button
                            className="btn-continue"
                            onClick={() => {
                                next();
                                const section = document.querySelector("body");
                                section.scrollIntoView({
                                    behavior: "smooth",
                                    block: "start",
                                });
                            }}
                        >
                            Continue
                        </Button>
                    )}
                    {current === steps.length - 1 && (
                        <Button
                            className="btn-done"
                            onClick={() =>
                                setToggleModalRegisterReview({ open: true })
                            }
                        >
                            Review
                        </Button>
                    )}
                </Col>

                {/* <Col>
                    <Button
                        className="btn-done"
                        onClick={() =>
                            setToggleModalRegisterReview({ open: true })
                        }
                    >
                        Review
                    </Button>
                </Col> */}

                <ModalRegisterReview
                    toggleModalRegisterReview={toggleModalRegisterReview}
                    setToggleModalRegisterReview={setToggleModalRegisterReview}
                />
            </Row>
        </Form>
    );
}
