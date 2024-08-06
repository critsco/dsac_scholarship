import React, { useState } from "react";
import { DatePicker } from "antd";

const FloatDatePicker = (props) => {
    const [focus, setFocus] = useState(false);
    let {
        label,
        value,
        placeholder,
        required,
        popupClassName,
        format,
        picker,
        disabled,
        disabledDate,
        id,
        onChange,
        allowClear,
        className,
        size,
        onBlur,
    } = props;

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    return (
        <div
            className={`float-wrapper float-date-picker ${className ?? ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <DatePicker
                id={id ?? ""}
                onChange={(date, dateString) => onChange(date, dateString)}
                value={value ? value : null}
                size={size ?? ""}
                placeholder={[""]}
                popupClassName={popupClassName ?? ""}
                format={format ? format : "DD/MM/YYYY"}
                allowClear={allowClear ?? false}
                onBlur={(date, dateString) => {
                    if (onBlur) {
                        onBlur(date, dateString);
                    }
                }}
                picker={picker ? picker : "date"}
                disabled={disabled ? disabled : false}
                disabledDate={(current) => {
                    if (disabledDate) {
                        return disabledDate(current);
                    } else {
                        return false;
                    }
                }}
            />

            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatDatePicker;
