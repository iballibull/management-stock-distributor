import "./bootstrap";
import "flowbite";
import "flowbite-datepicker";
import "./sidebar.js";
import "./swal.js";

import $ from "jquery";
window.$ = window.jQuery = $;

import select2 from "select2";

import Alpine from "alpinejs";

select2($);

$(document).ready(function () {
    $(".select2").select2({
        placeholder: "Pilih option",
        allowClear: true,
        width: "100%",
    });
});

window.Alpine = Alpine;
Alpine.start();
