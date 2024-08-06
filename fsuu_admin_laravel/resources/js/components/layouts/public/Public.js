import { useEffect } from "react";
import { name } from "../../providers/companyInfo";
import { Layout } from "antd";

export default function Public(props) {
    const { children, title, pageId } = props;

    useEffect(() => {
        if (title) {
            document.title = title + " | " + name;
        }

        return () => {};
    }, [title]);

    return (
        <Layout className="public-layout" id={pageId ?? ""}>
            {children}
        </Layout>
    );
}
