$(document).ready(function () {

    $(document).on('change', '#does_has_retailunit', function (e) {

        var uom_id = $('#uom_id').val();
        if (uom_id == '') {
            alert('اختر وحده الاب اولا');
            $('#does_has_retailunit').val("3");
            return false;
        }

        if ($(this).val() == 1) {
            $('#retail_uom_idDiv').show();
            var retail_uom_id = $('#retail_uom_id').val();
            if (retail_uom_id != "") {
                $('.retailed_retail_counter').show();
            } else {
                $('.retailed_retail_counter').hide();
            }

        } else {
            $('.retailed_retail_counter').hide();
            $('#retail_uom_idDiv').hide();

        }
        $('#retail_uom_id').val("");
    });

    $(document).on('change', '#uom_id', function (e) {

        if ($(this).val() != '') {
            var name = $('#uom_id option:selected').text();
            $('.parentuomname').text(name);

            var does_has_retailunit = $('#does_has_retailunit').val();
            if (does_has_retailunit == 1) {

                var retail_uom_id = $('#retail_uom_id').val();
                if (retail_uom_id != "") {
                    $('.retailed_retail_counter').show();
                } else {
                    $('.retailed_retail_counter').hide();
                }

            } else {
                $('.retailed_retail_counter').hide();
                $('#retail_uom_idDiv').hide();
            }

            $('.retailed_parent_counter').show();



        } else {
            $('.parentuomname').text('');
            $('.retailed_retail_counter').hide();
            $('.retailed_parent_counter').hide();
            $('#retail_uom_idDiv').hide();

        }
    });



    $(document).on('change', '#retail_uom_id', function (e) {

        if ($(this).val() != '') {
            var name = $('#retail_uom_id option:selected').text();
            $('.childuomname').text(name);
            $('.retailed_retail_counter').show();


        } else {
            $('.childuomname').text('');
            $('.retailed_retail_counter').hide();


        }
    });

    $(document).on('click', '#to_add_item_card', function (e) {
        var name = $('#name').val();
        if(name ==''){
            alert('من فضلك ادخل اسم الصنف');
            $('#name').focus();
            return false;
        }
        var item_type = $('#item_type').val();
        if(item_type ==''){
            alert('من فضلك ادخل نوع الصنف');
            $('#item_type').focus();
            return false;
        }
        var nainv_itemcard_categories_idme = $('#inv_itemcard_categories_id').val();
        if(nainv_itemcard_categories_idme ==''){
            alert('من فضلك ادخل فئة الصنف');
            return false;
        }
        var uom_id = $('#uom_id').val();
        if(uom_id ==''){
            alert('من فضلك ادخل قياس الاب للصنف');
            $('#uom_id').focus();
            return false;
        }
        var does_has_retailunit = $('#does_has_retailunit').val();
        if(does_has_retailunit ==''){
            alert('من فضلك  ادخل حاله هل للصنف وحده التجزئة  ');
            $('#does_has_retailunit').focus();
            return false;
        };

        if(does_has_retailunit == 1){

            var retail_uom_id = $('#retail_uom_id').val();
            if(retail_uom_id ==''){
                alert('من فضلك ادخل قياس التجزئة للابن للصنف');
                $('#retail_uom_id').focus();
                return false;
            }
            var retail_uom_quantityToParent = $('#retail_uom_quantityToParent').val();
            if(retail_uom_quantityToParent =='' || retail_uom_quantityToParent ==0){
                alert('من فضلك ادخل   عدد الوحدات الوحده تجزئة بالنسبة للاب ');
                $('#retail_uom_quantityToParent').focus();
                return false;
            }
        }

        var price = $('#price').val();
        if(price == ""){
            alert('من فضلك ادخل السعر القطاعي للوحده الاب');
            $('#price').focus();
            return false;
        }
        var nos_gomla_price = $('#nos_gomla_price').val();
        if(nos_gomla_price == ""){
            alert('من فضلك ادخل السعر نص جملة للوحده الاب');
            $('#nos_gomla_price').focus();
            return false;
        }
        var gomla_price = $('#gomla_price').val();
        if(gomla_price == ""){
            alert('من فضلك ادخل السعر جملة للوحده الاب');
            $('#gomla_price').focus();
            return false;
        }
        var cost_price = $('#cost_price').val();
        if(cost_price == ""){
            alert('من فضلك ادخل السعر تكلفة الشراء للوحده الاب');
            $('#cost_price').focus();
            return false;
        }
        
        if(does_has_retailunit == 1){
            var price_retail = $('#price_retail').val();
            if(price_retail == ""){
                alert('من فضلك ادخل السعر القطاعي للوحده التجزئة');
                $('#price_retail').focus();
                return false;
            }
            var nos_gomla_price_retail = $('#nos_gomla_price_retail').val();
            if(nos_gomla_price_retail == ""){
                alert('من فضلك ادخل السعر نص جملة للوحده التجزئة');
                $('#nos_gomla_price_retail').focus();
                return false;
            }
            var gomla_price_retail = $('#gomla_price_retail').val();
            if(gomla_price_retail == ""){
                alert('من فضلك ادخل السعر جملة للوحده التجزئة');
                $('#gomla_price_retail').focus();
                return false;
            }
            var cost_price_retail = $('#cost_price_retail').val();
            if(cost_price_retail == ""){
                alert('من فضلك ادخل السعر تكلفة الشراء للوحده التجزئة');
                $('#cost_price_retail').focus();
                return false;
            }

        }

        var has_fixed_price = $('#has_fixed_price').val();
        if(has_fixed_price == ""){
            alert('من فضلك اختر حالة هل للصنف سعر ثابت بالفواتير');
            $('#has_fixed_price').focus();
            return false;
        }
        var active = $('#active').val();
        if(active == ""){
            alert('من فضلك اختر حالة تفعيل للصنف');
            $('#active').focus();
            return false;
        }



    });


    $(document).on('click', '#do_edit_item_card', function (e) {

        
        var barcode = $('#barcode').val();
        if(barcode ==''){
            alert('من فضلك ادخل باركود الصنف');
            $('#barcode').focus();
            return false;
        }


        var name = $('#name').val();
        if(name ==''){
            alert('من فضلك ادخل اسم الصنف');
            $('#name').focus();
            return false;
        }
        var item_type = $('#item_type').val();
        if(item_type ==''){
            alert('من فضلك ادخل نوع الصنف');
            $('#item_type').focus();
            return false;
        }
        var nainv_itemcard_categories_idme = $('#inv_itemcard_categories_id').val();
        if(nainv_itemcard_categories_idme ==''){
            alert('من فضلك ادخل فئة الصنف');
            return false;
        }
        var uom_id = $('#uom_id').val();
        if(uom_id ==''){
            alert('من فضلك ادخل قياس الاب للصنف');
            $('#uom_id').focus();
            return false;
        }
        var does_has_retailunit = $('#does_has_retailunit').val();
        if(does_has_retailunit ==''){
            alert('من فضلك  ادخل حاله هل للصنف وحده التجزئة  ');
            $('#does_has_retailunit').focus();
            return false;
        };

        if(does_has_retailunit == 1){

            var retail_uom_id = $('#retail_uom_id').val();
            if(retail_uom_id ==''){
                alert('من فضلك ادخل قياس التجزئة للابن للصنف');
                $('#retail_uom_id').focus();
                return false;
            }
            var retail_uom_quantityToParent = $('#retail_uom_quantityToParent').val();
            if(retail_uom_quantityToParent =='' || retail_uom_quantityToParent ==0){
                alert('من فضلك ادخل   عدد الوحدات الوحده تجزئة بالنسبة للاب ');
                $('#retail_uom_quantityToParent').focus();
                return false;
            }
        }

        var price = $('#price').val();
        if(price == ""){
            alert('من فضلك ادخل السعر القطاعي للوحده الاب');
            $('#price').focus();
            return false;
        }
        var nos_gomla_price = $('#nos_gomla_price').val();
        if(nos_gomla_price == ""){
            alert('من فضلك ادخل السعر نص جملة للوحده الاب');
            $('#nos_gomla_price').focus();
            return false;
        }
        var gomla_price = $('#gomla_price').val();
        if(gomla_price == ""){
            alert('من فضلك ادخل السعر جملة للوحده الاب');
            $('#gomla_price').focus();
            return false;
        }
        var cost_price = $('#cost_price').val();
        if(cost_price == ""){
            alert('من فضلك ادخل السعر تكلفة الشراء للوحده الاب');
            $('#cost_price').focus();
            return false;
        }
        
        if(does_has_retailunit == 1){
            var price_retail = $('#price_retail').val();
            if(price_retail == ""){
                alert('من فضلك ادخل السعر القطاعي للوحده التجزئة');
                $('#price_retail').focus();
                return false;
            }
            var nos_gomla_price_retail = $('#nos_gomla_price_retail').val();
            if(nos_gomla_price_retail == ""){
                alert('من فضلك ادخل السعر نص جملة للوحده التجزئة');
                $('#nos_gomla_price_retail').focus();
                return false;
            }
            var gomla_price_retail = $('#gomla_price_retail').val();
            if(gomla_price_retail == ""){
                alert('من فضلك ادخل السعر جملة للوحده التجزئة');
                $('#gomla_price_retail').focus();
                return false;
            }
            var cost_price_retail = $('#cost_price_retail').val();
            if(cost_price_retail == ""){
                alert('من فضلك ادخل السعر تكلفة الشراء للوحده التجزئة');
                $('#cost_price_retail').focus();
                return false;
            }

        }

        var has_fixed_price = $('#has_fixed_price').val();
        if(has_fixed_price == ""){
            alert('من فضلك اختر حالة هل للصنف سعر ثابت بالفواتير');
            $('#has_fixed_price').focus();
            return false;
        }
        var active = $('#active').val();
        if(active == ""){
            alert('من فضلك اختر حالة تفعيل للصنف');
            $('#active').focus();
            return false;
        }



    });

    $(document).on('input', '#search_by_text', function(e) {
        make_search();  
    });
    $(document).on('change', '#item_type_search', function(e) {
        make_search();  
    });
    $(document).on('change', '#inv_itemcard_categories_id_search', function(e) {
        make_search();  
    });

    $('input[type=radio][name=searchbyradio]').change(function(){
        make_search();
    });




    function make_search(){
        var search_by_text = $('#search_by_text').val();
        var item_type = $('#item_type_search').val();
        var inv_itemcard_categories_id = $('#inv_itemcard_categories_id_search').val();
        var searchbyradio = $("input[type=radio][ name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_search_url").val();

        
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                item_type:item_type,
                inv_itemcard_categories_id:inv_itemcard_categories_id,
                searchbyradio:searchbyradio,
                "_token": token_search
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {}
        });
    }


    $(document).on('click', '#ajax_pagination_in_search a', function(e) {
        e.preventDefault();
        var search_by_text = $('#search_by_text').val();
        var item_type = $('#item_type_search').val();
        var inv_itemcard_categories_id = $('#inv_itemcard_categories_id_search').val();
        var searchbyradio = $("input[type=radio][ name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");

        jQuery.ajax({
            url: url ,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                item_type:item_type,
                inv_itemcard_categories_id:inv_itemcard_categories_id,
                searchbyradio:searchbyradio,
                "_token": token_search
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {}
        });

    });

    $(document).on('change', '#stores', function (e) {
        make_search_sow();
    });
    $(document).on('change', '#inv_itemcard_movements_categories', function (e) {
        make_search_sow();
    });
    $(document).on('change', '#inv_itemcard_movements_types', function (e) {
        make_search_sow();
    });
    $(document).on('change', '#from_order_date', function (e) {
        make_search_sow();
    });
    $(document).on('change', '#to_order_date', function (e) {
        make_search_sow();
    });
    $(document).on('change', '#sort_id', function (e) {
        make_search_sow();
    });
    $(document).on('click', '#show', function (e) {
        make_search_sow();
    });





    function make_search_sow() {
        var stores = $('#stores').val();
        var inv_itemcard_movements_categories = $('#inv_itemcard_movements_categories').val();
        var inv_itemcard_movements_types = $('#inv_itemcard_movements_types').val();
        var from_order_date = $("#from_order_date").val();
        var to_order_date = $("#to_order_date").val();
        var sort_id = $("#sort_id").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_search_url_show").val();

        

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                stores: stores,
                inv_itemcard_movements_categories: inv_itemcard_movements_categories,
                inv_itemcard_movements_types: inv_itemcard_movements_types, sort_id: sort_id,
                from_order_date: from_order_date, to_order_date: to_order_date,
                "_token": token_search
            },
            success: function (data) {
                $("#ajax_search_show").html(data);
            },
            error: function () { }
        });
    }


    $(document).on('click', '#ajax_pagination_in_search_show a', function (e) {
        
        e.preventDefault();
        
        var stores = $('#stores').val();
        var inv_itemcard_movements_categories = $('#inv_itemcard_movements_categories').val();
        var inv_itemcard_movements_types = $('#inv_itemcard_movements_types').val();
        var from_order_date = $("#from_order_date").val();
        var to_order_date = $("#to_order_date").val();
        var sort_id = $("#sort_id").val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");



        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                stores: stores,
                inv_itemcard_movements_categories: inv_itemcard_movements_categories,
                inv_itemcard_movements_types: inv_itemcard_movements_types, sort_id: sort_id,
                from_order_date: from_order_date, to_order_date: to_order_date,
                "_token": token_search
            },
            success: function (data) {
                $("#ajax_pagination_in_search_show").html(data);
            },
            error: function () { }
        });

    });

    
});


