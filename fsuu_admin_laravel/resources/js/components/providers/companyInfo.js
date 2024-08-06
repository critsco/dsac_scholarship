import CryptoJS from "crypto-js";

export const name = process.env.MIX_APP_NAME;
export const description = process.env.MIX_APP_DESCRIPTION;
export const bgColor = process.env.MIX_APP_BG_COLOR;
export const logo = process.env.MIX_APP_LOGO;
export const logoFullWidth = process.env.MIX_APP_LOGO_FULLWIDTH;
export const apiUrl = (url) => `${window.location.origin}/${url}`;

export const defaultProfile = apiUrl("images/default.png");
export const defaultDocument = apiUrl("images/documents.png");

export const date = new Date();

export const encryptKey =
    process.env.MIX_ENCRYPT_APP_KEY + `-${date.getFullYear()}`;

export const encrypt = (data) =>
    CryptoJS.AES.encrypt(data, encryptKey).toString();

export const decrypt = (data) => {
    try {
        return CryptoJS.AES.decrypt(data, encryptKey).toString(
            CryptoJS.enc.Utf8
        );
    } catch (error) {
        console.error("Error decrypting data:", error);
        clearLocalStorage();
        window.location.reload();
        return null;
    }
};

const clearLocalStorage = () => {
    localStorage.token = "";
    localStorage.userdata = "";
    return false;
};

export const token = () => {
    if (localStorage.getItem("token") === null) {
        clearLocalStorage();
        return false;
    }
    return "Bearer " + localStorage.getItem("token");
};

export const userData = () => {
    if (localStorage.getItem("userdata") === null) {
        clearLocalStorage();
        return false;
    }
    return JSON.parse(decrypt(localStorage.getItem("userdata")));
};

export const role = () => {
    return userData().role;
};
