jQuery(function ($) {
    //Active highlight js
    hljs.highlightAll();

    //show hide user metadata post box
    $('#user-metadata-viewer-id button.handlediv').on('click', function(){
        $('#user-metadata-viewer-id').toggleClass('closed');
    });

    //filter by meta key
    $("#meta_key_filter").on('keyup', function () {
        $(".metadata-viewer-table tbody").find("tr").addClass('hidden').filter(":contains('" + $(this).val() + "')").removeClass('hidden');
        
        if($(".metadata-viewer-table tbody tr").length === $(".metadata-viewer-table tbody tr.hidden").length){
            //show not found message
            $(".not-metadata-found").removeClass("hidden");
          } else {
            //hide not found message
            $(".not-metadata-found").addClass("hidden");
        }
    });
});