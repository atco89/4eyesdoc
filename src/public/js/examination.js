var app = new App;
var scrollSpeed = 300;
var textEditorSize = 300;

$(document)

    .ready(function () {
        app.tinymceRender('[data-type="tinymce"]', textEditorSize);
    })

    .on('click', '#examination-steps li a:not(#download-examination)', function () {
        let hash = $(this).data('hash');
        $('body').scrollspy({target: '.navbar', offset: 75});
        if ($(this).data('hash') !== "") {
            $('#examination-steps').find('li').removeClass('active');
            $(this).parent().addClass('active');
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, scrollSpeed);
        }
    })

    .on('click', '.show-fields', function () {
        let field = $($(this).data('hash'));
        $(this).is(':checked') ? field.show() : field.hide();
    });