var App = new function () {

    this.printPage = function (strid) {
        var prtContent = document.getElementById(strid);
        var extNode = document.getElementById('not_for_print');
        var WinPrint = window.open('print', '', 'left=50,top=50,width=750,height=640,toolbar=0,scrollbars=1,status=0');

        if (extNode) prtContent.removeChild(extNode);
        WinPrint.document.write(prtContent.innerHTML);

        WinPrint.focus();
        WinPrint.print();
    };

    this.getClientInfo = function (type, object_id) {
        var browserInfo = $.browser;
        $.post
        (
            '/views/index',
            {
                "bInfo": browserInfo,
                "object_id": object_id,
                "type": type
            },
            function (resp) {console.log(resp)},
            'json'
        )
    };

    this.vote = function (oid, type, votes, respId) {
        $.post(
            '/voting/index',
            {
                object_id: oid,
                type: type,
                votes: votes
            },
            function (resp) {
                if (resp.success == 1)
                    $('#' + respId).html(resp.votes);
                else
                    console.log(resp);
            },
            'json'
        );
    };

    this.delete_message = function (messageId, messageBoxId) {
        $.post(
            '/messages/index',
            {
                'message_id': messageId,
                'delete': '1'
            },
            function (resp) {
                if (resp.success)
                    $('#' + messageBoxId).remove();
                else
                    console.log(resp);
            },
            'json'
        );
    }
};;
