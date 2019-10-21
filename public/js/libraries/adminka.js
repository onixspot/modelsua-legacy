var Adminka = function (oId) {

    var id = oId;
    var x = 64;
    var y = 64;
    var width = 900;
    var height = 800;
    var mouseDown = false;

    /* Public methods */
    this.uiTable = function (tableId) {
        var uiTable = new Object({
            id: tableId,
            columns: [],

            init: function () {

            },

            setColumns: function (data) {
                this.columns = data;

                var tr = $("<tr />");
                for (var i = 0; i < this.columns.length; i++) {
                    tr.append(
                        $("<th />").html(this.columns[i].name)
                    );
                }

                $("#" + this.id).remove("tr");
                $("#" + this.id).append(
                    $(tr)
                );
            },

            addRow: function (data) {
                var tr = $("<tr />");
                for (var i = 0; i < data.length; i++) {
                    tr.append(
                        $("<td />")
                            .css("text-align", this.columns[i].align)
                            .html(data[i])
                    );
                }
                $("#" + this.id).append(
                    $(tr)
                );
            },

            removeAllRows: function () {
                var tr = $("#" + this.id).find("tr").eq(0);
                $("#" + this.id).find("tr").remove();
                $("#" + this.id).append($(tr));
            }
        });

        return uiTable;
    }

    this.uiTabBar = function (tabBarId) {
        var uiTabBar = new Object({
            id: tabBarId,
            selected: "",

            init: function () {
                //console.log("uiTabBar.init()");
                $("#" + this.id).attr("class", "uiTabBar");

                this.selected = $("#" + this.id + " ul li.selected").attr("id");

                $("#" + this.id + " ul li").click(function () {
                    uiTabBar.click($(this));
                });

                $("#" + this.id + " ul li.selected").click();
            },

            click: function (item) {
                $.each($(item).parent().find("li"), function () {
                    $(this).attr("class", "");
                });
                $(item).attr("class", "selected");
                this.selected = $(item).attr("id");
                this.onClick();
            },

            onClick: function () {}
        });

        //uiTabBar.init();

        return uiTabBar;
    }

    /* Private methods */
    var init = function () {
        $("#" + id)
            .attr("class", "adminka")
            .css({
                "left": x + "px",
                "top": y + "px",
                "width": width + "px",
                "height": height + "px"
            });

        $("#" + id).draggable({
            handle: "div.head",
            start: function () {
                $(this)
                    .find("div.head")
                    .css("cursor", "move");
            },
            stop: function () {
                $(this)
                    .find("div.head")
                    .css("cursor", "pointer");
            }
        });

        $("#" + id + " div[class='closeButton'] a").click(function () {
            $("#" + id)
                .animate(
                    {
                        "opacity": 0
                    },
                    256,
                    function () {
                        $(this).hide();
                    }
                )
        })
    }

    init();

}
