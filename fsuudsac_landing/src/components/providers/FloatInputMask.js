import React, { useState } from "react";
import InputMask from "react-input-mask";

const FloatInputMask = (props) => {
    const [focus, setFocus] = useState(false);
    let {
        id,
        label,
        value,
        placeholder,
        required,
        maskType,
        onChange,
        onBlur,
        className,
    } = props;

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    return (
        <div
            className={`float-wrapper ${className ?? ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <InputMask
                id={id ?? ""}
                alwaysShowMask={false}
                onChange={(e) => {
                    if (onChange) {
                        onChange(e);
                    }
                }}
                // maskPlaceholder=""
                mask={maskType ? maskType : "9999 9999 9999 9999"}
                value={value ? value : ""}
                onBlur={(e) => {
                    if (onBlur) {
                        onBlur(e.target.value);
                    }
                }}
                className="ant-input-mask ant-input-mask-lg"
            />
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatInputMask;
