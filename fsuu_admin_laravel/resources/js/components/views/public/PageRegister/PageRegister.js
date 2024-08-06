import React, { useState } from "react";
import { Layout, Typography } from "antd";
import { logo } from "../../../providers/companyInfo";
import RegisterRightContent from "./RegisterRightContent";

export default function PageRegister() {
    return (
        <Layout.Content>
            <div className="container">
                <div className="left">
                    <div className="menu-wrapper">
                        <div className="nav-tab">
                            <a href="/">Log In</a>
                        </div>
                        <div className="nav-tab active">
                            <div>Sign Up</div>
                        </div>
                    </div>

                    <div className="logo-container">
                        <div className="logo-wrapper zoom-in-out-box-1">
                            <img src={logo} />
                        </div>

                        <Typography.Title className="title">
                            FSUU
                            <p className="sub-title">
                                Father Saturnino Urios University
                            </p>
                        </Typography.Title>
                    </div>
                </div>

                <div className="right">
                    <RegisterRightContent />
                </div>
            </div>
        </Layout.Content>
    );
}
