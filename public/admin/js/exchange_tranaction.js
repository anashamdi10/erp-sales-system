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
            alert('من فضلك ادخل التاريخ التحصيل ');
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

    $(document).on('change','#mov_type',function(){

        $account_number = $('#account_number').val();
        
        if( $account_number == ""){
            alert(" من قضلك ادخل الحساب المالي اولا");
            return false;
        }

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


        
    })

     
})