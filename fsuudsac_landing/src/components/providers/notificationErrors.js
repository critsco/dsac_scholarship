import { notification } from "antd";
export default function notificationErrors(err) {
	const capitalize = (s) => {
		if (typeof s !== "string") return "";
		return s.charAt(0).toUpperCase() + s.slice(1);
	};

	let errors = err.response.data.errors;
	if (errors) {
		let fieldnames = Object.keys(errors);
		Object.values(errors).map((messages, index) => {
			let fieldname = fieldnames[index].split("_");
			fieldname.map((string, key) => {
				fieldname[key] = capitalize(string);

				return false;
			});
			fieldname = fieldname.join(" ");
			notification.error({
				message: fieldname,
				description: messages.join("\n\r"),
			});

			return false;
		});
	} else {
		notification.error({
			message: err.code,
			description: err.message,
		});
	}
}
