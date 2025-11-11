document.querySelectorAll(".js-only").forEach(element => {
    element.style.display = "flex";
});
$("#dataPolowu").datepicker({ dateFormat: "dd-mm-yy" });
$("#wagaRyby").slider({
    range: "min",
    value: 1,
    min: 0.5,
    max: 50,
    step: 0.1,
    slide: function (event, ui) {
        $("#wagaRybyValue").text(ui.value + " kg");
    }
});
$("#emojiRating").emoji({
    width: '32px',
    value: 0,
    emojis: ['&#x1F620;', '&#x1F61E;', '&#x1F610;', '&#x1F60A;', '&#x1F603;'],
    callback: function (event, value) {
        $("#rating").val(value);
    }
});
export {};
//# sourceMappingURL=polowy.js.map