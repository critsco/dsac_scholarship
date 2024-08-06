import React, { useState } from "react";
import { InputNumber } from "antd";

const FloatInputRate = (props) => {
	const [focus, setFocus] = useState(false);
	let { label, value, placeholder, required, size, className, id } = props;

	if (!placeholder) placeholder = label;

	const isOccupied = focus || (value && value.length !== 0);

	const labelClass = isOccupied ? "label float-label" : "label";

	const requiredMark = required ? <span className="text-danger">*</span> : null;

	return (
		<div
			className={`float-wrapper float-input-rate ${className ?? ""}`}
			onBlur={() => setFocus(false)}
			onFocus={() => setFocus(true)}
		>
			<InputNumber
				id={id ?? ""}
				onChange={props.onChange}
				value={value}
				size={size ?? "large"}
				autoComplete="off"
				stringMode
				step="0.00"
				// formatter={(value) => formatterNumber(value)}
				// parser={(value) => parserNumber(value)}
				style={{ width: "100%" }}
			/>
			<label className={labelClass}>
				{isOccupied ? label : placeholder} {requiredMark}
			</label>
		</div>
	);
};

export default FloatInputRate;
