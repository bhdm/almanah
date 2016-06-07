
var jcrop_api = null;

function showCoords(selection, container)
{
    container.children('input[name="x1"]').val(selection.x);
    container.children('input[name="y1"]').val(selection.y);
    container.children('input[name="x2"]').val(selection.x2);
    container.children('input[name="y2"]').val(selection.y2);
};



function getImage(data,container, first){
    first = first || 0;
    if ( data.data.error != undefined ) {
        $('.error-msg').fadeIn();
        $('.error-msg').html(data.data.error);
        var control = container.children('div').children('input[type=file]');
        //console.log(ttt = control);
        control.replaceWith( control = control.clone( true ) );

    }else{
        $('.error-msg').fadeOut();

        //$(".imgareaselect-selection").parent().remove();
        //$(".imgareaselect-outer").remove();

        var fileDoc = container.children('.fileDoc');

        //fileDoc.html('<img src=""  brightness="0" contrast="0" />');
        var container = container;
        if (jcrop_api != null){
            jcrop_api.destroy();
            fileDoc.children('img').removeAttr('style');
            //jcrop_api.setImage(data.data.img);
        }
        fileDoc.children('img').attr('src',data.data.img);
        $('#thumbail').val(data.data.img);
        fileDoc.children('.jcrop-holder').children('img').attr('src',data.data.img);
        var type = container.children('.jq-file').children('input[type=file]').attr('id');

        if ( first == 0 ){
            var maxHeight = 400;
            var maxWidth = 400;

                fileDoc.children('img').Jcrop({
                    boxHeight: maxHeight,
                    boxWidth:  maxWidth,
                    onChange:   function(c){showCoords(c, container) },
                    onSelect:   function(c){showCoords(c, container) },
                    aspectRatio: 4 / 3
                },function(){
                    jcrop_api = this;
                });
                fileDoc.children('.jcrop-holder').children('div').children('div').children('.jcrop-tracker').addClass('imgareaselect-selection2');
        }else{
            var maxHeight = 600;
            var maxWidth = 600;
            fileDoc.children('img').Jcrop({
                boxHeight: maxHeight,
                boxWidth:  maxWidth,
                onChange:   function(c){showCoords(c, container) },
                onSelect:   function(c){showCoords(c, container) },
                aspectRatio: 4 / 3
            },function(){
                jcrop_api = this;
            });
            fileDoc.children('.jcrop-holder').children('div').children('div').children('.jcrop-tracker').addClass('imgareaselect-selection2');
        }


    }
}



