let $ = require('jquery');
global.$ = global.jQuery = $;
window.jQuery = $;
window.$ = $;

require('bootstrap');
require('@fortawesome/fontawesome-free/js/all');
require('select2');
require('bootstrap-star-rating');

$(document).ready(function () {
    $(document).on('click', '.notification .delete', function(){
        $(this).parent().find('.js-message').html();
        $(this).parent().hide();
    });

    $('.badgebox').on('click', function(){
        let $parent = $(this).parent();
        let $color = $(this).attr('data-color');

        if($parent.hasClass('btn-outline-'+$color)) {
            $parent.removeClass('btn-outline-'+$color).addClass('btn-'+$color);
        } else {
            $parent.removeClass('btn-'+$color).addClass('btn-outline-'+$color);
        }
    });

    $('.badgebox').each(function(){
        let $parent = $(this).parent();
        let $color = $(this).attr('data-color');

        if($(this).is(':checked')){
            $parent.removeClass('btn-outline-'+$color).addClass('btn-'+$color);
        }
    })

    $('.show_more').on('click', function(){
        let $target = $(this).data('target');
        $($target).toggle();
    });

    $(".select2").select2();
    $(".star-rating-new").rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showCaption: false,
        starCaptions: {
            0.5: '0.5 / 5',
            1: '1 / 5',
            1.5: '1.5 / 5',
            2: '2 / 5',
            2.5: '2.5 / 5',
            3: '3 / 5',
            3.5: '3.5 / 5',
            4: '4 / 5',
            4.5: '4.5 / 5',
            5: '5 / 5',
        },
        clearButton: '<i class="fas fa-minus-circle"></i>'
    });

    $(".star-rating").rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showCaption: false,
        starCaptions: {
            0.5: '0.5 / 5',
            1: '1 / 5',
            1.5: '1.5 / 5',
            2: '2 / 5',
            2.5: '2.5 / 5',
            3: '3 / 5',
            3.5: '3.5 / 5',
            4: '4 / 5',
            4.5: '4.5 / 5',
            5: '5 / 5',
        }
    });

    $('.star-rating').on('rating:change', function(event, value){
        let $eval = value
        let $user = $(this).attr('data-user')
        let $tutos = $(this).attr('data-tutos')
        let $url = $(this).attr('data-url')
        let datas = {}
        datas['eval'] = $eval
        datas['user'] = $user
        datas['tutos'] = $tutos

        $.ajax({
            url: $url,
            method: 'POST',
            dataType: 'json',
            data: datas,
            async: true,
        }).done(function(result){
            console.log(result)
        }).fail(function(jqXHR, textStatus, error){
            let err = JSON.parse(jqXHR.responseText);
            alert(err.title);
        }).always(function(){

        });
    });

    $(".star-rating-fix").rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        readonly: 'true',
        showCaption: false,
        starCaptions: {
            0.5: '0.5 / 5',
            1: '1 / 5',
            1.5: '1.5 / 5',
            2: '2 / 5',
            2.5: '2.5 / 5',
            3: '3 / 5',
            3.5: '3.5 / 5',
            4: '4 / 5',
            4.5: '4.5 / 5',
            5: '5 / 5',
        }
    });
});

