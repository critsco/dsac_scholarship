const sortingArr = (arr, order = "") => {
	if (order !== "desc") {
		return arr.sort((a, b) =>
			a.label.toLowerCase().localeCompare(b.label.toLowerCase())
		);
	} else {
		return arr.sort((a, b) =>
			b.label.toLowerCase() > a.label.toLowerCase() ? 1 : -1
		);
	}
};
export default sortingArr;
