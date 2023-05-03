$(function() {
    $( ".sortable" ).sortable({
    connectWith: ".connectedSortable",
    remove: function(event, ui) {
        let currCounter = parseInt($(this).parent().find('.container__header-counter p').html());
        currCounter = currCounter - 1;
        $(this).parent().find('.container__header-counter p').html(currCounter)
    },
    receive: function( event, ui ) {
        let idStage = $(this).attr('data-stage');
        let idDeal = ui.item.attr('data-deal');

        let currCounter = parseInt($(this).parent().find('.container__header-counter p').html());
        currCounter = currCounter + 1;
        $(this).parent().find('.container__header-counter p').html(currCounter)

        $.ajax({
            url: '/change-stage',
            method: 'post',
            dataType: 'html',
            data: {
                'deal_id': idDeal,
                'stage_id': idStage,
            },
            success: function(data){
            }
        });
    },
    placeholder: "card-highlight"
    }).disableSelection();
});

$('.work-area__view-btn').on('click',function () {
    $('.work-area__view-btn').each(function () {
        $(this).removeClass('work-area__view-btn_active')
    });
    $(this).addClass('work-area__view-btn_active')
    let dataName = $(this).attr('data-name');
    if(dataName == 'canban') {
        $('.work-area__columns').css('display','flex')
        $('.work-area__rows').css('display','none')
    }

    if(dataName == 'list') {
        $('.work-area__columns').css('display','none')
        $('.work-area__rows').css('display','block')
    }
});

$('.work-area__header-add-btn, .add-new-order').on('click',function () {
    $('.fancy-box').fadeIn(300)
});

$('.close-form').on('click',function () {
    $('.fancy-box').fadeOut(300);
    $('#order_num').val('')
})

$('.form-block__btn').on('click',function () {
    $('.form-block-form').css('display','none')
    $('.await-form').css('display','flex')
    let dealNum = $('#deal-num').val();

    $.ajax({
        url: '/add-deal',
        method: 'post',
        dataType: 'json',
        data: {
            'deal_num': dealNum,
        },
        success: function(data){
            let dealId = data.deal_id;
            let url = '/card/'+dealId
            $(location).attr('href',url);
        }
    });
})

$('.add-text-message-btn').on('click',function () {
    let text_msg = $('#msg-text').val()
    console.log(text_msg)
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let type = 'message';


    $.ajax({
        url: '/add-event',
        method: 'post',
        dataType: 'json',
        data: {
            'deal_id': idDeal,
            'text_data': text_msg,
            'type': type,
        },
        success: function(data){
            $('#msg-text').val('')
            $('.work-area-info-messages-area').append('<div class="work-area-info-message">\n' +
            '<div class="work-area-info-message-header">\n' +
            '<div class="work-area-info-message-header-icon">\n' +
            '<img src="/assets/img/chat-msg.svg" alt="">\n' +
            '</div>\n' +
            '<div class="work-area-info-message-header-text">\n' +
            '<h4>Сообщение:</h4>\n' +
            '<p>'+data.date+'</p>\n' +
            '</div>\n' +
            '</div>\n' +
            '\n' +
            '<div class="work-area-info-message-body">\n' +
            '<p>'+text_msg+'</p>\n' +
            '</div>\n' +
            '<p class="work-area-info-message-from">от '+data.username+'</p>\n' +
            '</div>')
        }
    });
})

$('.end-btn').on('click',function () {
    let text_msg = $('#msg-text').val()
    console.log(text_msg)
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let idStage = 6

    $.ajax({
        url: '/change-stage',
        method: 'post',
        dataType: 'html',
        data: {
            'deal_id': idDeal,
            'stage_id': idStage,
        },
        success: function(data){
            let url = '/'
            $(location).attr('href',url);
        }
    });
})

$('.cancel-btn').on('click',function () {
    let text_msg = $('#msg-text').val()
    console.log(text_msg)
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let idStage = 7

    $.ajax({
        url: '/change-stage',
        method: 'post',
        dataType: 'html',
        data: {
            'deal_id': idDeal,
            'stage_id': idStage,
        },
        success: function(data){
            let url = '/'
            $(location).attr('href',url);
        }
    });
})