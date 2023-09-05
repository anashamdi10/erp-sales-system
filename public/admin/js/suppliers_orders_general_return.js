$(document).ready(function() {
    
    $(document).on('change', '#item_code_return', function (e) {
        
        if ($(this).val() != '') {
            $('.related_to_itemCard').show();
            get_item_uoms();
        } else {
            $('.related_to_itemCard').hide();
            $("#UomDivAdd").html('');
            $("#UomDivAdd").hide();
            $("#inv_item_batches").hide();
            $()
        }
    
    });

    $(document).on('change', '#uom_id_Add', function (e) {
        get_inv_itemcard_batches();
    });

    

    $(document).on('click', '#EditDetailsitem', function(e) {
    
        var id = $(this).data("id");

        var item_code_edit = $('#item_code_edit').val();
        var uom_id_edit = $('#uom_id_edit').val();
        
        var isparentuom = $('#uom_id_edit').children('option:selected').data('isparentuom');
        
        var quantity_edit = $('#quantity_edit').val();
        if(quantity_edit == '' || quantity_edit == 0 ){
            alert("  من فضلك ادخل الكمية المستلمة");
            $('#quantity_edit').focuse();
            return false;
            
        }
        var price_edit = $('#price_edit').val();
        if(price_edit == ''){
            alert("  من فضلك ادخل السعر الوحده");
            $('#price_edit').focuse();
            return false;
            
        }

    

        var type_edit = $('#item_code_edit').children('option:selected').data('type');
    
        if(type_edit == 2){
            var production_date_edit = $('#production_date_edit').val();
            if(production_date_edit == ''){
                alert("  من فضلك اختر تاريخ الانتاج");
                $('#production_date').focuse();
                return false;
            }
            var expire_date_edit = $('#expire_date_edit').val();
            if(expire_date_edit == ''){
                alert("  من فضلك اختر تاريخ انتهاء الصلاحية");
                $('#expire_date_edit').focuse();
                return false;
            }


            if(expire_date<production_date){
                alert('لا يمكن تاريخ الانتهاء اقل من تاريخ الانتاج !!! '); 
                $('#expire_date').focuse();
                return false;
    
            }
        }else{
        
            var expire_date = $('#expire_date').val("");
            var production_date = $('#production_date').val("");
        }

    


        var total_edit = $('#total_edit').val();
        if(total_edit == ''){
            alert("  من فضلك ادخل اجمالي  الاصناف");
            $('#total_edit').focuse();
            return false;
        }

        

        var  autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_edit_item_details").val();

        
    
        
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {
                autoserailparent: autoserailparent,"_token": token_search,item_code_edit: item_code_edit,
                uom_id_edit:uom_id_edit,quantity_edit:quantity_edit,isparentuom:isparentuom,
                price_edit:price_edit,production_date_edit:production_date_edit,id:id,
                expire_date_edit:expire_date_edit,total_edit:total_edit,type_edit:type_edit
            },
            success: function(data) {
                alert(" تم تحديث بنجاح ")
                reload_items_details();
                reload_parent_pill();
            },
            error: function() {}
        });


    })

    $(document).on('change', '#price_add', function(e) {
        recalculate_Add();
    });
    $(document).on('change', '#quantity_add', function(e) {
        recalculate_Add();
    });
    $(document).on('change', '#price_edit', function(e) {
    
        recalculate_edit();
    });
    $(document).on('change', '#quantity_edit', function(e) {
        recalculate_edit();
    });


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

    $(document).on('click', '.load_edit_item_details', function (e) {
        
        var id = $(this).data('id');
        var  autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_load_edit_item_details").val();

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                autoserailparent: autoserailparent,"_token": token_search,id:id   
            },
            success: function(data) {
            
                $('#Edit_item_model_body').html(data);
                $('#Edit_item_model').modal('show');
            },
            error: function() {
            
            }
        });

    });
    $(document).on('click', '#load_model_add_detailsBtn', function(e) {
        var id = $(this).data('id');
        var autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_load_model_add_details").val();

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                autoserailparent: autoserailparent,"_token": token_search,id:id   
            },
            success: function(data) {
            
                $('#Add_item_model_body').html(data);
                $('#Add_item_model').modal('show');
            },
            error: function() {
            
            }
        });

    });


    $(document).on('click', '#load_close_approve_invoice', function(e) {
        var  autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_load_model_approve_invoice").val();
        
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                autoserailparent: autoserailparent,"_token": token_search,
            },
            success: function(data) {
            
                $('#ModelApproveInvoice_body').html(data);
                $('#ModelApproveInvoice  ').modal('show');
            },
            error: function() {
            alert ("error");
            }
        });

    });


    $(document).on('input', '#tax_percent', function(e) {
        var tax_percent = $(this).val();
        if(tax_percent=="") {tax_percent=0};
        if(tax_percent>100) {
            alert('عفوا  لا يمكن ان يكون نسبة الضريبة اكبر من 100 % !!!')
            $(this).val(0);
        };


        recalculate_approved();
    });
    $(document).on('input', '#discount_percent', function(e) {

        var discount_percent = $(this).val();
        var discount_type = $('#discount_type').val();
        if(discount_percent=="") {discount_percent=0};
        if(discount_type==1){
            if(discount_percent>100) {
                alert('عفوا  لا يمكن ان يكون نسبة الخصم  اكبر من 100 % !!!')
                $(this).val(0);
            };
        }
        
        recalculate_approved();
    });
    $(document).on('change', '#discount_type', function(e) {

        if($(this).val()==""){
            $('#discount_percent').val(0);
            $('#discount_value').val(0);
            $('#discount_percent').attr("readonly" , true);

        }else{
            $('#discount_percent').attr("readonly" , false);
            var discount_percent = $('#discount_percent').val();
            var discount_type = $('#discount_type').val();
            if(discount_percent=="") {discount_percent=0};
            if(discount_type==1){
                if(discount_percent>100) {
                    alert('عفوا   لا يمكن ان يكون نسبة الخصم  اكبر من 100 % !!!')
                    $(this).val(0);
                };
            }

        }

        recalculate_approved();
    });
    $(document).on('change', '#discount_percent', function(e) {
        recalculate_approved();
    });
    $(document).on('change', '#pill_type', function(e) {


        recalculate_approved();
    });
    $(document).on('input', '#what_paid', function(e) {
        var what_paid = $('#what_paid').val();
        var total_cost = $('#total_cost').val();
        var what_remain = $('#what_remain').val();
        var treasures_balance = $('#treasures_balance').val(); 
        var pill_type = $('#pill_type').val();

        if(pill_type ==1){
            if(parseFloat(what_paid) <parseFloat(total_cost)){
                alert('عفوا يجب ان يكون المبلغ مدفوع في حاله ان الفاتروة كاش');
                $('#what_paid').val(total_cost);
            }
        }else{
            if(parseFloat(what_paid) == parseFloat(total_cost)){
                alert('عفوا يجب ان لا يكون  كل المبلغ مدفوع في حاله ان الفاتروة اجل');
                $('#what_paid').val(0);
            }
        }

        if(parseFloat(total_cost) < parseFloat(what_paid) ){
            alert("عفوا لا يمكن ان يكون المدفوع اكبر من اجمالي الفاتورة ");
            recalculate_approved();
            return false ;
        }

        if(parseFloat(treasures_balance) < parseFloat(what_paid)){
            alert("عفوا لا يوجد رصيد كافي بالخزنة  ");
            recalculate_approved();
            return false ;
        }
        
    
        if(parseFloat(treasures_balance) > parseFloat(what_paid)){
            

            what_remain = total_cost - what_paid
            
            $('#what_remain').val(what_remain);
            
        }
        
        recalculate_approved();
    });
    $(document).on('mouseenter', '#do_close_approve_invoice', function(e) {
    
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_load_usershiftDiv").val();
        
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
            },
            success: function(data) {
            
                $('#shift_div').html(data);
            },
            error: function() {
            alert ("error");
            }
        });
    
    });


    $(document).on('click', '#do_close_approve_invoice', function(e) {
        var total_cost_items = $('#total_cost_items').val();
        if(total_cost_items == ""){
            alert('من فضلك ادخل إجمالي الاصناف ');
            return false ;
        }
        var tax_percent = $('#tax_percent').val();
        if(tax_percent == ""){
            alert('من فضلك ادخل نسبة ضريبة القيمة المضافة  ');
            $('#tax_percent').val()
            return false ;
        }
        var tax_value = $('#tax_value').val();
        if(tax_value == ""){
            alert('من فضلك ادخل قيمة ضريبة القيمة المضافة  ');
        
            return false ;
        }
        var total_befor_discount = $('#total_befor_discount').val();
        
        if(total_befor_discount == ""){
            alert('من فضلك ادخل قيمة الاجمالي قبل الخصم  ');
        
            return false ;
        }
        var discount_type = $('#discount_type').val();
        var discount_percent = $('#discount_percent').val();
        
    
        if(discount_type == 1){
            if(discount_percent>100) {
                alert('عفوا  لا يمكن ان يكون نسبة الخصم  اكبر من 100 % !!!');
                $('#discount_percent').focuse();
                return false ;
            };
        }else if(discount_type ==2){
        
            if(parseFloat(discount_value)>parseFloat(total_befor_discount)) {
                
                alert('عفوا  لا يمكن ان يكون قيمة  الخصم  اكبر من اجمالي الفاتورة قبل الخصم  !!!')
                $('#discount_value').focuse();
                return false ;
            };
        }else{
            if(discount_value > 0){
                alert('عفوا لا يمكن ان يوجد خصم مع اختيارك لنوع الخصم لا يوجد !!')
            }
        }


        if(discount_value == ""){
            alert('من فضلك ادخل قيمة الخصم  ');
        
            return false ;
        }
        var total_cost = $('#total_cost').val();
        if(total_cost == ""){
            alert('من فضلك ادخل قيمة إحمالي الفاتورة النهائي  ');
        
            return false ;
        }
        var pill_type = $('#pill_type').val();
        if(pill_type == ""){
            alert('من فضلك اختر نوع الفاتورة   ');
            return false ;
        }

        var what_paid = $('#what_paid').val();
        var what_remain = $('#what_remain').val();

        if(what_paid == ""){
            alert('من فضلك ادخل مبلغ المدفوع     ');
            return false ;
        }
        if(what_paid > total_cost){
            alert("عفوا يجب ان يكون  المبلغ المصروف  اكبر من الاحمالي");
            return false;
        }
        if(pill_type == 1){
            if(what_paid > total_cost){
                alert("عفوا يجب ان يكون كل المبلغ المدفوع كاش");
                return false;
            }
        }else{

            if(what_paid == total_cost){
                alert("عفوا  لا يمكن ان يكون المبلغ المدفوع يساوي احمالي الفاتورة في حاله ان فاتورة اجل");
                return false;
            }

        }

        if(what_remain == ""){
            alert('من فضلك ادخل مبلغ المتبقي     ');
            return false ;
        }

        if(pill_type == 1){
            if(what_remain > 0){
                alert('عفوا لا يمكن ان يكون المبلغ المتبقي اكبر من صفر في حالة ان الفاتورة كاش !!!');
                return false ;
            }
        }
        var treasures_id = $('#treasures_id').val();
        var treasures_balance = $('#treasures_balance').val();
        if(what_paid >0){
            if(treasures_id == ""){
                alert('من فضلك اختر  خزنة التحصيل');
                return false ;
            }
        
        }


    })

    $(document).on('input', '#search_by_text', function(e) {
        
        make_search();
    });


    $('input[type=radio][name=searchbyradio]').change(function() {
    
        make_search();
    });

    
    $(document).on('change', '#to_order_date',function() {
    
        make_search();
    });
    $(document).on('change', '#from_order_date',function() {
    
        make_search();
    });
    $(document).on('change', '#supplier_code',function() {
        
        make_search();
    });
    $(document).on('change', '#store_id',function() {
    
        make_search();
    });



    function reload_items_details(){
    
        var  autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_reload_itemsdetails").val();

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                autoserailparent: autoserailparent,
                "_token": token_search,
                
            
            },
            success: function(data) {
                
                $('#ajax_responce_serarchDivDetails').html(data);
            },
            error: function() {
            
            }
        });


    }
    function reload_parent_pill(){
        var  autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_reload_parent_pill").val();

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                autoserailparent: autoserailparent,
                "_token": token_search,
                
            
            },
            success: function(data) {
            
                $('#ajax_responce_serarchDivparentpill').html(data);
            },
            error: function() {
            
            }
        });


    }




    function recalculate_Add(){
        var quantity_add = $('#quantity_add').val();
        var price_add = $('#price_add').val();
        if(quantity_add =="") quantity_add = 0 ; 
        if(price_add =="") price_add = 0 ; 

        $('#total_add').val(parseFloat(quantity_add)*parseFloat(price_add));
    }
    function recalculate_edit(){
        var quantity_add = $('#quantity_edit').val();
        var price_add = $('#price_edit').val();
        if(quantity_add =="") quantity_add = 0 ; 
        if(price_add =="") price_add = 0 ; 

        $('#total_edit').val(parseFloat(quantity_add)*parseFloat(price_add));
    }


    function recalculate_approved (){
        var total_cost_items = $("#total_cost_items").val();
        if (total_cost_items == "") { total_cost_items = 0; }
        total_cost_items = parseFloat(total_cost_items);
        var tax_percent = $("#tax_percent").val();
        if (tax_percent == "") { tax_percent = 0 };
        tax_percent = parseFloat(tax_percent);
    
        var tax_value = total_cost_items * tax_percent / 100;
        tax_value = parseFloat(tax_value);
        $("#tax_value").val(tax_value * 1);
        var total_befor_discount = total_cost_items + tax_value;
        $("#total_befor_discount").val(total_befor_discount);
        var discount_type = $("#discount_type").val();
        if (discount_type != "") {
            if (discount_type == 1) {
            var discount_percent = $("#discount_percent").val();
            if (discount_percent == "") { discount_percent = 0; }
            discount_percent = parseFloat(discount_percent);
            var discount_value = total_befor_discount * discount_percent / 100;
            $("#discount_value").val(discount_value * 1);
            var total_cost = total_befor_discount - discount_value;
            $("#total_cost").val(total_cost * 1);
    
        } else {
            var discount_percent = $("#discount_percent").val();
            if (discount_percent == "") { discount_percent = 0; }
            discount_percent = parseFloat(discount_percent);
            $("#discount_value").val(discount_percent * 1);
            var total_cost = total_befor_discount - discount_percent;
            $("#total_cost").val(total_cost * 1);
        }
    
    
    
        } else {
            $("#discount_value").val(0);
            var total_cost = total_befor_discount;
            $("#total_cost").val(total_cost);
    
        }
        what_paid = $("#what_paid").val();
        if (what_paid == "") what_paid = 0;
        what_paid = parseFloat(what_paid);
        total_cost = parseFloat(total_cost);
        $what_remain = total_cost - what_paid;
        $("#what_remain").val($what_remain * 1);

    }


    function make_search(){
        var search_by_text = $('#search_by_text').val();
        var store_id = $('#store_id').val();
        var supplier_code = $('#supplier_code').val();
        var searchbyradio = $("input[type=radio][ name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_search_url").val();
        var to_order_date = $("#to_order_date").val();
        var from_order_date = $("#from_order_date").val();
        
        
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                store_id:store_id,
                supplier_code:supplier_code,
                to_order_date:to_order_date,
                from_order_date:from_order_date,
                searchbyradio:searchbyradio,
                "_token": token_search
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {}
        });
    }


    function get_item_uoms() {
        var item_code = $("#item_code_return").val();
        
        
        if (item_code != "") {
            var token_search = $("#token_search").val();
            var ajax_get_item_uoms_url = $("#ajax_get_item_uoms_url").val();
            

            jQuery.ajax({
                url: ajax_get_item_uoms_url,
                type: "post",
                dataType: "html",
                cache: false,
                data: {
                    item_code: item_code,
                    '_token': token_search,
                },
                success: function (data) {
                    $("#UomDivAdd").html(data);
                    $("#UomDivAdd").show();
                    get_inv_itemcard_batches();
                },
                error: function () {
                    $("#UomDivAdd").hide();
                    alert(" error in get_item_uoms");
                },
            });
        } else {
            $("#UomDivAdd").html("");
            $(".UomDivAdd").hide();
            $("#inv_item_batches").hide();
        }
    }

    function get_inv_itemcard_batches(oldBatchid = null) {
        
        
        var item_code = $("#item_code_return").val();
        var uom_id = $("#uom_id_Add").val();
        
        var store_id = $("#store_id").val();
        
        if (item_code != "" && uom_id != "" && store_id != "") {

            

            var token_search = $("#token_search").val();
            var url = $("#ajax_get_inv_itemcard_batches").val();
            jQuery.ajax({
                url: url,
                type: "post",
                dataType: "html",
                cache: false,
                data: {
                    item_code: item_code,
                    uom_id: uom_id,
                    store_id: store_id,
                    _token: token_search,
                },
                success: function (data) {
                    
                    $("#inv_itemcard_batchesDiv").html(data);
                    $("#inv_itemcard_batchesDiv").show();
                    var inv_itemcard_batches_id = $('#inv_itemcard_batches_id').val();
                    
                    if (inv_itemcard_batches_id != '') {
                        
                        batchPrice = $("#inv_itemcard_batches_id option:selected").data("price");
                        $('#price_add').val(batchPrice * 1);
                        $('#quantity_add').focus();
                    }

                    if (oldBatchid != null) {
                        $('#inv_itemcard_batches_id').val(oldBatchid)
                    }

                    var quantity = $('#quantity_add').val();
                    var price = $('#price_add').val();

                    var total_cost = quantity * price;

                    $('#total_add').val(total_cost);

                },
                error: function () {
                    $("#inv_itemcard_batchesDiv").hide();
                    alert('error in get_inv_itemcard_batches ')
                },
            });
        } else {
            $("#UomDiv").hide();
            $("#inv_itemcard_batchesDiv").hide();
        }
    }


    $(document).on("mouseenter", "#AddToBill", function (e) {

        
        if ($('#inv_itemcard_batches_id').length) {

            var oldBatchid = $('#inv_itemcard_batches_id').val();
        } else {
            var oldBatchid = null;
        }

        get_inv_itemcard_batches(oldBatchid);
    });

    $(document).on("click", "#AddToBill", function (e) {
        
        var store_id = $("#store_id").val();
        if (store_id == "") {
            alert("من فضلك ادخل المخزن ");
            $("#store_id").focus();
            return false;
        }
    

        var item_code = $("#item_code_return").val();
        if (item_code == "") {
            alert("من فضلك اختر الصنف  ");
            $("#item_code").focus();
            return false;
        }

        var uom_id = $("#uom_id_Add").val();
        if (uom_id == "") {
            alert("من فضلك اختر وحده  البيع  ");
            $("#uom_id").focus();
            return false;
        }
        var inv_itemcard_batches_id = $("#inv_itemcard_batches_id").val();
        if (inv_itemcard_batches_id == "") {
            alert("من فضلك اختر  الباتش ");
            $("#inv_itemcard_batches_id").focus();
            return false;
        }
        var item_quantity = $("#quantity_add").val();
        if (item_quantity == "") {
            alert("من فضلك  ادخل الكمية ");
            $("#quantity").focus();
            return false;
        }

        var batchQuantity = $("#inv_itemcard_batches_id option:selected").data("quantity");

        if (parseFloat(item_quantity) > parseFloat(batchQuantity)) {
            alert("عفوا الكمية المطلوبة اكبر من كمية الباتش  الموجوده بالمخزن");
            return false;
        }
        var is_normal_orOthers = $("#is_normal_orOthers").val();
        if (is_normal_orOthers == "") {
            alert("من فضلك اختر هل البيع عادي ؟  ");
            $("#is_normal_orOthers").focus();
            return false;
        }

        
    
        var isparentuom = $("#uom_id_Add option:selected").data("isparentuom");
            var token = $("#token_search").val();
            var url = $("#ajax_add_return_items_from_invoice").val();
            var item_total = $("#total_add").val();

            var price = $("#price_add").val();
            var auto_serial_parent = $("#autoserailparent").val();
        

            jQuery.ajax({
                url: url,
                type: "post",
                dataType: "json",
                cache: false,
                data: {
                    _token: token,
                    store_id: store_id,
                    item_code: item_code,
                    uom_id: uom_id,
                    item_total: item_total,
                    inv_itemcard_batches_id: inv_itemcard_batches_id,
                    item_quantity: item_quantity,
                    is_normal_orOthers: is_normal_orOthers,
                    price: price,
                    auto_serial_parent: auto_serial_parent,
                    isparentuom: isparentuom,
                },
                success: function (data) {
                    alert("تم الايضافة  ");
                    reload_items_details();
                    reload_parent_pill();
                    
                },
                error: function () {
                    alert("error in click add_item_to_invoice_details");
                    
                },
            });
        
    });
    $(document).on('change', '#inv_itemcard_batches_id', function (e) {
        var inv_itemcard_batches_id = $(this).val();
        if (inv_itemcard_batches_id != '') {
            var batchPrice = $("#inv_itemcard_batches_id option:selected").data("price");
            $('#price_add').val(batchPrice * 1);
            $('#quantity_add').focus();

        }
    });

});