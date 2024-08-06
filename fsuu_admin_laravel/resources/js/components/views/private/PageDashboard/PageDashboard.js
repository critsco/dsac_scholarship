import { Row, Col, Collapse } from "antd";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faAngleDown, faAngleUp } from "@fortawesome/pro-regular-svg-icons";
import FacultyMonitoringGraph from "./CollapseItemFacultyMonitoringGraph";
import { role } from "../../../providers/companyInfo";
import FacultyMonitoringGraph2 from "./CollapseItemFacultyMonitoringGraph2";

import Highcharts from "highcharts";
import highchartsSetOptions from "../../../providers/highchartsSetOptions";
require("highcharts/modules/accessibility")(Highcharts);
// require("highcharts/modules/no-data-to-display")(Highcharts);
require("highcharts/modules/exporting")(Highcharts);
require("highcharts/modules/export-data")(Highcharts);
require("highcharts/modules/boost")(Highcharts);

export default function PageDashboard() {
    highchartsSetOptions(Highcharts);

    return (
        <Row gutter={[12, 12]}>
            <Col xs={24} sm={24} md={24} lg={24} xl={16} xxl={16}></Col>
        </Row>
    );
}
