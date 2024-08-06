import React, { useState } from "react";
import { Input } from "antd";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/pro-regular-svg-icons";

const FloatInputSearch = (props) => {
    let { label, value, placeholder, required, className, suffix, id, size } =
        props;

    const [focus, setFocus] = useState(false);

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    return (
        <div
            className={`float-wrapper float-input-search ${className ?? ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <Input
                id={id ?? ""}
                onChange={(e) => props.onChange(e.target.value)}
                value={value}
                size={size ?? ""}
                autoComplete="off"
                suffix={suffix ?? <FontAwesomeIcon icon={faSearch} />}
                style={{ width: "100%" }}
                allowClear
            />
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatInputSearch;
