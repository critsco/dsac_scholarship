import { useState } from "react";
import { Input } from "antd";

export default function FloatInputPassword(props) {
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
        prefix,
        addonAfter,
        addonBefore,
        allowClear,
        showCount,
        maxLength,
        autoComplete,
        size,
    } = props;

    const [focus, setFocus] = useState(false);

    const hasValue = focus || (value && value !== "" && value.length !== 0);

    const labelClass =
        hasValue || (value && value === "0" && value.length !== 0)
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
            <Input.Password
                id={id ?? ""}
                prefix={prefix}
                onChange={(e) => onChange(e)}
                type={type}
                value={value}
                size={size ?? ""}
                autoComplete={autoComplete ?? "off"}
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
            />
            <label className={labelClass}>
                {hasValue ? label : placeholder} {requiredMark}
            </label>
        </div>
    );
}
