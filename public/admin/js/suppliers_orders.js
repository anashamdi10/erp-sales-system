$(document).ready(function() {
  
    $(document).on('change', '#item_code_add', function(e) {
        var item_code = $(this).val();

        if(item_code !=""){
            var token_search = $("#token_search").val();
            var ajax_get_item_uoms_url = $("#ajax_get_item_uoms_url").val();
    
    
            
            jQuery.ajax({
                url: ajax_get_item_uoms_url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    item_code: item_code,
                    "_token": token_search,
                  
                   
                },
                success: function(data) {
                    $("#UomDivAdd").html(data);
                    $('.related_to_itemCard').show();

                    var type = $('#item_code_add').children('option:selected').data('type');

                    if(type==2){
                        $('.related_to_date').show();
                    }else{
                        $('.related_to_date').hide();
                    }
                },
                error: function() {
                    $('.related_to_itemCard').hide();
                    $("#UomDivAdd").html('');
                    alert("حدث خطأ ما")
                }
            });
        }else{
            $('#UomDivAdd').html("");
            $('.related_to_itemCard').hide();
            $('.related_to_date').hide(); 
        }
        
       
    });


    

    $(document).on('click', '#AddToBill', function(e) {
     
        var item_code_add = $('#item_code_add').val();
        if(item_code_add == ''){
            alert("من فضلك اختر الصنف ");
            $('#item_code_add').focuse();
            return false;
        }
        var uom_id_Add = $('#uom_id_Add').val();
        if(uom_id_Add == ''){
            alert("من فضلك اختر الوحدة ");
            $('#uom_id_Add').focuse();
            return false;
        }

       

        var isparentuom = $('#uom_id_Add').children('option:selected').data('isparentuom');

        var quantity_add = $('#quantity_add').val();
        if(quantity_add == '' || quantity_add == 0 ){
            alert("  من فضلك ادخل الكمية المستلمة");
            $('#quantity_add').focuse();
            return false;
        }
        var price_add = $('#price_add').val();
        if(price_add == ''){
            alert("  من فضلك ادخل السعر الوحده");
            $('#price_add').focuse();
            return false;
        }

       

        var type = $('#item_code_add').children('option:selected').data('type');
       
        if(type == 2){
            var production_date = $('#production_date').val();
            if(production_date == ''){
                alert("  من فضلك اختر تاريخ الانتاج");
                $('#production_date').focuse();
                return false;
            }
            var expire_date = $('#expire_date').val();
            if(expire_date == ''){
                alert("  من فضلك اختر تاريخ انتهاء الصلاحية");
                $('#expire_date').focuse();
                return false;
            }


            if(expire_date<production_date){
                alert('لا يمكن تاريخ الانتهاء اقل من تاريخ الانتاج !!! '); 
                $('#expire_date').focuse();
                return false;
    
            }
        }else{
            var expire_date = $('#expire_date').val();
            var production_date = $('#production_date').val();
        }

       


        var total_add = $('#total_add').val();
        if(total_add == ''){
            alert("  من فضلك ادخل اجمالي  الاصناف");
            $('#total_add').focuse();
            return false;
        }

        var  autoserailparent = $('#autoserailparent').val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_add_new_details").val();

       

        
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {
                autoserailparent: autoserailparent,"_token": token_search,item_code_add: item_code_add,
                uom_id_Add:uom_id_Add,quantity_add:quantity_add,isparentuom:isparentuom,
                price_add:price_add,production_date:production_date,
                expire_date:expire_date,total_add:total_add,type:type
            },
            success: function(data) {
                alert(" تم الإضافة بنجاح ")
                reload_items_details();
                reload_parent_pill();
            },
            error: function() {}
        });

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

 

    $(document).on('input', '#search_by_text', function(e) {
        
        make_search();
    });

 
    $('input[type=radio][name=searchbyradio]').change(function() {
        make_search();
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

    $(document).on('click', '.load_edit_item_details', function(e) {
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
        var  autoserailparent = $('#autoserailparent').val();
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

    function make_search() {
        var search_by_text = $("#search_by_text").val();
        var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_search_url").val();

        
        jQuery.ajax({
            url: ajax_search_url,
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

    
});