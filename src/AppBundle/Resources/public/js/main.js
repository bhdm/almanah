$(document).ready(function(){
    $('#login').click(function(){
        $('#loginRegisterModal').modal();
    });

    $('.enrolment').click(function(){
        $('#enrolmentModal').modal();
    });

    $('.owl-slider').owlCarousel({
        loop:true,
        margin:10,
        nav: true,
        navText: [' ',' '],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });

    $('.event-carusel').owlCarousel({
        loop:true,
        margin:10,
        nav: false,
        lazyLoad : true,
        navText: [' ',' '],
        autoPlay: true,
        autoplaySpeed: 10,
        autoplayHoverPause: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:4
            }
        }
    });


    //$(".university").chosen({disable_search_threshold: 10});


    $(".county").chosen({disable_search_threshold: 10});


    //$(".city").chosen({disable_search_threshold: 10});

    //$(".city").chosen({
    //    type: 'GET',
    //    url: Routing.generate('get_city')+'?city='+$(this).val(),
    //    dataType: 'json'
    //}, function (data) {
    //    var results = [];
    //
    //    $.each(data, function (i, val) {
    //        results.push({ value: val.value, text: val.text });
    //    });
    //
    //    return results;
    //});


    $('.phone').mask('+7 (000) 000-00-00');

    $('.event-carusel').trigger('play.owl.autoplay', 10000);
});