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