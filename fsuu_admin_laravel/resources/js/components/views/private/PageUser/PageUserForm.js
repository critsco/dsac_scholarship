import { useEffect, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import {
    Row,
    Col,
    Button,
    Form,
    Collapse,
    Image,
    Space,
    Upload,
    notification,
} from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faAngleDown,
    faAngleUp,
    faArrowLeft,
    faCamera,
} from "@fortawesome/pro-regular-svg-icons";
import ImgCrop from "antd-img-crop";

import { GET, POST } from "../../../providers/useAxiosQuery";
import { apiUrl, defaultProfile } from "../../../providers/companyInfo";
import FloatInput from "../../../providers/FloatInput";
import FloatSelect from "../../../providers/FloatSelect";
import FloatInputPassword from "../../../providers/FloatInputPassword";
import ModalFormEmail from "./components/ModalFormEmail";
import ModalFormPassword from "./components/ModalFormPassword";
import ModalUserUploadPictureForm from "./components/ModalUserUploadPictureForm";
import validateRules from "../../../providers/validateRules";
import imageFileToBase64 from "../../../providers/imageFileToBase64";
import notificationErrors from "../../../providers/notificationErrors";
import optionUserType from "../../../providers/optionUserType";
import optionGender from "../../../providers/optionGender";
import ModalUploadProfilePicture from "./components/ModalUploadProfilePicture";

// import Webcam from "react-webcam";

