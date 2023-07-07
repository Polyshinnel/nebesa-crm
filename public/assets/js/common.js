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

$('.refresh-payment').click(function () {
    $(this).addClass('rotation-animation')
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    $.ajax({
        url: '/update-payment',
        method: 'post',
        dataType: 'json',
        data: {
            'deal_id': idDeal
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})

$(document).ready(function (){
    let path = window.location.pathname;
    let paymentFlag = false;

    let paymentArr = [
        'payment-board',
        'add-brigade',
        'payment-products',
        'edit-payment-products',
        'add-payment-products',
        'payment-list',
        'payment-edit'
    ]

    pathArr = path.split('/')
    for(let i = 0; i < pathArr.length; i++) {
        if(paymentArr.includes(pathArr[i])){
            paymentFlag = true;
            break;
        }
    }


    $('.sidebar-nav ul li').each(function (){
        $(this).removeClass('sidebar-nav_active')
        let href = $(this).find('a').attr('href')
        if(href == path) {
            $(this).addClass('sidebar-nav_active')
        }
        if((paymentFlag == true) && (href == '/payment-board')) {
            $(this).addClass('sidebar-nav_active')
        }
    })
});

$('.work-area__rows-btn-add-brigade').click(function () {
    $('.brigade-fancy-add').fadeIn(300);
})

$('.close-fancy').click(function () {
    $('.brigade-fancy').fadeOut(300);
})

$('#add-window-brigade').click(function () {
    let name = $('#brigade-add-name').val()
    $.ajax({
        url: '/create-worker',
        method: 'post',
        dataType: 'json',
        data: {
            'name': name
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})

$('.worker-edit-btn').click(function () {
    $('.brigade-fancy-edit').fadeIn(300);
    let id = $(this).attr('data-id');
    $('#brigade-edit-name').attr('data-id', id)
})

$('#edit-window-brigade').click(function () {
    let brigadeElem = $('#brigade-edit-name');
    let name = brigadeElem.val()
    let id = brigadeElem.attr('data-id');
    $.ajax({
        url: '/update-worker',
        method: 'post',
        dataType: 'json',
        data: {
            'id': id,
            'name': name
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})

$('.worker-delete-btn').click(function () {
    let id = $(this).attr('data-id');
    $.ajax({
        url: '/delete-worker',
        method: 'post',
        dataType: 'json',
        data: {
            'id': id,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})


$(document).on('click', '.work-area__rows-products-line-add', function () {
    $('.work-area__rows-products-wrapper').append('<div class="work-area__rows-products-line">\n' +
        '<div class="form-input">\n' +
        '<label for="">Название наименования</label>\n' +
        '<input type="text" placeholder="Название" class="payment-product-name">\n' +
        '</div>\n' +
        '\n' +
        '<div class="form-input form-input-select">\n' +
        '<label for="">Категория</label>\n' +
        '<input type="text" placeholder="Категория" readonly class="payment-product-cat">\n' +
        '\n' +
        '<ul class="form-input-list">\n' +
        '<li>Монтаж</li>\n' +
        '<li>Демонтаж</li>\n' +
        '</ul>\n' +
        '</div>\n' +
        '\n' +
        '<div class="form-input">\n' +
        '<label for="">Цена</label>\n' +
        '<input type="text" placeholder="Цена" class="payment-product-price">\n' +
        '</div>\n' +
        '\n' +
        '<div class="work-area__rows-products-line-add">\n' +
        '<img src="/assets/img/plus-btn.svg" alt="">\n' +
        '</div>\n' +
        '</div>'
    )
})

$(document).on('click', '.form-input-select input', function () {
    $(this).parent().find('.form-input-list').slideToggle()
})

$(document).on('click', '.form-input-list li', function () {
    let val = $(this).html()
    $(this).parent().parent().find('input').val(val)
    $(this).parent().slideUp()
})

$('#create-products').click(function () {
    let productList = []
    $('.work-area__rows-products-line').each(function () {
        let name = $(this).find('.payment-product-name').val()
        let category = $(this).find('.payment-product-cat').val()
        let price = $(this).find('.payment-product-price').val()

        let productItem = {
            'name': name,
            'category': category,
            'price': price
        }

        productList.push(productItem)
    })

    let json = JSON.stringify(productList);

    $.ajax({
        url: '/create-products',
        method: 'post',
        dataType: 'json',
        data: {
            'json': json,
        },
        success: function(data){
            window.location.replace('/payment-products')
        }
    });
})

$('#payment-product-edit').click(function () {
    let productNameInput = $('#payment-product-name')
    let id = productNameInput.attr('data-id')
    let name = productNameInput.val()
    let catName = $('#payment-product-cat').val()
    let price = $('#payment-product-price').val()

    $.ajax({
        url: '/update-product',
        method: 'post',
        dataType: 'json',
        data: {
            'id': id,
            'name': name,
            'category': catName,
            'price': price
        },
        success: function(data){
            window.location.replace('/payment-products')
        }
    });
})

$('.delete-product-btn').click(function () {
    let id = $(this).attr('data-id')
    $.ajax({
        url: '/delete-product',
        method: 'post',
        dataType: 'json',
        data: {
            'id': id,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})