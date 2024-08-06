import { Modal, Button, Form, Typography, Col, Row, Select } from "antd";
import React, { useEffect } from "react";
import FloatInput from "../../../providers/FloatInput";
import RegisterStudentProfile from "./RegisterStudentProfile";
import RegisterStudentSchool from "./RegisterStudentSchool";
import RegisterStudentAddress from "./RegisterStudentAddress";
import RegisterParentProfile from "./RegisterParentProfile";
import RegisterParentProfile1 from "./RegisterParentProfile1";
import RegisterAcademicProfile from "./RegisterAcademicProfile";
import RegisterAdditionalInfo from "./RegisterAdditionalInfo";

export default function ModalRegisterReview(props) {
    const { toggleModalRegisterReview, setToggleModalRegisterReview } = props;
    const [form] = Form.useForm();
    const { Text } = Typography;

    useEffect(() => {
        return () => {};
    }, [toggleModalRegisterReview]);

    return (
        <Modal
            className="ModalRegisterReview"
            open={toggleModalRegisterReview.open}
            onCancel={() => {
                setToggleModalRegisterReview({
                    open: false,
                    data: null,
                });
            }}
            footer={[
                <Button
                    className="btn-cancel outlined"
                    size="large"
                    key={1}
                    onClick={() => {
                        setToggleModalRegisterReview({
                            open: false,
                            data: null,
                        });
                    }}
                >
                    CANCEL
                </Button>,
                <Button
                    className="btn-save"
                    type="primary"
                    size="large"
                    key={2}
                    onClick={() => form.submit()}
                >
                    Save
                </Button>,
            ]}
        >
            <Form form={form} layout="vertical">
                <Row gutter={[12, 0]}>
                    <Col xs={24} sm={24} md={24} lg={24}>
                        <Typography.Title className="review-title mt-50 font-50px">
                            Review Application Information
                        </Typography.Title>

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
                                            className="w-100 "
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>
                            </div>
                        </Col>

                        <Col xs={24} sm={24} md={24} lg={24}>
                            <div className="applicant">
                                <Col xs={24} sm={12} md={12} lg={12}>
                                    <Text className="mt-10 font-20px" strong>
                                        Application ID:
                                    </Text>
                                    <>
                                        <Form.Item
                                            name="applicant_id"
                                            shouldUpdate
                                        >
                                            <FloatInput
                                                disabled
                                                rows="1"
                                                cols="30"
                                                placeholder="Applicant ID"
                                            />
                                        </Form.Item>
                                    </>
                                </Col>
                                <Col xs={24} sm={12} md={12} lg={12}>
                                    <Text className="mt-10 font-20px" strong>
                                        Application Name:
                                    </Text>
                                    <>
                                        <Form.Item name="fullname">
                                            <FloatInput
                                                disabled
                                                rows="1"
                                                cols="30"
                                                placeholder="Applicant Name"
                                            />
                                        </Form.Item>
                                    </>
                                </Col>
                            </div>
                        </Col>

                        <Col
                            xs={24}
                            sm={24}
                            md={24}
                            lg={24}
                            className="pl-0 w-100"
                        >
                            <div className="student-banner">
                                <p>Student Profile</p>
                            </div>
                            <Row gutter={[12, 0]}>
                                <RegisterStudentProfile />
                            </Row>
                        </Col>
                        <Col xs={24} sm={24} md={24} lg={24} className="pl-0">
                            <RegisterStudentSchool />
                        </Col>
                        <Col xs={24} sm={24} md={24} lg={24} className="pl-0">
                            <RegisterStudentAddress />
                        </Col>
                        <Col xs={24} sm={24} md={24} lg={24} className="pl-0">
                            <div className="parent-banner">
                                <p>Parent Profile</p>
                            </div>
                            <RegisterParentProfile />
                        </Col>
                        <Col xs={24} sm={24} md={24} lg={24} className="pl-0">
                            <RegisterParentProfile1 />
                        </Col>
                        <Col xs={24} sm={24} md={24} lg={24} className="pl-0">
                            {/* <div className="academic-footer">
                                <Typography.Text level={5} italic>
                                    If this section does not apply to you click
                                    continue
                                </Typography.Text>
                            </div> */}
                            <div className="academic-banner">
                                <p>Academic Profile</p>
                            </div>
                            <RegisterAcademicProfile />
                        </Col>
                        <Col xs={24} sm={24} md={24} lg={24} className="pl-0">
                            <div className="additional-banner">
                                <p>Additional Information</p>
                            </div>
                            <RegisterAdditionalInfo />
                        </Col>
                    </Col>
                </Row>
            </Form>
        </Modal>
    );
}
