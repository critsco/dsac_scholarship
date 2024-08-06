import React from "react";
import { Tabs } from "antd/lib";

import PageUserRole from "../PageUserRole/PageUserRole";
import PageBuilding from "../PageBuilding/PageBuilding";
import PageFloor from "../PageFloor/PageFloor";
import PageRoom from "../PageRoom/PageRoom";
import PageSubject from "../PageSubject/PageSubject";
import PageDepartment from "../PageDepartment/PageDepartment";
import PageStatus from "../PageStatus/PageStatus";
import PageStatusCategory from "../PageStatusCategory/PageStatusCategory";
import PageDaySchedule from "../PageDaySchedule/PageDaySchedule";
import PageRate from "../PageRate/PageRate";
import PagePosition from "../PagePosition/PagePosition";
import PageSection from "../PageSection/PageSection";
import PageTimeSchedule from "../PageTimeSchedule/PageTimeSchedule";
import PageSchoolYear from "../PageSchoolYear/PageSchoolYear";

export default function PageSettings() {
    const tabListTitle = [
        {
            key: "1",
            label: "User Role",
            children: <PageUserRole />,
        },
        {
            key: "2",
            label: "Building",
            children: <PageBuilding />,
        },
        {
            key: "3",
            label: "Floor",
            children: <PageFloor />,
        },
        {
            key: "4",
            label: "Room",
            children: <PageRoom />,
        },
        {
            key: "5",
            label: "Department",
            children: <PageDepartment />,
        },
        {
            key: "6",
            label: "Time Schedule",
            children: <PageTimeSchedule />,
        },
        {
            key: "7",
            label: "Day Schedule",
            children: <PageDaySchedule />,
        },
        {
            key: "8",
            label: "School Year",
            children: <PageSchoolYear />,
        },

        {
            key: "9",
            label: "Section",
            children: <PageSection />,
        },
        {
            key: "10",
            label: "Subject",
            children: <PageSubject />,
        },
        {
            key: "11",
            label: "Position",
            children: <PagePosition />,
        },
        {
            key: "12",
            label: "Rate",
            children: <PageRate />,
        },
        {
            key: "13",
            label: "Status Category",
            children: <PageStatusCategory />,
        },
        {
            key: "14",
            label: "Status",
            children: <PageStatus />,
        },
    ];

    const onChange = (key) => {
        console.log(key);
    };

    return (
        <Tabs
            defaultActiveKey="1"
            onChange={onChange}
            type="card"
            items={tabListTitle}
        />
    );
}
