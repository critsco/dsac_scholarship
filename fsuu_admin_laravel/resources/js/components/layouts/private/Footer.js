import { Layout } from "antd";
import { name } from "../../providers/companyInfo";

export default function Footer() {
    return (
        <Layout.Footer>{`${name} ©2023 DEVELOPED BY DSAC TEAM`}</Layout.Footer>
    );
}
