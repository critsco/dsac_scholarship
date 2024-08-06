import React, { useState } from "react";
import { Select } from "antd";

const FloatSelect = (props) => {
    let {
        id,
        label,
        value,
        placeholder,
        required,
        options,
        disabled,
        multi,
        popupClassName,
        allowClear,
        onBlur,
        onChange,
        size,
        className,
    } = props;

    const [focus, setFocus] = useState(false);

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const multiClass = multi ? "float-select-multi" : "float-select";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    // console.log(props);
    return (
        <div
            className={`float-wrapper ${className ?? ""} ${multiClass}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <Select
                id={id ?? ""}
                style={{ width: "100%" }}
                value={value}
                onChange={(e) => {
                    if (onChange) {
                        onChange(e);
                    }
                }}
                size={size ?? "middle"}
                allowClear={allowClear ?? allowClear}
                showSearch
                disabled={disabled ? disabled : false}
                mode={multi}
                popupClassName={`float-select-dropdown ${popupClassName ?? ""}`}
                onBlur={(e) => {
                    if (onBlur) {
                        onBlur(e);
                    }
                }}
                filterOption={(input, option) => {
                    // console.log("option", option);
                    return (
                        option.label
                            .toLowerCase()
                            .indexOf(input.toLowerCase()) >= 0
                    );
                }}
            >
                {options.map((item, key) => {
                    return (
                        <Select.Option
                            key={key}
                            value={item.value}
                            data-json={item.json}
                            label={item.label}
                        >
                            {item.label}
                        </Select.Option>
                    );
                })}
            </Select>
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatSelect;
