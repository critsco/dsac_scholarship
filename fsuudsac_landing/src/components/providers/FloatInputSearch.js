import React, { useState } from "react";
import { Input } from "antd";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/pro-regular-svg-icons";

const FloatInputSearch = (props) => {
	const [focus, setFocus] = useState(false);
	let { label, value, placeholder, required, className, suffix, id } = props;

	if (!placeholder) placeholder = label;

	const isOccupied = focus || (value && value.length !== 0);

	const labelClass = isOccupied ? "label float-label" : "label";

	const requiredMark = required ? <span className="text-danger">*</span> : null;

	return (
		<div
			className={`float-wrapper ${className ?? ""}`}
			onBlur={() => setFocus(false)}
			onFocus={() => setFocus(true)}
		>
			<Input
				id={id ?? ""}
				onChange={(e) => props.onChange(e.target.value)}
				value={value}
				size="large"
				autoComplete="off"
				suffix={
					suffix ?? (
						<FontAwesomeIcon
							icon={faSearch}
							style={{
								fontSize: 16,
								color: "#1890ff",
							}}
						/>
					)
				}
				style={{ width: "100%" }}
				allowClear
			/>
			<label className={labelClass}>
				{isOccupied ? label : placeholder} {requiredMark}
			</label>
		</div>
	);
};

export default FloatInputSearch;
