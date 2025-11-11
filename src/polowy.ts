declare const $: any;

document.querySelectorAll(".js-only").forEach(element => {
    (element as HTMLElement).style.display = "flex";
});

($("#dataPolowu") as any).datepicker({ dateFormat: "dd-mm-yy" });
($("#wagaRyby") as any).slider({
    range: "min",
    value: 1,
    min: 0.5,
    max: 50,
    step:0.1,
    slide: function(event: any, ui: any) {
        $("#wagaRybyValue").text(ui.value + " kg");
    }
});
($("#emojiRating") as any).emoji({
    width: '32px',
    value: 0,
    emojis: ['&#x1F620;','&#x1F61E;','&#x1F610;','&#x1F60A;','&#x1F603;'],
    callback: function(event: any, value: any) {
        $("#rating").val(value);
    }
});