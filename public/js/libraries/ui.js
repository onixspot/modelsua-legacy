var DropDown = function (dropDownId) {
    var id = dropDownId;

    var init = function () {
        $("<div />")
            .css({
                "background": "#fff",
                "border": "1px solid #ddd"
            })
            .html("test")
            .insertAfter($("#" + id));
    }

    // Initialization
    init();

}

var DatePicker = function () {

    var init = function () {

    }

}