$(document).ready(function(){

    $( ".slider-vertical-contrast" ).on( "slidestop", function( event, ui ) {
            var container = $(this).parent().parent().parent();
            //console.log(container);
            var type = container.children('.jq-file').children('input[type=file]').attr('id');
            var contrast = ui.value;
            var brightness = container.children('.fileDoc').children('img').attr('brightness');
            //var contrastNow = container.children('.fileDoc').children('img').attr('contrast');

            container.children('.fileDoc').children('img').attr('contrast',contrast);
            $.ajax({
                url: Routing.generate('setting_image', {'type': type, 'contrast': contrast, 'brightness' : brightness }),
                type: 'POST',
                success: function(msg){ getImage(msg, container); },
                error:function (error) {
                    console.log(error);
                }
            });
            return false;
        }
    );

    $( ".slider-vertical-brightness" ).on( "slidestop", function( event, ui ) {
            var container = $(this).parent().parent().parent();
            //console.log(container);
            var type = container.children('.jq-file').children('input[type=file]').attr('id');

            var brightness = ui.value;

            var contrast = container.children('.fileDoc').children('img').attr('contrast');
            //var contrastNow = container.children('.fileDoc').children('img').attr('contrast');

            container.children('.fileDoc').children('img').attr('brightness',brightness);
            $.ajax({
                url: Routing.generate('setting_image', {'type': type, 'contrast': contrast, 'brightness' : brightness }),
                type: 'POST',
                success: function(msg){ getImage(msg, container); },
                error:function (error) {
                    console.log(error);
                }
            });
            return false;
        }
    );


    //          Загрузка файла
    var file;


    $('input.fileAjax').on('click', function(event){
        $(this).attr("value", "");
        $(this).val("");
    });

    $('input.fileAjax').on('change', function(event){
        var container = $(this).parent();
        if (container.hasClass('fileAjax')){
            container = container.parent();
        }
        var progressbar = container.children('.progress');
        var navigateFile = container.children('.navigateFile');

        //return false;

        file = event.target.files[0];
        var formData = new FormData();
        formData.append('file', file);
        //progressbar.css('display','block');
        //progressbar.attr({value:0, max:100});

        var type = container.children('.jq-file').children('input[type=file]').attr('id');


        $.ajax({
            url: Routing.generate('upload_document', {'type': type}),
            type: 'POST',

            success: function(msg){
                //progressbar.css('display','none');
                getImage(msg, container, 1);
                $('.navigateFile').css('display','mome');
                navigateFile.css('display','block');
            },
            error:function (error) {
                console.log(error);
                var $popup = $('<div class="flash-message">' + error.responseJSON.data.error  + '</div>');
                $popup.insertAfter('body');
                setTimeout(function() {
                    $popup.fadeOut('slow', function() { $popup.remove(); });
                }, 2000);
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
    });



    $('.rotateLeft').click(function(){
        var container = $(this).parent().parent();
        var type = container.children('.jq-file').children('input[type=file]').attr('id');
        $.ajax({
            url: Routing.generate('image_rotate', {'type': type, 'rotate': 'left'}),
            type: 'POST',
            success: function(msg){
                getImage(msg, container);
                $('#turn').val(parseInt($('#turn').val())-1);
            },
            error:function (error) {
                console.log(error);
            }
        });
    });

    $('.rotateRight').click(function(){
        var container = $(this).parent().parent();
        var type = container.children('.jq-file').children('input[type=file]').attr('id');
        $.ajax({
            url: Routing.generate('image_rotate', {'type': type, 'rotate': 'right'}),
            type: 'POST',
            success: function(msg){
                getImage(msg, container);
                $('#turn').val(parseInt($('#turn').val())+1);
            },
            error:function (error) {
                console.log(error);
            }
        });
    });

    $('.cropImage').click(function(){
        var container = $('.file-container');
        var x1 = container.children('input[name="x1"]').val();
        var x2 = container.children('input[name="x2"]').val();
        var y1 = container.children('input[name="y1"]').val();
        var y2 = container.children('input[name="y2"]').val();

        var width = container.children('.fileDoc').children('img').css('width');
        var height = container.children('.fileDoc').children('img').css('height');
        var type = container.children('.jq-file').children('input[type=file]').attr('id');
        $.ajax({
            url: Routing.generate('crop_image', {'type': type,  'width' : width, 'height' : height, 'x1': x1, 'y1' : y1, 'x2' : x2, 'y2' : y2 }),
            type: 'POST',
            success: function(msg){ getImage(msg, container); },
            error:function (error) {
                console.log(error);
            }
        });
    });

    //$('.brightness').click(function(){
    //    var container = $(this).parent().parent();
    //
    //    var type = container.children('.jq-file').children('input[type=file]').attr('id');
    //    var brightness = $(this).attr('data-brightness');
    //    var brightnessNow = container.children('.fileDoc').children('img').attr('brightness');
    //    if (brightness == 'plus'){
    //        brightnessNow = brightnessNow + 20;
    //    }else{
    //        brightnessNow = brightnessNow - 20;
    //    }
    //    container.children('.fileDoc').children('img').attr('brightness',brightnessNow);
    //    //console.log(t=container);
    //    $.ajax({
    //        url: Routing.generate('brightness_image', {'type': type, 'brightness': brightnessNow }),
    //        type: 'POST',
    //        success: function(msg){ getImage(msg, container); },
    //        error:function (error) {
    //            console.log(error);
    //        }
    //    });
    //});

    //$('.contrast').click(function(){
    //    var container = $(this).parent().parent();
    //
    //    var type = container.children('.jq-file').children('input[type=file]').attr('id');
    //    var contrast = $(this).attr('data-contrast');
    //    var contrastNow = container.children('.fileDoc').children('img').attr('contrast');
    //    if (contrast == 'plus'){
    //        contrastNow = contrastNow + 20;
    //    }else{
    //        contrastNow = contrastNow - 20;
    //    }
    //    container.children('.fileDoc').children('img').attr('contrast',contrastNow);
    //    console.log(t=container);
    //    $.ajax({
    //        url: Routing.generate('contrast_image', {'type': type, 'contrast': contrastNow }),
    //        type: 'POST',
    //        success: function(msg){ getImage(msg, container); },
    //        error:function (error) {
    //            console.log(error);
    //        }
    //    });
    //});

});