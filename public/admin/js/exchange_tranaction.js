$(document).ready(function(){
    $(document).on('click','#btn_collect_now',function(){
        var date = $('#mov_date').val();
        if(date == ''){
            alert('من فضلك اختر التاريخ  ');
            $('#mov_date').focus();
            return false;
        }
        
        var account_number = $('#account_number').val();
        if(account_number == ''){
            alert('من فضلك اختر الحساب  المالي ');
            $('#account_number').focus();
            return false;
        }

        var mov_type = $('#mov_type').val();
        if(mov_type == ''){
            alert('من فضلك اختر الحركة  المالية  ');
            $('#mov_type').focus();
            return false;
        }


        var money = $('#money').val();
        if(money == ''|| money <= 0 ){
            alert('من فضلك ادخل قيمة  المبلغ المحصل ');
            $('#money').focus();
            return false;
        }

        var byan = $('#byan').val();
        if(byan == ''){
            alert('من فضلك ادخل البيان ');
            $('#byan').focus();
            return false;
        }

    });

    $(document).on('change','#account_number',function(){
        account_number = $(this).val();

        if(account_number == ''){
        
            $('#mov_type').val("");
        }else{
        
            var account_type = $("#account_number option:selected").data("type");
        
            if(account_type == 2){
                // مورد
                $('#mov_type').val(9);
            }else if(account_type == 3){
                //عميل
                $('#mov_type').val(6);
            }else if(account_type == 6){
                //بنكي
                $('#mov_type').val(18);
            }else{
                //عام 
                $('#mov_type').val(3);
            }
        }
    });

    $(document).on('change', '#mov_type', function () {

        $account_number = $('#account_number').val();
        
        if ($account_number == "") {
            alert(" من قضلك ادخل الحساب المالي اولا");
            return false;
        }

        if (account_number == '') {
            $('#mov_type').val("");
        } else {
        
            var account_type = $("#account_number option:selected").data("type");
            if (account_type == 2) {
                // مورد
                $('#mov_type').val(9);
            } else if (account_type == 3) {
                //عميل
                $('#mov_type').val(6);
            } else if (account_type == 6) {
                //بنكي
                $('#mov_type').val(18);
            } else {
                //عام 
                $('#mov_type').val(3);
            }
        }


        
    });
    $(document).on('change', '#account_number', function () {
        
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_show_current_balance_account").val();
        account_number = $(this).val();

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,

            data: {
                "_token": token_search,
                account_number: account_number

            },
            success: function (data) {
                $('#current_balanceDiv').show();
                $('#current_balanceDiv').html(data);

            },
            error: function () {
                alert('error ');
            }
        });
    });

    $(document).on('change', '#account_number_search', function () {
        alert('aaaa')
        make_search();
    });
    $(document).on('change', '#mov_type_search', function () {
        make_search();
    });
    $(document).on('change', '#Sales_matrial_types_search', function () {
        make_search();
    });
    $(document).on('change', '#Sales_matrial_types_search', function () {
        make_search();
    });
    $(document).on('change', '#invoice_date_from_search', function () {
        make_search();
    });
    $(document).on('change', '#invoice_date_to_search', function () {
        make_search();
    });
    $(document).on('input', '#search_by_text', function () {
        make_search();
    });
    $('input[type=radio][name=searchbyradio]').change(function () {
        make_search();
    });


    function make_search() {

        alert('aaa');
        var token_search = $("#token_search").val();
        var url = $("#ajax_search").val();
        var searchbyradio = $("input[type=radio][ name=searchbyradio]:checked").val();
        var search_by_text = $('#search_by_text').val();
        var account_number_search = $('#account_number_search').val();
        var mov_type_search = $('#mov_type_search').val();
        var treasures_search = $('#treasures_search').val();
        var users_search = $('#users_search').val();
        var invoice_date_from_search = $('#invoice_date_from_search').val();
        var invoice_date_to_search = $('#invoice_date_to_search').val();



        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
                searchbyradio: searchbyradio,
                search_by_text: search_by_text,
                account_number_search: account_number_search,
                mov_type_search: mov_type_search,
                treasures_search: treasures_search,
                users_search: users_search,
                invoice_date_from_search: invoice_date_from_search,
                invoice_date_to_search: invoice_date_to_search,

            },
            success: function (data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function () {
                alert('error in search');
            }
        });
    }

    
})