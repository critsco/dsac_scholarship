import { useMutation, useQuery, useQueryClient } from "react-query";
import axios from "axios";
import { apiUrl, token } from "./companyInfo";

export function POST(url, key_name, onSuccessFunction, isLoading = true) {
	const queryClient = useQueryClient();
	return useMutation(
		(data) => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("globalLoading");
				}
			}
			return axios
				.post(`${apiUrl(url)}`, data, {
					headers: {
						Authorization: token(),
					},
				})
				.then((res) => res.data);
		},
		{
			onSuccess: () => {
				if (onSuccessFunction) onSuccessFunction();
				if (key_name) {
					if (typeof key_name === "string") {
						queryClient.refetchQueries(key_name);
					} else {
						key_name.forEach((name) => {
							queryClient.refetchQueries(name);
						});
					}
				}

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					globalLoading.classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					globalLoading.classList.add("hide");
				}
			},
		}
	);
}

export function POSTMANUAL(
	settoken,
	url,
	key_name,
	onSuccessFunction,
	isLoading = true
) {
	const queryClient = useQueryClient();
	return useMutation(
		(data) => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("hide");
				}
			}
			return axios
				.post(`${apiUrl(url)}`, data, {
					headers: {
						Authorization: settoken,
					},
				})
				.then((res) => res.data);
		},
		{
			onSuccess: () => {
				if (onSuccessFunction) onSuccessFunction();
				if (key_name) {
					if (typeof key_name === "string") {
						queryClient.refetchQueries(key_name);
					} else {
						key_name.forEach((name) => {
							queryClient.refetchQueries(name);
						});
					}
				}

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					globalLoading.classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");
				if (!globalLoading.matches(".hide")) {
					globalLoading.classList.add("hide");
				}
			},
		}
	);
}

export function POSTFILE(url, key_name, onSuccessFunction, isLoading = true) {
	const queryClient = useQueryClient();

	return useMutation(
		(data) => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("hide");
				}
			}
			return axios
				.post(`${apiUrl(url)}`, data, {
					headers: {
						Authorization: token(),
						"Content-Type": "multipart/form-data",
					},
				})
				.then((res) => res.data);
		},
		{
			onSuccess: () => {
				if (onSuccessFunction) onSuccessFunction();
				if (key_name) {
					if (typeof key_name === "string") {
						queryClient.refetchQueries(key_name);
					} else {
						key_name.forEach((name) => {
							queryClient.refetchQueries(name);
						});
					}
				}

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					globalLoading.classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");

				if (globalLoading && !globalLoading.matches(".hide")) {
					globalLoading.classList.add("hide");
				}
			},
		}
	);
}

export function UPDATE(url, key_name, onSuccessFunction, isLoading = true) {
	const queryClient = useQueryClient();

	return useMutation(
		(data) => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("hide");
				}
			}
			return axios
				.put(`${apiUrl(url + "/" + data.id)}`, data, {
					headers: {
						Authorization: token(),
					},
				})
				.then((res) => res.data);
		},
		{
			onSuccess: () => {
				if (onSuccessFunction) onSuccessFunction();
				// console.log(key_name);
				if (key_name) {
					if (typeof key_name === "string") {
						queryClient.refetchQueries(key_name);
					} else {
						key_name.forEach((name) => {
							queryClient.refetchQueries(name);
						});
					}
				}

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
		}
	);
}

export function DELETE(url, key_name, onSuccessFunction, isLoading = true) {
	const queryClient = useQueryClient();

	return useMutation(
		(data) => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("hide");
				}
			}
			return axios
				.delete(`${apiUrl(url + "/" + data.id)}`, {
					headers: {
						Authorization: token(),
					},
				})
				.then((res) => res.data);
		},
		{
			onSuccess: () => {
				if (onSuccessFunction) onSuccessFunction();
				if (key_name) {
					if (typeof key_name === "string") {
						queryClient.refetchQueries(key_name);
					} else {
						key_name.forEach((name) => {
							queryClient.refetchQueries(name);
						});
					}
				}

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
		}
	);
}

export function GET(url, key_name, onSuccessFunction, isLoading = true) {
	return useQuery(
		key_name,
		() => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("hide");
				}
			}
			return axios
				.get(`${apiUrl(url)}`, {
					headers: {
						Authorization: token(),
					},
				})
				.then((res) => res.data);
		},
		{
			retry: 1,
			retryDelay: 500,
			fetchOnWindowFocus: false,
			refetchOnWindowFocus: false,
			onSuccess: (res) => {
				if (onSuccessFunction) onSuccessFunction(res);

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
		}
	);
}

export function GETMANUAL(url, key_name, onSuccessFunction, isLoading = true) {
	return useQuery(
		key_name,
		() => {
			if (isLoading) {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading) {
					globalLoading.classList.remove("hide");
				}
			}
			return axios
				.get(`${apiUrl(url)}`, {
					headers: {
						Authorization: token(),
					},
				})
				.then((res) => res.data);
		},
		{
			enabled: false,
			retry: 1,
			retryDelay: 500,
			fetchOnWindowFocus: false,
			refetchOnWindowFocus: false,
			onSuccess: (res) => {
				if (onSuccessFunction) onSuccessFunction(res);

				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
			onError: () => {
				let globalLoading = document.querySelector(".globalLoading");
				if (globalLoading && !globalLoading.matches(".hide")) {
					document.querySelector(".globalLoading").classList.add("hide");
				}
			},
		}
	);
}
