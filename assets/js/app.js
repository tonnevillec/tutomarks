let $ = require('jquery');
global.$ = global.jQuery = $;
window.jQuery = $;
window.$ = $;

require('bootstrap');
require('@fortawesome/fontawesome-free/js/all');
require('select2');
require('bootstrap-star-rating');

import './comments/Comments.jsx'

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

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

    $("#js-form-contact").on('click', function(e){
        e.preventDefault();

        let $email = $('#contact_email').val();
        let $subject = $('#contact_subject').val();
        let $message = $('#contact_message').val();
        let $url = $('#contact_form').attr('action');
        let errors = false;
        let $data = {}

        if($email.length === 0) {
            $('#contact_email').addClass('is-invalid');
            errors = true;
        }

        if($subject.length === 0) {
            $('#contact_subject').addClass('is-invalid');
            errors = true;
        }

        if($message.length < 10) {
            $('#contact_message').addClass('is-invalid');
            errors = true;
        }

        if(errors){
            $('#js-contact-alert-text').html('Champs requis manquant');
            $('#js-contact-alert')
                .addClass('alert-danger')
                .removeClass('alert-success')
                .removeClass('is-hide')
                .show()
            ;
        } else {
            $('#contact_email').removeClass('is-invalid');
            $('#contact_subject').removeClass('is-invalid');
            $('#contact_message').removeClass('is-invalid');

            $data['email'] = $email;
            $data['subject'] = $subject;
            $data['message'] = $message;

            $.ajax({
                url: $url,
                method: 'POST',
                data: $data,
                async: true,
                dataType: 'json'
            }).done(function (datas) {
                $('#js-contact-alert-text').html(datas.message);
                $('#js-contact-alert')
                    .addClass('alert-success')
                    .removeClass('alert-danger')
                    .removeClass('is-hide')
                    .show()
                ;

                $('#contact_subject').val('');
                $('#contact_message').val('');

            }).fail(function (jqXHR, textStatus, error) {
                let err = JSON.parse(jqXHR.responseText);

                $('#js-contact-alert-text').html(err.message);
                $('#js-contact-alert')
                    .addClass('alert-danger')
                    .removeClass('alert-success')
                    .removeClass('is-hide')
                    .show()
                ;
            });
        }
    });
});

