import "./bootstrap";
import "flowbite";
import "flowbite-datepicker";
import "./sidebar.js";
import "./swal.js";
import {
    Chart,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    BarElement,
    ArcElement,
    Filler,
    LineController,
    BarController,
    DoughnutController,
} from "chart.js";

// Register SEMUA komponen yang diperlukan
Chart.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    LineController,
    BarController,
    DoughnutController,
    Title,
    Tooltip,
    Legend,
    BarElement,
    ArcElement,
    Filler
);

window.Chart = Chart;

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
