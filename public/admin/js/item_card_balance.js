$(document).ready(function () {

    $(document).on('change', '#batch_search', function (e) {
        make_search();
    });
    $(document).on('change', '#store_id_search', function (e) {
        make_search();
    });
    $(document).on('change', '#item_code_search', function (e) {
        make_search();
    });
    $(document).on('change', '#BatchQuantityStatus', function (e) {
        var status = $(this).val();
        if (status != "all") {
            $('#BatchQuantitySearch').show();
            $('#BatchQuantitySearch').val();
        
        } else {
            $('#BatchQuantitySearch').hide();
            $('#BatchQuantitySearch').val();
            make_search();
        }



    });

    $(document).on('input', '#BatchQuantity', function (e) {
        var status = $(this).val();
        if (status != '') {
            make_search();
        }
    });
    $(document).on('change', '#TypeBatches', function (e) {
        make_search();
    });




    function make_search() {
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_search_url").val();
        var store_id_search = $("#store_id_search").val();
        var batch_search = $("#batch_search").val();
        var item_code_search = $("#item_code_search").val();
        var TypeBatches = $("#TypeBatches").val();
        var BatchQuantity = $("#BatchQuantity").val();
        var BatchQuantityStatus = $("#BatchQuantityStatus").val();
        


        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                store_id_search: store_id_search, BatchQuantityStatus: BatchQuantityStatus,
                batch_search: batch_search, BatchQuantity: BatchQuantity,
                item_code_search: item_code_search, TypeBatches: TypeBatches,
                "_token": token_search
            },
            success: function (data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function () { }
        });
    }

});


