jQuery(function ($) {
    //Active highlight js
    hljs.highlightAll();

    //show hide user metadata post box
    $('#user-metadata-viewer-id button.handlediv').on('click', function(){
        $('#user-metadata-viewer-id').toggleClass('closed');
    });

    //filter by meta key
    $("#meta_key_filter").on('keyup', function () {
        $(".metadata-viewer-table tbody").find("tr").hide().filter(":contains('" + $(this).val() + "')").show();
    });
});