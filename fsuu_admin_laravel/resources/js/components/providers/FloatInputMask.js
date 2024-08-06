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
        onBlur,
        className,
        size,
        // maskPlaceholder,
    } = props;

    if (!placeholder) placeholder = label;

    const isOccupied = focus || (value && value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    return (
        <div
            className={`float-wrapper float-input-mask ${className ?? ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <InputMask
                id={id ?? ""}
                alwaysShowMask={false}
                onChange={(e) => props.onChange(e)}
                // maskPlaceholder={maskPlaceholder ? maskPlaceholder : null}
                mask={maskType ? maskType : "9999 9999 9999 9999"}
                value={value ? value : ""}
                onBlur={(e) => {
                    if (onBlur) {
                        onBlur(e);
                    }
                }}
                className={`ant-input ${size ?? ""}`}
            />
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatInputMask;
