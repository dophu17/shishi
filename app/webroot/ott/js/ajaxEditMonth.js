$(document).ready(function(e) {
    $('.uploadimage').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: updateImageUrl, // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false, // To send DOMDocument or non processed data file it is set to false
            success: function(data) // A function to be called if request succeeds
            {
                ajax_respone_check(data, true);
                if (data.trim() == 'success') {
                    console.log('success');
                    location.reload();
                } else if (data.trim() == 'nothing') {
                    location.reload();
                } else {
                    alert("イメージを再度選択してください。");
                    return false;
                }
            }
        });
    });

});

$(document).ready(function() {
    $('.error').hide();
    $('.note').hide();
    // prevent input press enter 
    $("input").keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    // Update info
    $(".saveReportItemsBtn").click(function(e) {
        e.preventDefault();

        var report_slide_id = $(this).parents('.box').attr('report_slide_id');
        var options = $(this).parents('.box').attr('options');
        var edit_title = $(this).parents('.box').find('.edit_title').val();
        var edit_description = $(this).parents('.box').find('.edit_description').val();
        var orderNumber = $(this).parents('.box').attr('orderNumber');
        var button_clicked = $(this);

        $.post(
            editReportSlide_url, {
                edit_title: edit_title,
                report_id: report_id,
                report_slide_id: report_slide_id,
                options: options,
                edit_description: edit_description,
                orderNumber: orderNumber
            },
            function(status) {
                ajax_respone_check(status, true);
                if (status.trim() == 'success') {

                    var parent = button_clicked.parents('.box');
                    var button_edit = parent.find('.editReportItemsBtn');
                    var title = parent.find('.editableItem'); //show
                    var description = parent.find('.edit_description_view');
                    var textbox = title.next(); //edit
                    var textbox_des = description.next();
                    title.val(textbox.html()).show();
                    description.html(textbox_des.val()).show();

                    textbox_des.hide();
                    button_clicked.parents('.reportSection').removeClass('editable');

                    //Update image
                    if (parent.find('.submit_inform').length) {
                        parent.find('.submit_inform').trigger('click');
                    } else {
                        location.reload();
                    }
                    //end update image
                    /*setTimeout(function(){
                        $('.note').text('データは保存されました！').fadeIn();
                    }, 300); 
                    setTimeout(function(){
                        $('.note').fadeOut();
                    }, 2500);*/

                } else if (status.trim() == 'error_title') {
                    setTimeout(function() {
                        $('.error').text('タイトルの入力は必須です。').fadeIn();
                    }, 300);
                    setTimeout(function() {
                        $('.error').fadeOut();
                    }, 2500);
                } else {
                    setTimeout(function() {
                        $('.error').text('エラーになりました。再度試してください！').fadeIn();
                    }, 300);
                    setTimeout(function() {
                        $('.error').fadeOut();
                    }, 2500);
                }
            }
        );
        return false;

    });
    //Delete info
    $(".deleteSlideBtn").click(function(event) {
        if (confirm('削除しても良いですか？')) {
            var report_slide_id = $(this).parents('.box').attr('report_slide_id');
            event.preventDefault();

            $.post(
                deleteReportSlide_url, {
                    report_slide_id: report_slide_id,
                },
                function(status) {
                    ajax_respone_check(status, true);
                    if (status.trim() == 'success') {
                        $(".deleteSlideBtn").parents(".box[report_slide_id=" + report_slide_id + "]").remove();
                        location.reload();
                    } else {
                        alert("Error, please try again");
                    }
                }
            );
        } else {
            return false;
        }
    });

    // Public report month
    $('.public').click(function() {
        $.post(
            publicReportMonthUrl, {
                id: report_id
            },
            function(res) {
                ajax_respone_check(res, true);
                res = JSON.parse(res);
                var selector = '.error';
                var text = 'データは公開されませんでした！';
                var content = $('.public').html();
                if (res && res.status == 'success') {
                    selector = '.note';
                    if (res.data == 1) { // publish success
                        text = 'データは公開されました！';
                    } else { // unpublish success
                        text = 'データは非公開されました！';
                    }
                    content = res.button;
                } else {
                    if (content == '公開する') { // publish error
                        text = 'データは公開されませんでした！';
                    } else { // unpublish error
                        text = 'データは非公開されませんでした！';
                    }
                }
                $('.public').html(content);
                setTimeout(function() {
                    $(selector).text(text).fadeIn();
                }, 100);
                setTimeout(function() {
                    $(selector).fadeOut();
                }, 3000);
            });
    });

    // START report revision
    $('.btn_report_revision').click(function() {
        var selected_val = $('select[name=revision]').val();
        var selected_text = $('select[name=revision] > option[value=' + selected_val + ']').text();
        $('.copy_msg').addClass('warning').html(selected_text + ' に復元しますか？<br>');
        $('.confirm_button').show();
        $('.normal_button').hide();
    });

    $('.btn_report_revision_cancelled, .btn_report_revision_confirmed').click(function() {
        $('.copy_msg').addClass('warning').html('');
        $('.confirm_button').hide();
        $('.normal_button').show();
    });

    $('.btn_report_revision_confirmed').click(function() {
        $.ajax({
            url: reportRevisionUrl,
            type: "POST",
            data: {
                report_id: report_id,
                revision_id: $("[name=revision]").val(),
                site_id: site_id,
                year: report_year,
                month: report_month
            },
            success: function(res) {
                location.reload();
            }
        });
    });
    // END report revision

    //Load chart, box
    function showLoading() {
        $("#loader").show();
        $("body").css("cursor", "wait");
    }

    function hideLoading() {
        // $("#loader").fadeOut(100);
        $("body").css("cursor", "default");
    }

    function loadChart() {
        showLoading();
        $.ajax({
            url: loadCharUrl,
            type: "GET",
            data: {
                month: month_chart,
                year: year_chart,
                site_id: site_id,
                cv_key: cv_key,
                site_target_key: site_target_key,
                kpi_list: kpi_list
            },
            success: function(res) {
                $('.chart_image').html(res);
            },
            complete: function() {
                hideLoading();
            }
        });
    }
    loadChart();

    function loadBox() {
        showLoading();
        $.ajax({
            url: loadBoxUrl,
            type: "GET",
            data: {
                month: month_box,
                year: year_box,
                site_id: site_id,
                cv_key: cv_key,
                site_target_key: site_target_key,
                kpi_list: kpi_list
            },
            success: function(res) {
                ajax_respone_check(res, true);
                $('.box_image').html(res);
            },
            // error: function(){
            //     $('#loader').text('Loading Error');
            // },
            complete: function() {
                hideLoading();
            }
        });
    }
    loadBox();

    //Edit button
    $('.editReportItemsBtn').click(function() {
        $(this).parents('.reportSection').addClass('editable');
        $(this).parents('.box').find('.editableItem').hide();
        $(this).parents('.box').find('.edit_title').show();
        $(this).parents('.box').find('.edit_description').show();
        //Edit slide image
        $(this).parents('.box').find('.file_img_before').show();
        $(this).parents('.box').find('.uploadimage').show();
    });

    //Arrange slide
    $('#reorderSlideBtn').click(function() {
        reorderNumbers();
    });

    function upList(tr_elm) {
        var tbody = $('#reorderTbody');
        if ($(tr_elm).prev().hasClass('reorderTr')) {
            $(tr_elm).insertBefore($(tr_elm).prev(".reorderTr")[0]);
            reorderNumbers();
        }
    }

    function downList(tr_elm) {
        var tbody = $('#reorderTbody');
        if ($(tr_elm).next().hasClass('reorderTr')) {
            $(tr_elm).insertAfter($(tr_elm).next(".reorderTr")[0]);
            reorderNumbers();
        }
    }

    function reorderNumbers() {
        $('#reorderTbody .reorderTr').each(function(index, elm) {
            $(this).find('.oni_slides_number_output').html(index + 1);
            $(this).find('.oni_slides_number').val(index + 1);
        })
    }

    $(function() {
        $('.upListBtn').on('click', function() {
            upList($(this).parents('.reorderTr'));
        });
        $('.downListBtn').on('click', function() {
            downList($(this).parents('.reorderTr'));
        });
    });

    //Submit arrange slide
    $("#commit").click(function() {
        var x = $('.reorderTr');
        var data = [];
        var i = 0;
        x.each(function() {
            var slide_id = $(this).attr('slide_id');
            var slide_index = $(this).index();
            data.push({ 'slide_id': slide_id, 'slide_index': slide_index });
        });
        $.post(editOrderNum, { data }, function(status) {
            ajax_respone_check(status, true);
            if (status.trim() == 'success') {
                console.log('Success');
                location.reload();
            } else {
                console.log("Error");
            }
        });
    });

    //send to google slide api
    $('#send-google-slide').click(function(e){
        $.get('/shishimai/OttExport', {
            report_id: report_id,
            month: month_chart,
            year: year_chart,
            site_id: site_id,
            cv_key: cv_key,
            site_target_key: site_target_key,
            kpi_list: kpi_list
        }, function(results) {
            var res = JSON.parse(results);
            console.log(res);
            var url = 'https://docs.google.com/presentation/d/' + res.presentation_id + '/edit';
            window.open(url, '_blank');
        });
    });
});