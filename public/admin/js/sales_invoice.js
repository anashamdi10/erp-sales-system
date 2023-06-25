$(document).ready(function () {

    $(document).on('change', '#item_code', function (e) {
        // نجيب وحدات للصنف
        get_item_uoms();
        

    });

    function get_item_uoms() {
        var item_code = $('#item_code').val();

        if (item_code != "") {
            var token_search = $("#token_search").val();
            var ajax_get_item_uoms_url = $("#ajax_get_uoms").val();



            jQuery.ajax({
                url: ajax_get_item_uoms_url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    item_code: item_code,
                    "_token": token_search,


                },
                success: function (data) {
                    $("#UomDivAdd").html(data);
                    $('#UomDivAdd').show();
                    get_inv_itemcard_batches()

                },
                error: function () {

                    $("#UomDivAdd").hide();
                    alert("حدث خطأ ما")
                }
            });
        } else {
            $('#UomDivAdd').html("");
            $('.UomDivAdd').hide();
            $('#inv_item_batches').hide();

        }
    }

    function get_inv_itemcard_batches(){
       
       
        var item_code = $("#item_code").val();
        var uom_id = $("#uom_id").val();
       
      
        var store_id=$("#store_id").val();
      
        if (item_code != "" && uom_id!=""&& store_id!="") {
      var token_search = $("#token_search").val();
      var url = $("#ajax_get_inv_itemcard_batches").val();
      jQuery.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        cache: false,
        data: { item_code: item_code,uom_id:uom_id,store_id:store_id, "_token": token_search },
        success: function (data) {
         
          $("#inv_itemcard_batchesDiv").html(data);
          $("#inv_itemcard_batchesDiv").show();
          get_item_price();
        
        },
        error: function () {
            $("#inv_itemcard_batchesDiv").hide();
      
       
        }
      });
      
      
      
        }else{
          $("#UomDiv").hide(); 
          $("#inv_itemcard_batchesDiv").hide();
      
        }
      
      
      }

      $(document).on('change', '#uom_id', function (e) {
        get_inv_itemcard_batches();
      });
      


    $(document).on('click', '#AddNewInvoiceModel_show', function (e) {
        var token_search = $("#token_search").val();
        var ajax_load_add_invoice = $("#ajax_load_add_invoice").val();
        jQuery.ajax({
            url: ajax_load_add_invoice,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,


            },
            success: function (data) {

                $("#AddNewInvoiceModalBody").html(data);
                $("#AddNewInvoiceModal").modal("show");

                
            },
            error: function () {

                alert("حدث خطأ ما")
            }
        })
    })

    $(document).on('change', '#sales_item_type', function (e) {
        
        get_item_price();
      });



    function get_item_price(){
       
        var item_code = $("#item_code").val();
        var uom_id = $("#uom_id").val();
        var sales_item_type = $('#sales_item_type').val();
        var token_search = $("#token_search").val();
        var url = $("#ajax_get_item_price").val();
        
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            cache: false,
            data: { item_code: item_code,uom_id:uom_id,sales_item_type:sales_item_type, "_token": token_search },
            success: function (data) {
               
              $('#price').val(data);

              calculate_total_item_price_row();
            
            },
            error: function () {
                $('#price').val();
                
                alert('error');
           
            }
          });
    }

    function calculate_total_item_price_row(){
       $quantity = $('#quantity').val();
       $unit_price = $('#price').val();
       if($quantity == 0) {
            alert('ادخل الكمية ');
            return false;
       }

       if($unit_price == 0){
            alert('ادخل السعر الوحده  ');
            return false;
       }

       $('#total_cost').val((parseFloat($quantity)*parseFloat($unit_price))*1); 
    }

    $(document).on('input', '#quantity', function (e) {
        
        calculate_total_item_price_row();
    });
    $(document).on('input', '#price', function (e) {
        
        calculate_total_item_price_row();
    });


    $(document).on('click', '#add_item', function (e) {
        var store_id = $('#store_id').val();
        if(store_id == ''){
            alert("من فضلك ادخل المخزن ");
            $('#store_id').focus();
            return false;
        } 
        var sales_item_type = $('#sales_item_type').val();
        if(sales_item_type == ''){
            alert("من فضلك نوع  البيع ");
            $('#sales_item_type').focus();
            return false;
        } 
        var item_code = $('#item_code').val();
        if(item_code == ''){

            alert("من فضلك اختر الصنف  ");
            $('#item_code').focus();
            return false;
        } 
        var uom_id = $('#uom_id').val();
        if(uom_id == ''){
            alert("من فضلك اختر وحده  البيع  ");
            $('#uom_id').focus();
            return false;
        } 
        var inv_itemcard_batches_id = $("#inv_itemcard_batches_id").val();
        if (inv_itemcard_batches_id == "") {
          alert("من فضلك اختر  الباتش ");
          $("#inv_itemcard_batches_id").focus();
          return false;
        }
        var item_quantity = $("#quantity").val();
        if (item_quantity == "") {
          alert("من فضلك  ادخل الكمية ");
          $("#quantity").focus();
          return false;
        }
        alert(parseFloat(inv_itemcard_batches_id))
        if (parseFloat(item_quantity) > parseFloat(inv_itemcard_batches_id)) {
          alert("عفوا الكمية المطلوبة اكبر من كمية الباتش  الموجوده بالمخزن");
          return false;
        }
        var is_normal_orOthers = $('#is_normal_orOthers').val()
        if(is_normal_orOthers == ''){
            alert("من فضلك اختر هل البيع عادي ؟  ");
            $('#is_normal_orOthers').focus();
            return false;
        }

       

        var token = $("#token_search").val();
        var url = $("#ajax_add_sales_row").val();
        var total_cost = $("#total_cost").val();
        var price = $("#price").val();
        
        var store_name = $("#store_id option:selected").text();
        var sales_item_type_name = $("#sales_item_type option:selected").text();
        var item_code_name = $("#item_code option:selected").text();
        var uom_id_name = $("#uom_id option:selected").text();
        var is_normal_orOthers_name = $("#is_normal_orOthers option:selected").text();


        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {  "_token": token,store_id:store_id, sales_item_type:sales_item_type ,item_code:item_code, uom_id:uom_id , 
            total_cost:total_cost,inv_itemcard_batches_id:inv_itemcard_batches_id ,item_quantity:item_quantity ,
            is_normal_orOthers:is_normal_orOthers ,price:price,store_name:store_name , sales_item_type_name:sales_item_type_name,
            item_code_name:item_code_name ,uom_id_name:uom_id_name ,is_normal_orOthers_name:is_normal_orOthers_name },
            success: function (data) {
              
                $("#itemsrowtableContainterBody").append(data);
            
            },
            error: function () {
              
                
                alert('error');
           
            }
          });
    


        
    });

    $(document).on('click', '.remove_current', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    })



});
