jQuery(function ($) {
    //Active highlight js
    hljs.highlightAll();

    //show hide user metadata post box
    $('#user-metadata-viewer-id button.handlediv').on('click', function(){
        $('#user-metadata-viewer-id').toggleClass('closed');
    });
});