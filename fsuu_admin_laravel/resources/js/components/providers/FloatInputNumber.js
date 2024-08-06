import React, { useState, useEffect } from "react";
import { InputNumber } from "antd";

const FloatInputNumber = (props) => {
    let {
        label,
        value,
        placeholder,
        type,
        required,
        disabled,
        readOnly,
        addonAfter,
        autoFocus,
        maxLength,
        inputMode,
        pattern,
        className,
        size,
        hideHandlerWrapper,
    } = props;

    const [focus, setFocus] = useState(false);

    if (!placeholder) placeholder = label;

    const isOccupied =
        focus ||
        (value !== undefined &&
            value !== null &&
            value !== "" &&
            value.length !== 0);

    const labelClass = isOccupied ? "label float-label" : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    useEffect(() => {
        // console.log(props)
    }, [props]);

    return (
        <div
            className={`float-wrapper float-input-number ${className ?? ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <InputNumber
                onChange={props.onChange}
                type={type}
                value={value}
                size={size ?? ""}
                autoComplete="off"
                disabled={disabled}
                readOnly={readOnly}
                addonAfter={addonAfter ? addonAfter : ""}
                autoFocus={autoFocus ? true : false}
                maxLength={maxLength}
                inputMode={inputMode}
                pattern={pattern}
                className={
                    !hideHandlerWrapper ? "hide-input-handler-wrapper" : ""
                }
                step={props.step}
            />
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
};

export default FloatInputNumber;
