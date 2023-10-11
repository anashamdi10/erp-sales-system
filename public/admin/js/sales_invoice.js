$(document).ready(function () {
    $(document).on("change", "#item_code", function (e) {
        // نجيب وحدات للصنف
        get_item_uoms();
    });


    $(document).on("change", "#uom_id", function (e) {
        get_inv_itemcard_batches();
        get_item_price();
    });

    $(document).on("click", "#AddNewOfferPrice_show", function (e) {
        var token_search = $("#token_search").val();
        var ajax_load_add_invoice = $("#ajax_load_model_offer_price").val();
        jQuery.ajax({
            url: ajax_load_add_invoice,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                '_token': token_search,
            },
            success: function (data) {
                $("#AddNewOfferPriceModaMlBody").html(data);
                $("#AddNewOfferPriceModal").modal("show");

                $("#AddNewSalesInvoiceModaMlBody").html("");
                $("#AddNewSalesInvoiceModal").modal("hide");
            },
            error: function () {
                alert("  error in AddNewOfferPrice_show");
            },
        });
    });

    $(document).on("click", "#AddNewSalesInvoice", function (e) {
        var token_search = $("#token_search").val();
        var url = $("#ajax_load_model_sales_invoice").val();
        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                _token: token_search,
            },
            success: function (data) {
                $("#AddNewSalesInvoiceModal").modal("show");
                $("#AddNewSalesInvoiceModaMlBody").html(data);
            },
            error: function () {
                alert("  error in AddNewSalesInvoice");
            },
        });
    });

    $(document).on("change", "#sales_item_type", function (e) {
        get_item_price();
    });
    $(document).on("change", "#notes", function (e) {
        recalculate();
    });
    $(document).on("change", "#invoice_date", function (e) {
        recalculate();
    });
    $(document).on("change", "#sales_material_type", function (e) {
        recalculate();
    });
    $(document).on("change", "#customer_code", function (e) {
        recalculate();
    });
    $(document).on("change", "#delegate_code", function (e) {
        recalculate();
    });

    $(document).on("click", "#do_add_new_customer", function (e) {
        e.preventDefault();
    
        $('#AddNewCustomereModal').modal('show');
    });

  




    $(document).on("click", "#add_new_customer", function (e) {
        e.preventDefault();
        var name = $("#name").val();
        var start_balance_status = $("#start_balance_status").val();
        var start_balance = $("#start_balance").val();
        var active = $("#active").val();

        if (name == '') {
            alert('اسم العميل مطلوب ');
            $("#name").focus();
            return false;
        }
        
        if (start_balance_status == '') {
            alert('   حال الرصيد العميل اول المدة مطلوب ');
            $("#start_balance_status").focus();
            return false;
        }
        if (start_balance == '') {
            alert('رصيد العميل اول المدة مطلوب ');
            $("#start_balance_status").focus();
            return false;
        }
        if (start_balance == 3 && start_balance == 0) {
            alert('هفوا لا بد ان يكون اول المدة في حالة الاتزان ');
            $("#start_balance").val(0); 
            $("#start_balance").focus();
            return false;
        }
        if (active == '') {
            alert('حالة التفعيل مطلوبة  ');
            $("#active").focus();
            return false;
        };
        var phones = $("#phones").val();
        var address = $("#address").val();
        var notes = $("#notes").val();
        var token_search = $("#token_search").val();
        var url = $("#ajax_add_new_customer").val();
        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "json",
            cache: false,
            data: {
                _token: token_search,
                name: name, start_balance_status: start_balance_status, start_balance: start_balance,
                active: active,phones: phones,address: address, notes: notes
            },
            success: function (data) {
                if (data == 'exsits') {
                    alert('اسم العميل مسجل من قبل ');
                    $("#name").focus();
                } else {
                    alert('تم الإضافة العميل بنجاح ');
                    $("#notes").val("");
                    $("#address").val('');
                    $("#phones").val('');
                    $("#active").val(1);
                    $("#start_balance").val(0);
                    $("#start_balance_status").val('');
                    $('#AddNewCustomereModal').modal('hide'); 

                    get_last_added_customer();
                }
            },
            error: function () {
                alert("  error in AddNewSalesInvoice");
            },
        });

        
    });


    $(document).on('change', '#start_balance_status', function (e) {
        if ($(this).val() == "") {
            $("#start_balance").val("");
        } else {
            if ($(this).val() == 3) {
                $("#start_balance").val(0);
            }
        }
    });
    $(document).on('input', '#start_balance', function (e) {
        var start_balance_status = $("#start_balance_status").val();
        if (start_balance_status == "") {
            alert("من فضلك اختر حالة الحساب اولا");
            $(this).val("");
            return false;
        }
        if ($(this).val() == 0 && start_balance_status != 3) {
            alert("يجب ادخال مبلغ اكبر من الصفر");
            $(this).val("");
            return false;
        }
    });



    $(document).on("input", "#quantity", function (e) {
        calculate_total_item_price_row();
    });
    $(document).on("input", "#price", function (e) {
        calculate_total_item_price_row();
    });

    $(document).on("click", "#add_item", function (e) {

        var store_id = $("#store_id").val();
        if (store_id == "") {
            alert("من فضلك ادخل المخزن ");
            $("#store_id").focus();
            return false;
        }

        var item_code = $("#item_code").val();
        if (item_code == "") {
            alert("من فضلك اختر الصنف  ");
            $("#item_code").focus();
            return false;
        }

        var sales_item_type = $("#sales_item_type").val();
        if (sales_item_type == "") {
            alert("من فضلك نوع  البيع ");
            $("#sales_item_type").focus();
            return false;
        }

        var uom_id = $("#uom_id").val();
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
        var item_quantity = $("#quantity").val();
        if (item_quantity == "") {
            alert("من فضلك  ادخل الكمية ");
            $("#quantity").focus();
            return false;
        }



        var batchQuantity = $("#inv_itemcard_batches_id option:selected").data(
            "quantity"
        );

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

        var token = $("#token_search").val();
        var url = $("#ajax_add_sales_row").val();
        var item_total = $("#item_total").val();

        var price = $("#price").val();

        var store_name = $("#store_id option:selected").text();
        var sales_item_type_name = $("#sales_item_type option:selected").text();
        var item_code_name = $("#item_code option:selected").text();
        var uom_id_name = $("#uom_id option:selected").text();
        var is_normal_orOthers_name = $(
            "#is_normal_orOthers option:selected"
        ).text();

        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                '_token': token,
                store_id: store_id,
                sales_item_type: sales_item_type,
                item_code: item_code,
                uom_id: uom_id,
                item_total: item_total,
                inv_itemcard_batches_id: inv_itemcard_batches_id,
                item_quantity: item_quantity,
                is_normal_orOthers: is_normal_orOthers,
                price: price,
                store_name: store_name,
                sales_item_type_name: sales_item_type_name,
                item_code_name: item_code_name,
                uom_id_name: uom_id_name,
                is_normal_orOthers_name: is_normal_orOthers_name,
            },
            success: function (data) {
                $("#itemsrowtableContainterBody").append(data);
                recalculate();
            },
            error: function () {
                alert("error in add_item");
            },
        });
    });

    $(document).on("click", ".remove_current", function (e) {
        e.preventDefault();
        $(this).closest("tr").remove();
        recalculate();
    });



    $(document).on("change", "#pill_type", function (e) {
        var pill_type = $("#pill_type").val();
        var total_cost = $("#total_cost").val();

        if (pill_type == 1) {
            $("#what_paid").val(total_cost * 1);
            $("#what_remain").val(0);
            $("#what_paid").attr("readonly", true);
            recalculate();
        } else {
            $("#what_paid").val(0);
            $("#what_remain").val(total_cost * 1);
            $("#what_paid").attr("readonly", false);
            recalculate();
        }
    });

    $(document).on("input", "#what_paid", function (e) {
        var what_paid = $("#what_paid").val();
        var total_cost = $("#total_cost").val();
        var what_remain = $("#what_remain").val();
        var treasures_balance = $("#treasures_balance").val();
        var pill_type = $("#pill_type").val();

        if (pill_type == 1) {
            if (parseFloat(what_paid) < parseFloat(total_cost)) {
                alert("عفوا يجب ان يكون المبلغ مدفوع في حاله ان الفاتروة كاش");
                $("#what_paid").val(total_cost);
            }
        } else {
            if (parseFloat(what_paid) >= parseFloat(total_cost)) {
                alert(
                    "عفوا يجب ان لا يكون  كل المبلغ مدفوع في حاله ان الفاتروة اجل"
                );
                $("#what_paid").val(0);
            }
        }

        if (parseFloat(treasures_balance) < parseFloat(what_paid)) {
            alert("عفوا لا يوجد رصيد كافي بالخزنة  ");
            recalculate();
            return false;
        }

        if (parseFloat(treasures_balance) > parseFloat(what_paid)) {
            what_remain = total_cost - what_paid;

            $("#what_remain").val(what_remain);
        }

        recalculate();
    });

    $(document).on("change", "#discount_type", function (e) {
        if ($(this).val() == "") {
            $("#discount_percent").val(0);
            $("#discount_value").val(0);
            $("#discount_percent").attr("readonly", true);
        } else {
            $("#discount_percent").attr("readonly", false);
            var discount_percent = $("#discount_percent").val();
            var discount_type = $("#discount_type").val();
            if (discount_percent == "") {
                discount_percent = 0;
            }
            if (discount_type == 1) {
                if (discount_percent > 100) {
                    alert(
                        "عفوا   لا يمكن ان يكون نسبة الخصم  اكبر من 100 % !!!"
                    );
                    $(this).val(0);
                }
            }
        }

        recalculate();
    });

    $(document).on("input", "#discount_percent", function (e) {
        var discount_percent = $(this).val();
        var discount_type = $("#discount_type").val();
        if (discount_percent == "") {
            discount_percent = 0;
        }
        if (discount_type == 1) {
            if (discount_percent > 100) {
                alert("عفوا  لا يمكن ان يكون نسبة الخصم  اكبر من 100 % !!!");
                $(this).val(0);
            }
        }

        recalculate();
    });

    $(document).on("input", "#tax_percent", function (e) {
        var tax_percent = $(this).val();
        if (tax_percent == "") {
            tax_percent = 0;
        }
        if (tax_percent > 100) {
            alert("عفوا  لا يمكن ان يكون نسبة الضريبة اكبر من 100 % !!!");
            $(this).val(0);
        }

        recalculate();
    });

    $(document).on("click", "#do_add_new_sales_invoice", function (e) {
        var invoice_date = $("#invoice_date_create").val();
        if (invoice_date == "") {
            alert("من فضلك ادخل تاريخ الفاتورة ");
            $("#invoice_date").focus();
            return false;
        }

        var sales_material_type = $("#sales_material_type_create").val();
        if (sales_material_type == "") {
            alert("من فضلك اختر  فئة الفاتورة   ");
            $("#sales_material_type").focus();
            return false;
        }

        var is_has_customer = $("#is_has_customer_create").val();

        if (is_has_customer == 1) {
            var customer_code = $("#customer_code_create").val();

            if (customer_code == "") {
                alert("من فضلك اختر  العميل  ");
                $("#customer_code").focus();
                return false;
            }
        }
        var delgate_code = $("#delgate_code_create").val();
        if (delgate_code == "") {
            alert("من فضلك اختر  المندوب  ");
            $("#delgate_code").focus();
            return false;
        }

        var pill_type = $("#pill_type_create").val();

        var token = $("#token_search").val();
        var url = $("#ajax_do_add_new_sales_invoice").val();


        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "json",
            cache: false,
            data: {
                delgate_code: delgate_code,
                invoice_date: invoice_date,
                is_has_customer: is_has_customer,
                sales_material_type: sales_material_type,
                customer_code: customer_code,
                pill_type: pill_type,
                '_token': token,
            },
            success: function (auto_serial) {
                alert("تم الحفظ ");
                update_sales_invoice(auto_serial);
                make_search();
            },
            error: function () {
                alert("  error in do_add_new_sales_invoice ");
            },
        });
    });

    $(document).on("change", "#is_has_customer", function (e) {
        $("#customer_code").val("");

        var is_has_customer = $(this).val();

        if (is_has_customer == 0) {
            $("#customerDiv").hide();
        } else {
            $("#customerDiv").show();
        }
    });
    $(document).on("change", "#is_has_customer_create", function (e) {
        $("#customer_code_create").val("");

        var is_has_customer = $(this).val();

        if (is_has_customer == 0) {
            $("#customerDiv").hide();
        } else {
            $("#customerDiv").show();
        }
    });

    $(document).on("click", ".load_update_sales_invoice", function (e) {
        var auto_serial = $(this).data("auto_serial");
        update_sales_invoice(auto_serial);
    });


    $(document).on("mouseenter", "#add_item_to_invoice_details", function (e) {

        if ($('#inv_itemcard_batches_id').length) {

            var oldBatchid = $('#inv_itemcard_batches_id').val();
        } else {
            var oldBatchid = null;
        }
        get_inv_itemcard_batches(oldBatchid);
    });

    $(document).on("click", "#add_item_to_invoice_details", function (e) {

        var store_id = $("#store_id").val();
        if (store_id == "") {
            alert("من فضلك ادخل المخزن ");
            $("#store_id").focus();
            return false;
        }

        var item_code = $("#item_code").val();
        if (item_code == "") {
            alert("من فضلك اختر الصنف  ");
            $("#item_code").focus();
            return false;
        }

        var sales_item_type = $("#sales_item_type").val();
        if (sales_item_type == "") {
            alert("من فضلك نوع  البيع ");
            $("#sales_item_type").focus();
            return false;
        }
        var uom_id = $("#uom_id").val();
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
        var item_quantity = $("#quantity").val();
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

        if ($("#invoice_auto_serial").length > 0) {

            var isparentuom = $("#uom_id option:selected").data("isparentuom");
            var token = $("#token_search").val();
            var url = $("#ajax_add_items_to_invoice").val();
            var item_total = $("#item_total").val();

            var price = $("#price").val();
            var auto_serial = $("#invoice_auto_serial").val();

            jQuery.ajax({
                url: url,
                type: "post",
                dataType: "json",
                cache: false,
                data: {
                    _token: token,
                    store_id: store_id,
                    sales_item_type: sales_item_type,
                    item_code: item_code,
                    uom_id: uom_id,
                    item_total: item_total,
                    inv_itemcard_batches_id: inv_itemcard_batches_id,
                    item_quantity: item_quantity,
                    is_normal_orOthers: is_normal_orOthers,
                    price: price,
                    auto_serial: auto_serial,
                    isparentuom: isparentuom,
                },
                success: function (data) {
                    alert("تم الايضافة  ");
                    add_new_item_sales_row();

                },
                error: function () {
                    alert("error in click add_item_to_invoice_details");
                },
            });
        }
    });


    $(document).on("click", ".remove_current_row", function (e) {


        jQuery.ajax({
            url: $("#ajax_delete_item_sales_details_row").val(),
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                '_token': $("#token_search").val(),
                auto_serial: $("#invoice_auto_serial").val(),
                id: $(this).data('id'),
            },
            success: function (data) {
                alert('تم الحذف ')
                add_new_item_sales_row();
                recalculate();
            },
            error: function () {

                alert(" error in remove_current_row");
            },
        });
    });


    $(document).on("click", "#do_close_approve_invoice", function (e) {

        var invoice_date = $("#invoice_date").val();
        if (invoice_date == "") {
            alert("من فضلك ادخل تاريخ الفاتورة ");
            $("#invoice_date").focus();
            return false;
        }

        var sales_material_type = $("#sales_material_type").val();
        if (sales_material_type == "") {
            alert("من فضلك اختر  فئة الفاتورة   ");
            $("#sales_material_type").focus();
            return false;
        }

        var is_has_customer = $("#is_has_customer").val();

        if (is_has_customer == 1) {
            var customer_code = $("#customer_code").val();

            if (customer_code == "") {
                alert("من فضلك اختر  العميل  ");
                $("#customer_code").focus();
                return false;
            }
        }
        var delgate_code = $("#delegate_code").val();
        if (delgate_code == "") {
            alert("من فضلك اختر  المندوب  ");
            $("#delgate_code").focus();
            return false;
        }


        if (!$('.item_total_array').length) {
            alert('عفوا يجب إيضافة صنف على الأقل ')
            return false;
        }


        var total_cost_items = $('#total_cost_items').val();
        if (total_cost_items == "") {
            alert('من فضلك ادخل إجمالي الاصناف ');
            return false;
        }
        var tax_percent = $('#tax_percent').val();
        if (tax_percent == "") {
            alert('من فضلك ادخل نسبة ضريبة القيمة المضافة  ');
            $('#tax_percent').val()
            return false;
        }
        var tax_value = $('#tax_value').val();
        if (tax_value == "") {
            alert('من فضلك ادخل قيمة ضريبة القيمة المضافة  ');

            return false;
        }
        var total_befor_discount = $('#total_befor_discount').val();

        if (total_befor_discount == "") {
            alert('من فضلك ادخل قيمة الاجمالي قبل الخصم  ');

            return false;
        }
        var discount_type = $('#discount_type').val();
        var discount_percent = $('#discount_percent').val();


        if (discount_type == 1) {
            if (discount_percent > 100) {
                alert('عفوا  لا يمكن ان يكون نسبة الخصم  اكبر من 100 % !!!');
                $('#discount_percent').focuse();
                return false;
            };
        } else if (discount_type == 2) {

            if (parseFloat(discount_value) > parseFloat(total_befor_discount)) {

                alert('عفوا  لا يمكن ان يكون قيمة  الخصم  اكبر من اجمالي الفاتورة قبل الخصم  !!!')
                $('#discount_value').focuse();
                return false;
            };
        } else {
            if (discount_value > 0) {
                alert('عفوا لا يمكن ان يوجد خصم مع اختيارك لنوع الخصم لا يوجد !!')
            }
        }


        if (discount_value == "") {
            alert('من فضلك ادخل قيمة الخصم  ');

            return false;
        }
        var total_cost = $('#total_cost').val();
        if (total_cost == "") {
            alert('من فضلك ادخل قيمة إحمالي الفاتورة النهائي  ');

            return false;
        }
        var pill_type = $('#pill_type').val();
        if (pill_type == "") {
            alert('من فضلك اختر نوع الفاتورة   ');
            return false;
        }

        var what_paid = $('#what_paid').val();
        var what_remain = $('#what_remain').val();

        if (what_paid == "") {
            alert('من فضلك ادهل مبلغ المدفوع     ');
            return false;
        }
        if (what_paid > total_cost) {
            alert("عفوا يجب ان يكون  المبلغ المصروف  اكبر من الاحمالي");
            return false;
        }
        if (pill_type == 1) {
            if (what_paid > total_cost) {
                alert("عفوا يجب ان يكون كل المبلغ المدفوع كاش");
                return false;
            }
        } else {

            if (what_paid == total_cost) {
                alert("عفوا  لا يمكن ان يكون المبلغ المدفوع يساوي احمالي الفاتورة في حاله ان فاتورة اجل");
                return false;
            }

        }

        if (what_remain == "") {
            alert('من فضلك ادخل مبلغ المتبقي     ');
            return false;
        }

        if (pill_type == 1) {
            if (what_remain > 0) {
                alert('عفوا لا يمكن ان يكون المبلغ المتبقي اكبر من صفر في حالة ان الفاتورة كاش !!!');
                return false;
            }
        }
        var treasures_id = $('#treasures_id').val();
        var treasures_balance = $('#treasures_balance').val();
        if (what_paid > 0) {
            if (treasures_id == "") {
                alert('من فضلك اختر  خزنة  الصرف     ');
                return false;
            }
            if (treasures_balance == "") {
                alert('من فضلك ادخل رصيد  خزنة       ');
                return false;
            }

            if (parseFloat(what_paid) > parseFloat(treasures_balance)) {
                alert("عفوا لا يوجد رصيد كافي في خزنة الصرف ");
                return false;
            }
        }

        var token_search = $("#token_search").val();
        var url = $("#ajax_do_close_and_approve").val();
        var auto_serial = $("#invoice_auto_serial").val();
        var treasures_id = $("#treasures_id").val();



        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "json",
            cache: false,
            data: {
                '_token': token_search,
                auto_serial: auto_serial,
                treasures_id: treasures_id,
                what_paid: what_paid,
                what_remain: what_remain
            },
            success: function (data) {
                alert('تم الاعتماد ');
                location.reload();

            },
            error: function () {

                alert('error in do_close_approve_invoice ')
            },

        });

    });


    $(document).on('mouseenter', '#do_close_approve_invoice', function (e) {

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
            success: function (data) {

                $('#shift_div').html(data);
            },
            error: function () {
                alert("error in mouseenter do_close_approve_invoice ");
            }
        });

    });

    $(document).on("click", "#load_invoice_details_modal", function (e) {

        var token_search = $("#token_search").val();
        var url = $("#ajax_sales_invoice_details").val();
        var auto_serial = $(this).data('autoserial');
        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                _token: token_search,
                auto_serial: auto_serial
            },
            success: function (data) {
                $("#DetailsSalesInvoiceModal").modal("show");
                $("#DetailsSalesInvoiceModaMlBody").html(data);



            },
            error: function () {
                alert("  error in load_invoice_details_modal");
            },
        });
    });



    $(document).on("change", "#customer_code_search", function (e) { 
        
        make_search()
    });
    $(document).on("change", "#delegates_code_search", function (e) { 
        make_search()
    });
    $(document).on("change", "#Sales_matrial_types_search", function (e) { 
        make_search()
    });
    $(document).on("change", "#pill_type_search", function (e) { 
        make_search()
    });
    $(document).on("change", "#discount_type_search", function (e) { 
        make_search()
    });
    $(document).on("change", "#is_approved_search", function (e) { 
        make_search()
    });
    $(document).on("change", "#invoice_date_from", function (e) { 
        make_search()
    });
    $(document).on("change", "#invoice_date_to", function (e) { 
        make_search()
    });
    $(document).on("input", "#search_by_text", function (e) { 
        make_search()
    });

    $('input[type=radio][name=searchbyradio]').change(function () {
        make_search();
    });



    $(document).on("input", "#searchbytextcustomer", function (e) {
        var searchbytextcustomer = $("#searchbytextcustomer").val();
        if (searchbytextcustomer == '') {
            $("#searchcustomerdiv").hide();
            return false ;
        } else {
            $("#searchcustomerdiv").show();
        }
        
        var token_search = $("#token_search").val();
        var url = $("#ajax_customer_search").val();
        
        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                _token: token_search,
                searchbytextcustomer: searchbytextcustomer
                
            },
            success: function (data) {
                $("#searchcustomerdiv").html(data);
            },
            error: function () {
                alert("error in searchbytextcustomer");
            },
        });
    });




    function get_item_uoms() {
        var item_code = $("#item_code").val();

        if (item_code != "") {
            var token_search = $("#token_search").val();
            var ajax_get_item_uoms_url = $("#ajax_get_uoms").val();

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
        var item_code = $("#item_code").val();
        var uom_id = $("#uom_id").val();
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
                    if (oldBatchid != null) {
                        $('#inv_itemcard_batches_id').val(oldBatchid)
                    }

                    get_item_price();
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

    //reload_items_in_invoice
    function add_new_item_sales_row() {
        var auto_serial = $("#invoice_auto_serial").val();

        var token_search = $("#token_search").val();
        var ajax_load_add_invoice = $("#ajax_add_new_item_sales_row").val();
        jQuery.ajax({
            url: ajax_load_add_invoice,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                _token: token_search,
                auto_serial: auto_serial,
            },
            success: function (data) {
                $("#active_items_salesDiv").html(data);
                recalculate();
            },
            error: function () {
                alert("  error in add_new_item_sales_row");
            },
        });
    }



    function recalculate() {

        var total_cost_items = 0;

        $(".item_total_array").each(function () {
            total_cost_items += parseFloat($(this).val());

        });

        $("#total_cost_items").val(total_cost_items);

        if (total_cost_items == "") {
            total_cost_items = 0;
        }
        total_cost_items = parseFloat(total_cost_items);
        var tax_percent = $("#tax_percent").val();
        if (tax_percent == "") {
            tax_percent = 0;
        }
        tax_percent = parseFloat(tax_percent);

        var tax_value = (total_cost_items * tax_percent) / 100;
        tax_value = parseFloat(tax_value);
        $("#tax_value").val(tax_value * 1);
        var total_befor_discount = total_cost_items + tax_value;
        $("#total_befor_discount").val(total_befor_discount);
        var discount_type = $("#discount_type").val();
        if (discount_type != "") {
            if (discount_type == 1) {
                var discount_percent = $("#discount_percent").val();
                if (discount_percent == "") {
                    discount_percent = 0;
                }
                discount_percent = parseFloat(discount_percent);
                var discount_value =
                    (total_befor_discount * discount_percent) / 100;
                $("#discount_value").val(discount_value * 1);
                var total_cost = total_befor_discount - discount_value;
                $("#total_cost").val(total_cost * 1);
            } else {
                var discount_percent = $("#discount_percent").val();
                if (discount_percent == "") {
                    discount_percent = 0;
                }
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

        what_paid = parseFloat(what_paid);
        total_cost = parseFloat(total_cost);
        $what_remain = total_cost - what_paid;
        $("#what_remain").val($what_remain * 1);
        if (pill_type == 1) {
            $('#what_paid').val(total_cost);
            $('#what_remain').val(0);
        }

        var token_search = $("#token_search").val();
        var url = $("#ajax_reload_invoice_details").val();
        var auto_serial = $("#invoice_auto_serial").val();
        var total_cost_items = $("#total_cost_items").val();
        var tax_percent = $("#tax_percent").val();
        var tax_value = $("#tax_value").val();
        var total_befor_discount = $("#total_befor_discount").val();
        var discount_type = $("#discount_type").val();
        var discount_percent = $("#discount_percent").val();
        var discount_value = $("#discount_value").val();
        var total_cost = $("#total_cost").val();
        var notes = $("#notes").val();
        var invoice_date = $("#invoice_date").val();
        var sales_material_type = $("#sales_material_type").val();
        var delegate_code = $("#delegate_code").val();
        var is_has_customer = $("#is_has_customer").val();
        var pill_type = $("#pill_type").val();



        if (is_has_customer == 1) {
            var customer_code = $("#customer_code").val();
        }


        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "json",
            cache: false,
            data: {
                '_token': token_search, auto_serial: auto_serial, total_cost_items: total_cost_items, tax_percent: tax_percent,
                total_befor_discount: total_befor_discount, discount_type: discount_type, discount_percent: discount_percent,
                discount_value: discount_value, total_cost: total_cost, notes: notes, tax_value: tax_value, invoice_date: invoice_date,
                sales_material_type: sales_material_type, delegate_code: delegate_code, customer_code: customer_code,
                is_has_customer: is_has_customer, pill_type: pill_type
            },
            success: function (data) {
                alert('تم التحديث');

            },
            error: function () {


                alert("error in recalculate");
            },
        });


    }


    function get_item_price() {
        var item_code = $("#item_code").val();
        var uom_id = $("#uom_id").val();
        var sales_item_type = $("#sales_item_type").val();
        var token_search = $("#token_search").val();
        var url = $("#ajax_get_item_price").val();

        jQuery.ajax({
            url: url,
            type: "post",
            dataType: "json",
            cache: false,
            data: {
                item_code: item_code,
                uom_id: uom_id,
                sales_item_type: sales_item_type,
                '_token': token_search,
            },
            success: function (data) {
                $("#price").val(data);

                calculate_total_item_price_row();
            },
            error: function () {
                $("#price").val();

                alert("error in get item price");
            },
        });
    }

    function calculate_total_item_price_row() {
        $quantity = $("#quantity").val();
        $unit_price = $("#price").val();
        if ($quantity == 0) {
            alert("ادخل الكمية ");
            return false;
        }

        if ($unit_price == 0) {
            alert("ادخل السعر الوحده  ");
            return false;
        }

        $("#item_total").val(
            parseFloat($quantity) * parseFloat($unit_price) * 1
        );
    }


    function update_sales_invoice(auto_serial) {
        
        var token_search = $("#token_search").val();
        var ajax_load_add_invoice = $("#ajax_do_update_sales_invoice").val();
        jQuery.ajax({
            url: ajax_load_add_invoice,
            type: "post",
            dataType: "html",
            cache: false,
            data: {
                '_token': token_search,
                auto_serial: auto_serial,
            },
            success: function (data) {
                $("#AddNewSalesInvoiceModaMlBody").html("");
                $("#AddNewSalesInvoiceModal").modal("hide");

                $("#UpdateSalesInvoiceModaMlBody").html(data);
                $("#UpdateSalesInvoiceModal").modal("show");
            },
            error: function () {
                alert("حدث خطأ ما");
            },
        });
    }



    function make_search() {
        var url = $("#ajax_search_url").val();
        var token_search = $("#token_search").val();
        var searchbyradio = $("input[type=radio][ name=searchbyradio]:checked").val();
        var search_by_text = $('#search_by_text').val();
        var customer_code_search = $('#customer_code_search').val();
        var delegates_code_search = $('#delegates_code_search').val();
        var Sales_matrial_types_search = $('#Sales_matrial_types_search').val();
        var pill_type_search = $('#pill_type_search').val();
        var discount_type_search = $('#discount_type_search').val();
        var is_approved_search = $('#is_approved_search').val();
        var invoice_date_from = $("#invoice_date_from").val();
        var invoice_date_to = $("#invoice_date_to").val();


        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
                searchbyradio: searchbyradio,
                search_by_text: search_by_text,
                customer_code_search: customer_code_search,
                delegates_code_search: delegates_code_search,
                Sales_matrial_types_search: Sales_matrial_types_search,
                pill_type_search: pill_type_search,
                discount_type_search: discount_type_search,
                is_approved_search: is_approved_search,
                invoice_date_from: invoice_date_from,
                invoice_date_to: invoice_date_to
            },
            success: function (data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function () { }
        });
    }


    function get_last_added_customer() {
        var url = $("#ajax_reload_customers").val();
        var token_search = $("#token_search").val();
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
            },
            success: function (data) {
                $("#customerDiv").html(data);
            },
            error: function () { }
        });
    }


});