export default function PageUserForm() {
    const navigate = useNavigate();
    const location = useLocation();
    const params = useParams();

    const [form] = Form.useForm();
    const [formDisabled, setFormDisabled] = useState(true);

    const [dataRoles, setDataRoles] = useState([]);

    const { mutate: mutateUserRole } = POST(`api/users`, "users_info");

    const [toggleModalFormEmail, setToggleModalFormEmail] = useState({
        open: false,
        data: null,
    });

    const [toggleModalFormPassword, setToggleModalFormPassword] = useState({
        open: false,
        data: null,
    });

    const [
        toggleModalUserUploadPictureForm,
        setToggleModalUserUploadPictureForm,
    ] = useState({
        open: false,
        data: null,
    });

    const [fileList, setFileList] = useState({
        imageUrl: defaultProfile,
        loading: false,
        file: null,
    });

    const [
        toggleModalUploadProfilePicture,
        setToggleModalUploadProfilePicture,
    ] = useState({
        open: false,
        file: null,
        src: null,
        is_camera: null,
        fileName: null,
    });

    GET(
        `api/users/${params.id}`,
        ["users_info", "check_user_permission"],
        (res) => {
            if (res.data) {
                let data = res.data;

                let username = data.username;
                let email = data.email;
                let firstname = data.profile.firstname;
                let lastname = data.profile.lastname;

                let gender = data.profile.gender;

                if (
                    data.profile &&
                    data.profile.attachments &&
                    data.profile.attachments.length > 0
                ) {
                    let profileAttachments = data.profile.attachments.filter(
                        (f) => f.file_description === "Profile Picture"
                    );

                    if (profileAttachments.length > 0) {
                        setToggleModalUploadProfilePicture({
                            open: false,
                            file: null,
                            src: apiUrl(profileAttachments[0].file_path),
                            is_camera: null,
                            fileName: null,
                        });
                    }

                    //  = data.profile.attachments[0].gender;
                }

                form.setFieldsValue({
                    role: data.role,
                    username,
                    email,
                    firstname,
                    lastname,
                    gender,
                });
            }
        }
    );

    const onFinish = (values) => {
        console.log("onFinish values", values);

        let data = new FormData();
        data.append("id", params.id ? params.id : "");
        data.append("role", values.role);
        data.append("username", values.username);
        data.append("email", values.email);
        if (!params.id) {
            data.append("password", values.password);
        }
        data.append("firstname", values.firstname);
        data.append("lastname", values.lastname);

        data.append("gender", values.gender);

        if (fileList.file) {
            data.append("imagefile", fileList.file);
        }

        mutateUserRole(data, {
            onSuccess: (res) => {
                if (res.success) {
                    if (params.id) {
                        notification.success({
                            message: "User",
                            description: res.message,
                        });
                    } else {
                        notification.success({
                            message: "User",
                            description: res.message,
                        });
                        navigate("/users");
                    }
                }
            },
            onError: (err) => {
                notificationErrors(err);
            },
        });
    };

    useEffect(() => {
        const timer = setTimeout(() => {
            setFormDisabled(false);
        }, 1000);

        return () => {
            clearTimeout(timer);
        };
    }, []);

    return (
        <Row gutter={[12, 12]}>
            <Col sm={24} md={24} lg={24} xl={24} xxl={24}>
                <Button
                    className=" btn-main-primary btn-main-invert-outline b-r-none"
                    icon={<FontAwesomeIcon icon={faArrowLeft} />}
                    onClick={() => navigate(-1)}
                >
                    Back to list
                </Button>
            </Col>

            <Col sm={24} md={24} lg={24} xl={24} xxl={24}>
                <Form form={form} onFinish={onFinish}>
                    <Row gutter={[12, 12]}>
                        <Col sm={24} md={24} lg={14} xl={14} xxl={14}>
                            <Collapse
                                className="collapse-main-primary"
                                defaultActiveKey={["0", "1"]}
                                size="middle"
                                expandIcon={({ isActive }) => (
                                    <FontAwesomeIcon
                                        icon={
                                            isActive ? faAngleUp : faAngleDown
                                        }
                                    />
                                )}
                                items={[
                                    {
                                        key: "0",
                                        label: "ACCOUNT INFORMATION",
                                        children: (
                                            <Row gutter={[12, 12]}>
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={12}
                                                    xl={12}
                                                    xxl={12}
                                                >
                                                    <Form.Item
                                                        name="type"
                                                        rules={[
                                                            validateRules.required(),
                                                        ]}
                                                    >
                                                        <FloatSelect
                                                            label="Role"
                                                            placeholder="Role"
                                                            required={true}
                                                            options={
                                                                optionUserType
                                                            }
                                                            disabled={
                                                                formDisabled
                                                            }
                                                        />
                                                    </Form.Item>
                                                </Col>

                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={12}
                                                    xl={12}
                                                    xxl={12}
                                                >
                                                    <Form.Item
                                                        name="username"
                                                        rules={[
                                                            validateRules.required(),
                                                        ]}
                                                    >
                                                        <FloatInput
                                                            label="Username"
                                                            placeholder="Username"
                                                            required
                                                            disabled={
                                                                params.id
                                                                    ? true
                                                                    : formDisabled
                                                            }
                                                        />
                                                    </Form.Item>
                                                </Col>

                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={12}
                                                    xl={12}
                                                    xxl={12}
                                                >
                                                    <Form.Item
                                                        name="email"
                                                        rules={[
                                                            validateRules.required(),
                                                            validateRules.email,
                                                        ]}
                                                    >
                                                        <FloatInput
                                                            label="Email"
                                                            placeholder="Email"
                                                            required={true}
                                                            disabled={
                                                                params.id
                                                                    ? true
                                                                    : formDisabled
                                                            }
                                                        />
                                                    </Form.Item>
                                                </Col>

                                                {params.id ? null : (
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={12}
                                                        xl={12}
                                                        xxl={12}
                                                    >
                                                        <Form.Item
                                                            name="password"
                                                            rules={[
                                                                validateRules.required(),
                                                                validateRules.password,
                                                            ]}
                                                        >
                                                            <FloatInputPassword
                                                                label="Password"
                                                                placeholder="Password"
                                                                required={true}
                                                                autoComplete="new-password"
                                                                disabled={
                                                                    formDisabled
                                                                }
                                                            />
                                                        </Form.Item>
                                                    </Col>
                                                )}

                                                {params.id ? (
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={24}
                                                    >
                                                        <a
                                                            type="link"
                                                            className="color-1"
                                                            onClick={() =>
                                                                setToggleModalFormEmail(
                                                                    {
                                                                        open: true,
                                                                        data: {
                                                                            id: params.id,
                                                                        },
                                                                    }
                                                                )
                                                            }
                                                        >
                                                            Change Email
                                                        </a>
                                                    </Col>
                                                ) : null}

                                                {params.id ? (
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={12}
                                                        xl={12}
                                                        xxl={12}
                                                    >
                                                        <a
                                                            type="link"
                                                            className="color-1"
                                                            onClick={() =>
                                                                setToggleModalFormPassword(
                                                                    {
                                                                        open: true,
                                                                        data: {
                                                                            id: params.id,
                                                                        },
                                                                    }
                                                                )
                                                            }
                                                        >
                                                            Change Password
                                                        </a>
                                                    </Col>
                                                ) : null}
                                            </Row>
                                        ),
                                    },
                                    {
                                        key: "1",
                                        label: "PERSONAL INFORMATION",
                                        children: (
                                            <Row gutter={[12, 12]}>
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={12}
                                                    xl={12}
                                                    xxl={12}
                                                >
                                                    <Form.Item
                                                        name="firstname"
                                                        rules={[
                                                            validateRules.required(),
                                                        ]}
                                                    >
                                                        <FloatInput
                                                            label="First Name"
                                                            placeholder="First Name"
                                                            required={true}
                                                            disabled={
                                                                formDisabled
                                                            }
                                                            onBlur={() => {
                                                                if (params.id) {
                                                                    form.submit();
                                                                }
                                                            }}
                                                        />
                                                    </Form.Item>
                                                </Col>

                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={12}
                                                    xl={12}
                                                    xxl={12}
                                                >
                                                    <Form.Item
                                                        name="lastname"
                                                        rules={[
                                                            validateRules.required(),
                                                        ]}
                                                    >
                                                        <FloatInput
                                                            label="Last Name"
                                                            placeholder="Last Name"
                                                            required={true}
                                                            disabled={
                                                                formDisabled
                                                            }
                                                            onBlur={() => {
                                                                if (params.id) {
                                                                    form.submit();
                                                                }
                                                            }}
                                                        />
                                                    </Form.Item>
                                                </Col>

                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={12}
                                                    xl={12}
                                                    xxl={12}
                                                >
                                                    <Form.Item name="gender">
                                                        <FloatSelect
                                                            label="Gender"
                                                            placeholder="Gender"
                                                            disabled={
                                                                formDisabled
                                                            }
                                                            options={
                                                                optionGender
                                                            }
                                                            onChange={() => {
                                                                if (params.id) {
                                                                    form.submit();
                                                                }
                                                            }}
                                                        />
                                                    </Form.Item>
                                                </Col>
                                            </Row>
                                        ),
                                    },
                                ]}
                            />
                        </Col>

                        <Col sm={24} md={24} lg={10} xl={10} xxl={10}>
                            <Collapse
                                className="collapse-main-primary"
                                defaultActiveKey={["0"]}
                                size="middle"
                                expandIcon={({ isActive }) => (
                                    <FontAwesomeIcon
                                        icon={
                                            isActive ? faAngleUp : faAngleDown
                                        }
                                    />
                                )}
                                items={[
                                    {
                                        key: "0",
                                        label: "TAKE PHOTO",
                                        className: "collapse-profile-picture",
                                        children: (
                                            <Row gutter={[12, 0]}>
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={24}
                                                    lg={24}
                                                >
                                                    <div className="profile-picture-wrapper">
                                                        <img
                                                            alt=""
                                                            src={
                                                                toggleModalUploadProfilePicture.src
                                                                    ? toggleModalUploadProfilePicture.src
                                                                    : defaultProfile
                                                            }
                                                        />

                                                        <Button
                                                            type="link"
                                                            icon={
                                                                <FontAwesomeIcon
                                                                    icon={
                                                                        faCamera
                                                                    }
                                                                />
                                                            }
                                                            className="btn-upload"
                                                            onClick={() =>
                                                                setToggleModalUploadProfilePicture(
                                                                    (ps) => ({
                                                                        ...ps,
                                                                        open: true,
                                                                    })
                                                                )
                                                            }
                                                        />
                                                    </div>

                                                    <ModalUploadProfilePicture
                                                        toggleModalUploadProfilePicture={
                                                            toggleModalUploadProfilePicture
                                                        }
                                                        setToggleModalUploadProfilePicture={
                                                            setToggleModalUploadProfilePicture
                                                        }
                                                    />
                                                </Col>
                                            </Row>
                                        ),
                                    },
                                ]}
                            />
                        </Col>

                        {params.id ? null : (
                            <Col
                                xs={24}
                                sm={24}
                                md={24}
                                lg={24}
                                xl={24}
                                xxl={24}
                            >
                                <Button
                                    key={4}
                                    className="btn-main-primary"
                                    type="primary"
                                    size="large"
                                    onClick={() => form.submit()}
                                >
                                    SUBMIT
                                </Button>
                            </Col>
                        )}
                    </Row>
                </Form>

                <ModalFormEmail
                    toggleModalFormEmail={toggleModalFormEmail}
                    setToggleModalFormEmail={setToggleModalFormEmail}
                />

                <ModalFormPassword
                    toggleModalFormPassword={toggleModalFormPassword}
                    setToggleModalFormPassword={setToggleModalFormPassword}
                />

                <ModalUserUploadPictureForm
                    toggleModalUserUploadPictureForm={
                        toggleModalUserUploadPictureForm
                    }
                    setToggleModalUserUploadPictureForm={
                        setToggleModalUserUploadPictureForm
                    }
                />
            </Col>
        </Row>
    );
}
