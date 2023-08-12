$(document).ready(function() {
    $(document).on("click", "#details_show", function (e) {
        var delegate_code = $(this).data('delegate_code');
        var token_search = $("#token_search").val();
        var ajax_load_add_invoice = $("#ajax_show_details").val();
        jQuery.ajax({
            url: ajax_load_add_invoice,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                '_token': token_search,
                delegate_code: delegate_code
            },
            success: function (data) {
                $("#DelegatesDetailsModel").modal("show");
                $("#DelegatesDetailsModaMlBody").html(data);

            },
            error: function () {
                alert("  error in AddNewOfferPrice_show");
            },
        });
    });
    $(document).on('input', '#search_by_text', function(e) {
        
        make_search();
    });


    $('input[type=radio][name=searchbyradio]').change(function() {
        make_search();
    });

    function make_search() {
        var search_by_text = $("#search_by_text").val();
        var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var url = $("#ajax_search_url").val();

        
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
                search_by_text: search_by_text,
                searchbyradio: searchbyradio,
            
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {}
        });
    }
    $(document).on('click', '#ajax_pagination_in_search ', function(e) {
        e.preventDefault();
        var search_by_text = $("#search_by_text").val();
        var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
        
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search,
                searchbyradio: searchbyradio,
            
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {}
        });
    });
});