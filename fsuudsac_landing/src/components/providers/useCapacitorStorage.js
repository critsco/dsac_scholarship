import { Capacitor } from "@capacitor/core";
import { Filesystem, Directory, Encoding } from "@capacitor/filesystem";
import { Network } from "@capacitor/network";

export const getPlatform = () => Capacitor.getPlatform();

export const writeDirectory = async (foldername) => {
	try {
		return await Filesystem.mkdir({
			path: foldername,
			directory: Directory.Data,
			recursive: true,
		});
	} catch (error) {
		return error;
	}
};

export const readDirectory = async (foldername = "") => {
	try {
		return await Filesystem.readdir({
			path: foldername ?? "/",
			directory: Directory.Data,
		});
	} catch (error) {
		return error;
	}
};

export const writeFile = async (file, data) => {
	try {
		return await Filesystem.writeFile({
			path: file,
			data: data,
			directory: Directory.Data,
			encoding: Encoding.UTF8,
		});
	} catch (error) {
		return error;
	}
};

export const readFile = async (file) => {
	try {
		return await Filesystem.readFile({
			path: file,
			directory: Directory.Data,
			encoding: Encoding.UTF8,
		});
	} catch (error) {
		return error;
	}
};

export const deleteFile = async (file) => {
	try {
		return await Filesystem.deleteFile({
			path: file,
			directory: Directory.Data,
		});
	} catch (error) {
		return error;
	}
};

export const setFileStorage = async (
	foldername,
	filename,
	data,
	override = false
) => {
	let file = `${foldername}/${filename}.txt`;

	try {
		return await Filesystem.readFile({
			path: file,
			directory: Directory.Data,
			encoding: Encoding.UTF8,
		}).then((res) => {
			if (override) {
				let datanew = JSON.parse(res.data);
				if (Array.isArray(data)) {
					data.forEach((item) => {
						datanew.push(item);
					});
				} else {
					datanew.push(data);
				}

				return writeFile(file, JSON.stringify(datanew));
			} else {
				return writeFile(file, JSON.stringify(data));
			}
		});
	} catch (error) {
		return readDirectory().then(async (res) => {
			if (res && res.files) {
				let files = res.files;
				if (files.length) {
					let fileExist = files.find((f) => f.name === foldername);
					if (!fileExist) {
						return writeDirectory(foldername).then((res1) => {
							if (Array.isArray(data)) {
								return writeFile(file, JSON.stringify(data));
							} else {
								return writeFile(file, JSON.stringify([data]));
							}
						});
					} else {
						if (Array.isArray(data)) {
							return writeFile(file, JSON.stringify(data));
						} else {
							return writeFile(file, JSON.stringify([data]));
						}
					}
				} else {
					return writeDirectory(foldername).then((res1) => {
						if (Array.isArray(data)) {
							return writeFile(file, JSON.stringify(data));
						} else {
							return writeFile(file, JSON.stringify([data]));
						}
					});
				}
			} else {
				return writeDirectory(foldername).then((res1) => {
					if (Array.isArray(data)) {
						return writeFile(file, JSON.stringify(data));
					} else {
						return writeFile(file, JSON.stringify([data]));
					}
				});
			}
		});
	}
};

export const checkPermissions = async () => {
	try {
		return await Filesystem.checkPermissions();
	} catch (error) {
		return error;
	}
};
export const requestPermissions = async (file) => {
	try {
		return await Filesystem.requestPermissions();
	} catch (error) {
		return error;
	}
};

export const checkNetworkStatus = async () => {
	// Network.addListener("networkStatusChange", (status) => {
	// 	console.log("Network status changed", status);
	// });
	return await Network.getStatus();
};

export const requestPublicStoragePermissions = async () => {
	checkPermissions().then(async (res) => {
		if (res) {
			if (res.publicStorage !== "granted") {
				return await Filesystem.requestPermissions();
			}
		} else {
			return await Filesystem.requestPermissions();
		}
	});
};
