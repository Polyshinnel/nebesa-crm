function getUrlParameter(sParam) {
    let sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
}


$(function() {
    $( ".sortable" ).sortable({
    connectWith: ".connectedSortable",
    dropOnEmpty: true,
    remove: function(event, ui) {
        let currCounter = parseInt($(this).parent().find('.container__header-counter p').html());
        currCounter = currCounter - 1;
        $(this).parent().find('.container__header-counter p').html(currCounter)
    },
    receive: function( event, ui ) {
        let idStage = $(this).attr('data-stage');
        let idDeal = ui.item.attr('data-deal');

        let funnelId = getUrlParameter('funnel_id');
        if(!funnelId) {
            funnelId = 1
        }

        let currCounter = parseInt($(this).parent().find('.container__header-counter p').html());
        currCounter = currCounter + 1;
        $(this).parent().find('.container__header-counter p').html(currCounter)

        $.ajax({
            url: '/change-stage',
            method: 'post',
            dataType: 'html',
            data: {
                'funnel_id': funnelId,
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

    let funnelId = getUrlParameter('funnel_id');
    if(!funnelId) {
        funnelId = 1
    }

    console.log(funnelId)

    $.ajax({
        url: '/add-deal',
        method: 'post',
        dataType: 'json',
        data: {
            'funnel_id': funnelId,
            'deal_num': dealNum,
        },
        success: function(data){
            let dealId = data.deal_id;
            let url = '/card/'+dealId+'?funnel_id='+funnelId
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
    let idStage = 7

    let funnelId = getUrlParameter('funnel_id');
    if(!funnelId) {
        funnelId = 1
    }

    if(funnelId == 2) {
        idStage = 12
    }

    $.ajax({
        url: '/change-stage',
        method: 'post',
        dataType: 'html',
        data: {
            'funnel_id': funnelId,
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
    let idStage = 6

    let funnelId = getUrlParameter('funnel_id');
    if(!funnelId) {
        funnelId = 1
    }

    if(funnelId == 2) {
        idStage = 13
    }

    $.ajax({
        url: '/change-stage',
        method: 'post',
        dataType: 'html',
        data: {
            'funnel_id': funnelId,
            'deal_id': idDeal,
            'stage_id': idStage,
        },
        success: function(data){
            let url = '/'
            $(location).attr('href',url);
        }
    });
})

function createSearchUrl(value) {
    let obj = {
        query: value
    }

    return '/search?' + $.param(obj)
}

$('.search-btn').on('click',function () {
    let query = $('#search').val()
    let url = createSearchUrl(query)
    $(location).attr('href',url);
})

$('#search').keydown(function(e) {
    if(e.keyCode === 13) {
        let query = $(this).val()
        let url = createSearchUrl(query)
        $(location).attr('href',url);
    }
});

$('.work-area-info-common-stage-block').on('click',function () {
    $('.work-area-info-common-stage-list').slideToggle();
})

$('.work-area-info-common-stage-list li').on('click', function () {
    let idStage = $(this).attr('data-id')
    let className = $(this).attr('data-class')
    let name = $(this).html()
    let workAreaBlock = $('.work-area-info-common-stage-block')
    let classArr = workAreaBlock.attr('class');
    classArr = classArr.split(' ')
    let clearClass = classArr[0]
    workAreaBlock.attr('class', clearClass)
    workAreaBlock.addClass(className)
    workAreaBlock.find('h4').html(name)
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();


    $('.work-area-info-common-stage-list').slideUp()

    let funnelId = getUrlParameter('funnel_id');
    if(!funnelId) {
        funnelId = 1
    }

    $.ajax({
        url: '/change-stage',
        method: 'post',
        dataType: 'html',
        data: {
            'funnel_id': funnelId,
            'deal_id': idDeal,
            'stage_id': idStage,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})

$('.work-area-info-sms-btn').on('click',function () {
    $('.work-area-info-sms-form').slideToggle();
});

$('.work-area-info-sms-btn-send').on('click', function () {
    let customerNum = $('#sms-num').val();
    let smsText = $('#text-msg-sms').val();
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();

    $.ajax({
        url: '/send-sms',
        method: 'post',
        dataType: 'json',
        data: {
            'customer_num': customerNum,
            'msg_text': smsText,
            'deal_id': idDeal
        },
        success: function(data){
            console.log(data)
            $('.sms-msg-info').html(data.msg);
            setTimeout(function(){
                window.location.reload(true);
            }, 2000);
        }
    });
})

$('.work-area__header-title_switch').on('click', function () {
    $('.funnel-list').slideToggle();
})