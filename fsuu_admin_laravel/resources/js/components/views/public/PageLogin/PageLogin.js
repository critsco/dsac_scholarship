import React, { useState } from "react";
import { Layout, Typography, Card, Alert, Form, Button } from "antd";

import { POST } from "../../../providers/useAxiosQuery";
import { logo } from "../../../providers/companyInfo";
import { encrypt } from "../../../providers/companyInfo";
import { date, description } from "../../../providers/companyInfo";
import FloatInput from "../../../providers/FloatInput";
import FloatInputPassword from "../../../providers/FloatInputPassword";
import validateRules from "../../../providers/validateRules";

export default function PageLogin() {
    const [errorMessageLogin, setErrorMessageLogin] = useState({
        type: "",
        message: "",
    });

    const { mutate: mutateLogin, isLoading: isLoadingButtonLogin } = POST(
        "api/login",
        "login"
    );

    const onFinishLogin = (values) => {
        mutateLogin(values, {
            onSuccess: (res) => {
                // console.log("res", res);
                if (res.data) {
                    localStorage.userdata = encrypt(JSON.stringify(res.data));
                    localStorage.token = res.token;
                    window.location.reload();
                } else {
                    setErrorMessageLogin({
                        type: "error",
                        message: res.message,
                    });
                }
            },
            onError: (err) => {
                setErrorMessageLogin({
                    type: "error",
                    message: (
                        <>
                            Unrecognized username or password.{" "}
                            <b>Forgot your password?</b>
                        </>
                    ),
                });
            },
        });
    };

    return (
        <Layout.Content>
            <div className="main-content">
                <div className="left-content">
                    <div className="content-wrapper">
                        <div className="logo-wrapper zoom-in-out-box-1">
                            <img src={logo} alt="" />
                        </div>

                        <div className="title">
                            <div className="name">FSUU</div>
                            <div className="description">
                                Father Saturnino Urios University
                            </div>
                        </div>
                    </div>
                </div>

                <div className="right-content">
                    <Card className="card-login">
                        <Typography.Title
                            level={4}
                            className="mt-0 mb-20 text-center"
                        >
                            Account Login
                        </Typography.Title>

                        <Form onFinish={onFinishLogin} autoComplete="off">
                            <Form.Item
                                name="email"
                                rules={[validateRules.required()]}
                            >
                                <FloatInput
                                    label="Username / E-mail"
                                    placeholder="Username / E-mail"
                                />
                            </Form.Item>

                            <Form.Item
                                name="password"
                                rules={[validateRules.required()]}
                            >
                                <FloatInputPassword
                                    label="Password"
                                    placeholder="Password"
                                />
                            </Form.Item>

                            <Button
                                type="primary"
                                htmlType="submit"
                                loading={isLoadingButtonLogin}
                                className="mt-10 mb-10 btn-log-in"
                                block
                            >
                                Log In
                            </Button>

                            {/* <Typography.Link href="login" target="_blank" italic>
						Forgot Password?
					</Typography.Link> */}

                            {errorMessageLogin.message && (
                                <Alert
                                    className="mt-10"
                                    type={errorMessageLogin.type}
                                    message={errorMessageLogin.message}
                                />
                            )}
                        </Form>
                    </Card>
                </div>
            </div>

            <Layout.Footer>
                <Typography.Text>
                    {`© ${date.getFullYear()} ${description}. All Rights
                        Reserved.`}
                </Typography.Text>
            </Layout.Footer>
        </Layout.Content>
    );
}
