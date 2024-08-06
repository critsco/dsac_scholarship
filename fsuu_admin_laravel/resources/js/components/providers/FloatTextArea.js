import React, { useState } from "react";
import { Input } from "antd";

const FloatTextArea = (props) => {
    const [focus, setFocus] = useState(false);
    let {
        label,
        value,
        placeholder,
        type,
        required,
        id,
        rows,
        onBlur,
        className,
        size,
    } = props;

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    return (
        <div
            className={`float-wrapper float-textarea ${className ?? ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <Input.TextArea
                id={id ?? ""}
                onChange={props.onChange}
                type={type}
                value={value}
                size={size ?? "middle"}
                rows={rows ?? 4}
                onBlur={(e) => {
                    if (onBlur) {
                        onBlur(e);
                    }
                }}
            />

            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatTextArea;
