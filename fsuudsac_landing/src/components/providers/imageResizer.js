import Resizer from "react-image-file-resizer";

const imageResizer = (file, type = "file") =>
	new Promise((resolve) =>
		Resizer.imageFileResizer(
			file,
			1080,
			1080,
			"JPEG",
			70,
			0,
			(uri) => {
				resolve(uri);
			},
			type,
			1000
		)
	);

export default imageResizer;
