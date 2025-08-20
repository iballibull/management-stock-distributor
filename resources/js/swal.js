import Swal from "sweetalert2";

const body = document.querySelector("body");
const successMessage = body.getAttribute("data-success");
const failedMessage = body.getAttribute("data-failed");
console.log(successMessage);

if (successMessage) {
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: successMessage,
        timer: 3000,
    });
}

if (failedMessage) {
    Swal.fire({
        icon: "error",
        title: "Gagal!",
        text: failedMessage,
    });
}
