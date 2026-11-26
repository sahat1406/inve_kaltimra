/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="date"][data-max-today]').forEach(function (input) {
        var validateDate = function () {
            if (input.value && input.max && input.value > input.max) {
                input.setCustomValidity('Tanggal pengakuan awal tidak boleh lebih dari hari ini.');
            } else {
                input.setCustomValidity('');
            }
        };

        input.addEventListener('input', validateDate);
        input.addEventListener('change', validateDate);
        validateDate();
    });
});
