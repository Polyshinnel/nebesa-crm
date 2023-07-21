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
            if(data.err === 'none') {
                $(location).attr('href',url);
            } else {
                let text = data.err+' ссылка на сделку <a href="'+url+'">Сделка</a>'
                $('.await-text').html(text)
            }
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

$('.add-brigade-list-btn').click(function () {
    $('.add-brigade-list-block').slideToggle();
})

$('.add-brigade-list-block li').click(function () {
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();

    let name = $(this).html()
    let id = $(this).attr('data-id')

    $('.add-brigade-list-block').slideUp()

    $.ajax({
        url: '/add-worker',
        method: 'post',
        dataType: 'json',
        data: {
            'deal_id': idDeal,
            'brigade_name': name,
            'brigade_id': id,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})

$('.products-periods-filter-btn').click(function () {
    $('.products-periods-filter-block').slideToggle()
})

$('.close-filter').click(function () {
    $('.products-periods-filter-block').slideUp()
})

$('.work-area__rows-btn_acept-filter').click(function () {
    let query = window.location.search
    let baseUrl = window.location.pathname

    let params = {}
    let date_start = $('#time-start').val()
    let date_end = $('#time-end').val()
    let status_name = $('#status_name').val()
    let brigade_name = $('#brigade_name').val()

    if(date_start !== '') {
        params.date_start = date_start
    }

    if(date_end !== '') {
        params.date_end = date_end
    }

    if(status_name !== '') {
        params.status_name = status_name
    }

    if(brigade_name !== '') {
        params.brigade_name = brigade_name
    }

    let getQuery = $.param(params)
    let url = baseUrl+'?'+getQuery
    window.location.replace(url)
})


$('.filter-btn__reset').click(function () {
    let url = window.location.pathname;
    window.location.replace(url)
})

$('.work-area__rows-btn-add-payment-position').click(function () {
    $('.position-list-wrapper').append('<div class="position-list-wrapper-line">\n' +
        '    <div class="form-input">\n' +
        '        <label for="">Наименование</label>\n' +
        '        <input type="text" placeholder="Наименование" class="new-product-name">\n' +
        '        <ul class="new-product-name__variants"></ul>\n'+
        '    </div>\n' +
        '\n' +
        '    <div class="form-input">\n' +
        '        <label for="">Количество</label>\n' +
        '        <input type="text" placeholder="Количество" class="new-product-quant">\n' +
        '    </div>\n' +
        '\n' +
        '    <div class="form-input form-input_sm">\n' +
        '        <label for="">Цена</label>\n' +
        '        <input type="text" placeholder="Цена" class="new-product-price">\n' +
        '    </div>\n' +
        '\n' +
        '    <div class="form-input form-input_sm">\n' +
        '        <label for="">Итого</label>\n' +
        '        <input type="text" placeholder="Итого" class="new-product-total">\n' +
        '    </div>\n' +
        '\n' +
        '    <div class="work-area__rows-btn create-product-btn">\n' +
        '        <p>Добавить</p>\n' +
        '    </div>\n' +
        '</div>')
});

$('.form-input_checkbox input').click(function () {
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let prodLine = $(this).parent().parent()
    let prodName = prodLine.find('.prod_pay_name').val()
    let quantity = prodLine.find('.prod_pay_quant').val()
    let price = prodLine.find('.prod_pay_price').val()
    let total = prodLine.find('.total_pay_price').val()
    let product_id = prodLine.find('.delete-payment-btn').attr('data-id')

    let state = 0;

    if ($(this).is(':checked')){
        state = 1;
    }

    let productObj = {
        'deal_id': idDeal,
        'product_name': prodName,
        'quantity': quantity,
        'price': price,
        'total': total,
        'state': state,
        'product_id': product_id
    }

    let json = JSON.stringify(productObj)

    $.ajax({
        url: '/update-deal-detail',
        method: 'post',
        dataType: 'json',
        data: {
            'json': json,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
});

$('.delete-payment-btn').click(function () {
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let productId = $(this).attr('data-id');

    let productObj = {
        'deal_id': idDeal,
        'product_id': productId
    }

    let json = JSON.stringify(productObj)

    $.ajax({
        url: '/delete-deal-detail',
        method: 'post',
        dataType: 'json',
        data: {
            'json': json,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})

$(document).on('click', '.create-product-btn', function () {
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let prodLine = $(this).parent()
    let prodName = prodLine.find('.new-product-name').val()
    let prodQuant = prodLine.find('.new-product-quant').val()
    let prodPrice = prodLine.find('.new-product-price').val()
    let prodTotal = prodLine.find('.new-product-total').val()


    let prodObj = {
        'deal_id': idDeal,
        'product_name': prodName,
        'quantity': prodQuant,
        'price': prodPrice,
        'total': prodTotal,
        'state': 0
    }

    let json = JSON.stringify(prodObj)

    $.ajax({
        url: '/create-deal-detail',
        method: 'post',
        dataType: 'json',
        data: {
            'json': json,
        },
        success: function(data){
            window.location.reload(true);
        }
    });

})


$('#add-payment').click(function () {
    let url = window.location.pathname;
    url = url.split('/');
    let idDeal = url.pop();
    let money = $('#add-deal-payment').val();

    let productObj = {
        'deal_id': idDeal,
        'payment_sum': money
    }

    let json = JSON.stringify(productObj)

    $.ajax({
        url: '/money-deal-detail',
        method: 'post',
        dataType: 'json',
        data: {
            'json': json,
        },
        success: function(data){
            window.location.reload(true);
        }
    });
})


$(document).ready(function() {
    let workerArea = $('.payment-worker-list');
    $('#payment-search').on('keyup', function(){
        let search = $(this).val()
        if((search !== '') && (search.length > 2)) {
            workerArea.empty()
            $.ajax({
                url: '/worker-deals-search',
                method: 'post',
                dataType: 'json',
                data: {
                    'deal': search,
                },
                success: function(data){
                    console.log(data)
                    if(data.err !== 'none') {
                        workerArea.append('<div class="empty-search">\n' +
                            '                <p>К сожалению поиск не дал результатов</p>\n' +
                            '                <div class="reset-search">\n' +
                            '                    <p>Сбросить</p>\n' +
                            '                </div>\n' +
                            '            </div>')
                    } else {
                        console.log(data.deals)
                        for(let i = 0; i < data.deals.length; i++) {
                            workerArea.append('<div class="work-area__row">\n' +
                                '                    <div class="work-area__row-info">\n' +
                                '                        <a href="/payment-edit/{{ payment.id }}"><h4 class="card-title">'+data.deals[i].name+'</h4></a>\n' +
                                '                        <p class="card-subtitle">'+data.deals[i].dead_name+'</p>\n' +
                                '                        <p class="card-agent">Агент/Мастер: '+data.deals[i].agent_name+'</p>\n' +
                                '                        <p class="card-subtitle"><b>Выплачено:</b> '+data.deals[i].payment_money+'₽/'+data.deals[i].total_money+'₽</p>\n' +
                                '                    </div>\n' +
                                '\n' +
                                '                    <div class="work-area__row-tag-graveyard">\n' +
                                '                        <div class="work-area__row-tag">\n' +
                                '                            <p>'+data.deals[i].tag+'</p>\n' +
                                '                        </div>\n' +
                                '\n' +
                                '                        <div class="work-area__row-graveyard">\n' +
                                '                            <img src="/assets/img/grave.svg" alt="">\n' +
                                '                            <p>'+data.deals[i].funeral+'</p>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '\n' +
                                '                    <div class="work-area__row-stage-info">\n' +
                                '                        <p>Статус:</p>\n' +
                                '\n' +
                                '                        <div class="work-area__row-stage '+data.deals[i].status_class+'">\n' +
                                '                            <h4>'+data.deals[i].status_name+'</h4>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '\n' +
                                '                    <div class="work-area__row-chat-date">\n' +
                                '                        <div class="work-area__row-chat">\n' +
                                '                            <img src="/assets/img/payment/list.svg" alt="">\n' +
                                '                            <p>'+data.deals[i].task_done+'/'+data.deals[i].tasks_totals+'</p>\n' +
                                '                        </div>\n' +
                                '                        <div class="work-area__row-date">\n' +
                                '                            <img src="/assets/img/payment/money.svg" alt="">\n' +
                                '                            <p>'+data.deals[i].money_to_pay+'р / '+data.deals[i].total_money+'р</p>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '\n' +
                                '                    <div class="work-area__row-chat-date">\n' +
                                '                        <div class="work-area__row-chat">\n' +
                                '                            <img src="/assets/img/payment/brigade.svg" alt="">\n' +
                                '                            <p>'+data.deals[i].brigade_name+'</p>\n' +
                                '                        </div>\n' +
                                '\n' +
                                '                        <div class="work-area__row-date">\n' +
                                '                            <img src="/assets/img/calendar-card.svg" alt="">\n' +
                                '                            <p>'+data.deals[i].date_create+'</p>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '                </div>')
                        }
                    }
                }
            });
        }
        if(search.length === 0) {
            window.location.reload(true);
        }
    })
})

$(document).on('click', '.reset-search', function () {
    window.location.reload(true);
})

$(document).on('keyup', '.new-product-name', function () {
    let productName = $(this).val()
    let dataField = $(this).parent().find('.new-product-name__variants')
    if((productName !== '') && (productName.length > 3)) {
        dataField.empty()
        $.ajax({
            url: '/search-product',
            method: 'post',
            dataType: 'json',
            data: {
                'name': productName,
            },
            success: function(data){
                console.log(data)
                if(data.err === 'none') {
                    dataField.fadeIn(300)
                    console.log(data.products)
                    for(let i = 0; i < data.products.length; i++) {
                        dataField.append('<li>'+data.products[i].name+'</li>')
                    }
                }
            }
        });
    }

    if(productName.length === 0) {
        dataField.empty()
        dataField.fadeOut(300)
    }
})

$(document).on('click', '.new-product-name__variants li', function () {
    let name = $(this).html();
    let field = $(this).parent().parent().find('.new-product-name')
    field.val(name)
    $(this).parent().fadeOut(300)
})

$(document).ready(function () {
    $('.prod_pay_quant').on('input', function () {
        let priceField = $(this).parent().parent().find('.prod_pay_price')
        if(priceField.val() !== '') {
            let price = parseFloat(priceField.val())
            let quantity = parseFloat($(this).val())
            let total = price*quantity
            total = total.toFixed(2)
            $(this).parent().parent().find('.total_pay_price').val(total)
        }
    })

    $('.prod_pay_price').on('input', function () {
        let quantField = $(this).parent().parent().find('.prod_pay_quant')
        if(quantField.val() !== '') {
            let quantity = parseFloat(quantField.val())
            let price = parseFloat($(this).val())
            let total = price*quantity
            total = total.toFixed(2)
            $(this).parent().parent().find('.total_pay_price').val(total)
        }
    })
})

$(document).on('input','.new-product-quant', function () {
    let priceField = $(this).parent().parent().find('.new-product-price')
    if(priceField.val() !== '') {
        let quantity = parseFloat($(this).val())
        let price = parseFloat(priceField.val())
        let total = quantity*price
        total = total.toFixed(2)
        $(this).parent().parent().find('.new-product-total').val(total)
    }
})

$(document).on('input','.new-product-price', function () {
    let quantField = $(this).parent().parent().find('.new-product-quant')
    if(quantField.val() !== '') {
        let price = parseFloat($(this).val())
        let quantity = parseFloat(quantField.val())
        let total = quantity*price
        total = total.toFixed(2)
        $(this).parent().parent().find('.new-product-total').val(total)
    }
})