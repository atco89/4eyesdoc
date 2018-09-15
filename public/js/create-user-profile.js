var app = new App;

$(document)

    .ready(function () {
        let groupId = $("#group").val();
        $(".selectpicker").selectpicker();
        // ===== enable features for doctors =====
        $(".examinations, .examination").prop("disabled", function () {
            return !(groupId === "1" || groupId === "6");
        }).selectpicker("refresh");
    })

    .on("keyup", "[data-convert-letters]", function () {
        let transliterated = app.transliterate($(this).val());
        $(this).val(transliterated);
    })

    .on("click", ".examinations", function () {
        let isChecked = $(this).is(":checked");
        $(".examination").prop("checked", isChecked);
    })

    .on("changed.bs.select", "#group", function () {
        let input = $(this).val();
        // ===== load roles ======
        $.get("partial/roles/" + input, function (response) {
            $("#role").html(response)
                .prop("disabled", false)
                .selectpicker("refresh");
        });
        // ===== enable features for doctors =====
        $(".examinations, .examination").prop("disabled", function () {
            return !(input === "1" || input === "6");
        }).selectpicker("refresh");
    });