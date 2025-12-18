import CryptoJS from "crypto-js";

const LaravelEncrypt = function (key) {
    this.key = key;
}

LaravelEncrypt.prototype.decrypt = function (encryptStr) {
    encryptStr = CryptoJS.enc.Base64.parse(encryptStr);
    let encryptData = encryptStr.toString(CryptoJS.enc.Utf8);
    encryptData = JSON.parse(encryptData);
    let iv = CryptoJS.enc.Base64.parse(encryptData.iv);
    var decrypted = CryptoJS.AES.decrypt(encryptData.value,  CryptoJS.enc.Utf8.parse(this.key), {
        iv : iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });
    decrypted = CryptoJS.enc.Utf8.stringify(decrypted);
    return decrypted;
};

LaravelEncrypt.prototype.encrypt = function (data) {
    let iv = CryptoJS.lib.WordArray.random(16),
    key = CryptoJS.enc.Utf8.parse(this.key);
    let options = {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    };
    let encrypted = CryptoJS.AES.encrypt(data, key, options);
    encrypted = encrypted.toString();
    iv = CryptoJS.enc.Base64.stringify(iv);
    let result = {
        iv: iv,
        value: encrypted,
        mac: CryptoJS.HmacSHA256(iv + encrypted, key).toString()
    }
    result = JSON.stringify(result);
    result = CryptoJS.enc.Utf8.parse(result);
    return CryptoJS.enc.Base64.stringify(result);
};

export default LaravelEncrypt;
