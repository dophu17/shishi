$(document).ready(function() {
    $('.note').hide();
    $('.error').hide();
    //Doing status
    $('.doing').click(function() {

        var content = $(".content").val();
        var month = $("#month :selected").text();
        var day = $("#day :selected").text();
        var hour = $("#hour :selected").text();
        var fromUser = $("#fromUser :selected").val();
        var status = 0;

        $.post(doingUrl, {
                content: content,
                status: status,
                month: month,
                day: day,
                hour: hour,
                fromUser: fromUser,
                report_id: report_id
            },
            function(res) {
                ajax_respone_check(res, true);
                if (res.trim() == 'success') {
                    $('.b_going').text('記入中').css("background", "#999");
                    setTimeout(function() {
                        $('.note').fadeIn().text('データは一時的に更新されました。');
                    }, 100);
                    setTimeout(function() {
                        $('.note').fadeOut();
                    }, 2000);

                } else {
                    setTimeout(function() {
                        $('.error').fadeIn();
                    }, 100);
                    setTimeout(function() {
                        $('.error').fadeOut();
                    }, 2000);
                }
            }
        );
    });
    //Done status
    $('.done').click(function() {
        var content = $(".content").val();
        var month = $("#month :selected").text();
        var day = $("#day :selected").text();
        var hour = $("#hour :selected").text();
        var fromUser = $("#fromUser :selected").val();
        var status = 1;

        $.post(doneUrl, {
                content: content,
                status: status,
                month: month,
                day: day,
                hour: hour,
                fromUser: fromUser,
                report_id: report_id
            },
            function(res) {
                ajax_respone_check(res, true);
                if (res.trim() == 'success') {
                    $('.b_going').text('記入済み').css("background", "#3276b1");

                    setTimeout(function() {
                        $('.note').fadeIn().text('データは完全に更新されました。');
                    }, 100);
                    setTimeout(function() {
                        $('.note').fadeOut();
                    }, 2000);

                } else {
                    setTimeout(function() {
                        $('.error').fadeIn().text('データはアップロードされていません。');
                    }, 100);
                    setTimeout(function() {
                        $('.error').fadeOut();
                    }, 2000);

                }
            }
        );
    });
    //Btn sendReportToCwBtn
    $("#sendReportToCwBtn").click(function() {
        $.post(chatworkUrl, {
                report_id: report_id
            },
            function(res) {
                ajax_respone_check(res, true);
                if (res.trim() == 'success') {
                    setTimeout(function() {
                        $('.note').fadeIn().text('データはチャットワークにアップロードされました。');
                    }, 100);
                    setTimeout(function() {
                        $('.note').fadeOut();
                    }, 2000);

                } else if (res.trim() == 'error_data') {
                    setTimeout(function() {
                        $('.error').fadeIn().text('エラーになりました。レポートコンテンツは空いてます。');
                    }, 100);
                    setTimeout(function() {
                        $('.error').fadeOut();
                    }, 2000);

                } else if (res.trim() == 'error_room_id') {
                    setTimeout(function() {
                        $('.error').fadeIn().text('チャットワークグループIDを選択してください。');
                    }, 100);
                    setTimeout(function() {
                        $('.error').fadeOut();
                    }, 2000);
                }
            });
    });

    $('.edit_deadline').click(function() {

        var month = $('#month', '.edit_weekly_report').val();
        var day = $('#day', '.edit_weekly_report').val();
        var hour = $('#hour', '.edit_weekly_report').val();

        $.post(editDeadlineUrl, {
                report_id: report_id,
                month: month,
                day: day,
                hour: hour,
            },
            function(res) {
                ajax_respone_check(res, true);
                var jsonOBJ = JSON.parse(res);
                if (jsonOBJ.status == 'success') {
                    alert('週レポートの期限は変更されました。');
                } else if (jsonOBJ.status == 'error') {
                    alert('週レポートの期限は変更されませんでした。');
                } else if (jsonOBJ.status == 'input_invalid') {
                    alert('入力データは正しくありません。');
                } else {
                    alert('未定義のエラー');
                }
            });
    });
});