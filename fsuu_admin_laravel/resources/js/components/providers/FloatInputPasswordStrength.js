import React, { useState, useEffect } from "react";

import { PasswordInput } from "antd-password-input-strength";

const FloatInputPasswordStrength = (props) => {
    let {
        label,
        value,
        placeholder,
        type,
        required,
        size,
        className,
        id,
        autoComplete,
    } = props;

    const [focus, setFocus] = useState(false);

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    useEffect(() => {
        // console.log(props)
    }, [props]);

    return (
        <div
            className={`float-wrapper float-input-password-strength ${
                className ?? ""
            }`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <PasswordInput
                id={id ?? ""}
                onChange={(e) => props.onChange(e.target.value)}
                type={type}
                value={value}
                size={size ?? "middle"}
                autoComplete={autoComplete ?? "off"}
            />
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatInputPasswordStrength;
