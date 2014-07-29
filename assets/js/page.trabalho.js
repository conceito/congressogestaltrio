$(document).ready(function () {

    var num_authors = 1;

    $('a[data-author-tab="2"]', '#frm_trabalho').hide();
    $('a[data-author-tab="3"]', '#frm_trabalho').hide();

    $('.add-author-tab').on('click', function (e) {
        e.preventDefault();

        if (num_authors == 1) {
            $('a[data-author-tab="2"]').show();
            num_authors = 2;
        } else if (num_authors == 2) {
            $('a[data-author-tab="2"]').show();
            $('a[data-author-tab="3"]').show();
            $(this).hide();
            num_authors = 3;
        }

    });


    $('.basic-editor').tinymce({
        // Location of TinyMCE script
        script_url: CMS.base_url + 'libs/tiny_mce356/tiny_mce.js',
        language: "pt", // change language here
        convert_urls: false,// true = relativos , false = path absolut
        width: "100%",
//        entity_encoding : "raw",

        // General options
//        theme : "advanced",
        plugins: "visualblocks,safari,pagebreak,paste,directionality,noneditable,nonbreaking,xhtmlxtras",

        /* removed:
         * pagebreak, forecolor, outdent,indent, pasteword
         * */
        theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright",
        height: 20,
        cleanup: true,
        init_instance_callback: function (inst) {
//            console.log($(tinyMCE.get(inst.editorId).getElement()));
            //for (editorId in tinyMCE.editors) {
                var orig_element = $(tinyMCE.get(inst.editorId).getElement());
                var editorHeight = orig_element.data('editor-height');

//            console.log('editorHeight', editorHeight);

//                console.log('editorId', editorId);
//                if (id === 'field_resumo') {
//                    console.log(numChars);
//                }
            //}
        },
        handle_event_callback: function (e, inst) {

            var orig_element = $(tinyMCE.get(inst.editorId).getElement());
            var name = orig_element.attr('name');
            var id = orig_element.attr('id');
            var numChars = orig_element.data('editor-limit');

            var body = tinyMCE.get(inst.editorId).getBody(), text = tinymce.trim(body.innerText || body.textContent);
            var left = numChars - text.length;

//            console.log('left', left);

            if (left < 0) {
                $('#'+id+'_countBox').addClass('overquota');
            } else {
                $('#'+id+'_countBox').removeClass('overquota');
            }

            $('#'+id+'_countBox').find('.countBox').text(left);
//           console.log(value);
        },
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "none",
        theme_advanced_resizing: false,
        theme_advanced_resize_horizontal: false,
//        theme_advanced_blockformats: "p,h1,h2,h3,h4",
        paste_auto_cleanup_on_paste: true,
        paste_block_drop: true, // true = não permite arrastar para conteúdo

        // Example content CSS (should be your site CSS)
        content_css : CMS.base_url+"assets/css/simple-editor.css",
        class_filter: function (cls, rule) {
            // Skip classes that are inside id like #tinymce
            if (/^#.*/.test(rule))
                return false;

            // Pass though the rest
            return cls;
        }
    });


    $('.proposal-editor').tinymce({
        // Location of TinyMCE script
        script_url: CMS.base_url + 'libs/tiny_mce356/tiny_mce.js',
        language: "pt", // change language here
        convert_urls: false,// true = relativos , false = path absolut
        width: "100%",

//        entity_encoding : "raw",
        // General options
//        theme : "advanced",
        plugins: "visualblocks,safari,pagebreak,layer,table,save,advhr,advimage,advlink,inlinepopups,paste,directionality,noneditable,nonbreaking,xhtmlxtras",

        /* removed:
         * pagebreak, forecolor, outdent,indent, pasteword
         * */
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,|,formatselect,|,pastetext",

        cleanup: true,
        handle_event_callback: function () {
            var body = tinymce.get('field_proposta').getBody(), text = tinymce.trim(body.innerText || body.textContent);
            var value = 8500 - text.length;
//            return {
//                chars: text.length,
//                words: text.split(/[\w\u2019\'-]+/).length
//            };
            $('#charPropostaLimit').find('.countBox').text(text.length);
//           console.log(value);
        },
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        theme_advanced_resize_horizontal: false,
        theme_advanced_blockformats: "p,h1,h2,h3,h4",
        paste_auto_cleanup_on_paste: true,
        paste_block_drop: true, // true = não permite arrastar para conteúdo

        // Example content CSS (should be your site CSS)
        content_css : CMS.base_url+"assets/css/simple-editor.css",
        class_filter: function (cls, rule) {
            // Skip classes that are inside id like #tinymce
            if (/^#.*/.test(rule))
                return false;

            // Pass though the rest
            return cls;
        }
    });

//    setTimeout(function(){
//        console.log('tinyMCE.editors', tinyMCE.editors);
//
//    }, 1000);


});