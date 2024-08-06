import { Input } from "antd";
import { useState } from "react";

export default function FloatInput(props) {
    const {
        label,
        placeholder,
        onChange,
        onBlur,
        value,
        id,
        className,
        required,
        readOnly,
        disabled,
        type,
        step,
        prefix,
        addonAfter,
        addonBefore,
        allowClear,
        showCount,
        maxLength,
        size,
    } = props;

    const [focus, setFocus] = useState(false);

    const isOccupied = focus || (value && value !== "" && value.length !== 0);

    const labelClass =
        isOccupied || (value && value === "0" && value.length !== 0)
            ? "label float-label"
            : "label";

    const requiredMark = required ? (
        <span className="text-danger">*</span>
    ) : null;

    return (
        <div
            className={`float-wrapper ${className ? className : ""}`}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
        >
            <Input
                id={id ?? ""}
                prefix={prefix}
                onChange={(e) => onChange(e)}
                type={type}
                value={value}
                size={size ?? ""}
                autoComplete="off"
                disabled={disabled}
                readOnly={readOnly}
                addonAfter={addonAfter ?? ""}
                addonBefore={addonBefore ?? ""}
                allowClear={allowClear ? allowClear : false}
                maxLength={maxLength}
                showCount={showCount ?? false}
                onBlur={(e) => {
                    if (onBlur) {
                        onBlur(e);
                    }
                }}
                step={step ?? ""}
            />
            <label className={labelClass}>
                {isOccupied ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
}
