import { useEffect, useState } from "react";
import { Tabs } from "antd";

import { GET } from "../../../../providers/useAxiosQuery";
import optionUserType from "../../../../providers/optionUserType";
import TableUserRolePermission from "./TableUserRolePermission";

export default function TabPermissionUserRole(props) {
    const { location } = props;

    const [tableFilter, setTableFilter] = useState({
        page: 1,
        page_size: 50,
        search: "",
        sort_field: "module_code",
        sort_order: "asc",
        system_id:
            location.pathname === "/permission/opis"
                ? 1
                : location.pathname === "/permission/faculty-monitoring"
                ? 2
                : location.pathname === "/permission/guidance"
                ? 3
                : 0,
        user_role_id: 1,
    });

    useEffect(() => {
        let system_id = 0;
        if (location.pathname === "/permission/opis") {
            system_id = 1;
        } else if (location.pathname === "/permission/faculty-monitoring") {
            system_id = 2;
        } else if (location.pathname === "/permission/guidance") {
            system_id = 3;
        }
        setTableFilter((ps) => ({ ...ps, system_id: system_id }));

        return () => {};
    }, [location]);

    const { data: dataSource, refetch: refetchSource } = GET(
        `api/user_role_permission?${new URLSearchParams(tableFilter)}`,
        "user_role_permission_list"
    );

    useEffect(() => {
        refetchSource();

        return () => {};
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [tableFilter]);

    const handleTabChange = (key) => {
        setTableFilter((ps) => ({ ...ps, user_role_id: key }));
    };

    return (
        <Tabs
            onChange={handleTabChange}
            defaultActiveKey="1"
            type="card"
            items={[
                ...(optionUserType
                    ? optionUserType.map((item) => ({
                          key: item.value,
                          label: item.label,
                          children: (
                              <TableUserRolePermission
                                  dataSource={dataSource}
                                  tableFilter={tableFilter}
                                  setTableFilter={setTableFilter}
                              />
                          ),
                      }))
                    : []),
            ]}
        />
    );
}
