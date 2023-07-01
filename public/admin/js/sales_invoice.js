$(document).ready(function () {
    $(document).on("change", "#item_code", function (e) {
        // نجيب وحدات للصنف
        get_item_uoms();
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
                    alert("حدث خطأ ما");
                },
            });
        } else {
            $("#UomDivAdd").html("");
            $(".UomDivAdd").hide();
            $("#inv_item_batches").hide();
        }
    }

    function get_inv_itemcard_batches() {
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
                    get_item_price();
                },
                error: function () {
                    $("#inv_itemcard_batchesDiv").hide();
                },
            });
        } else {
            $("#UomDiv").hide();
            $("#inv_itemcard_batchesDiv").hide();
        }
    }

    $(document).on("change", "#uom_id", function (e) {
        get_inv_itemcard_batches();
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

                $("#AddNewOfferPriceModaMlBody").html("");
                $("#AddNewOfferPriceModal").modal("hide");
            },
            error: function () {
                alert("حدث خطأ ما");
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
                alert("حدث خطأ ما");
            },
        });
    });

    $(document).on("change", "#sales_item_type", function (e) {
        get_item_price();
    });

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

                alert("error");
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
                recalculate_approved();
            },
            error: function () {
                alert("error");
            },
        });
    });

    $(document).on("click", ".remove_current", function (e) {
        e.preventDefault();
        $(this).closest("tr").remove();
        recalculate_approved();
    });

    function recalculate_approved() {
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
        if (what_paid == "") what_paid = 0;
        what_paid = parseFloat(what_paid);
        total_cost = parseFloat(total_cost);
        $what_remain = total_cost - what_paid;
        $("#what_remain").val($what_remain * 1);
    }

    $(document).on("change", "#pill_type", function (e) {
        var pill_type = $("#pill_type").val();
        var total_cost = $("#total_cost").val();

        if (pill_type == 1) {
            $("#what_paid").val(total_cost * 1);
            $("#what_remain").val(0);
            $("#what_paid").attr("readonly", true);
            recalculate_approved();
        } else {
            $("#what_paid").val(0);
            $("#what_remain").val(total_cost * 1);
            $("#what_paid").attr("readonly", false);
            recalculate_approved();
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
            recalculate_approved();
            return false;
        }

        if (parseFloat(treasures_balance) > parseFloat(what_paid)) {
            what_remain = total_cost - what_paid;

            $("#what_remain").val(what_remain);
        }

        recalculate_approved();
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

        recalculate_approved();
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

        recalculate_approved();
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

        recalculate_approved();
    });

    $(document).on("click", "#do_add_new_sales_invoice", function (e) {
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
        var delgate_code = $("#delgate_code").val();
        if (delgate_code == "") {
            alert("من فضلك اختر  المندوب  ");
            $("#delgate_code").focus();
            return false;
        }

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
               '_token': token,
            },
            success: function (auto_serial) {
                alert("تم الحفظ ");
                update_sales_invoice(auto_serial);
            },
            error: function () {
                alert("حدث خطأ ما ");
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

    $(document).on("click", ".load_update_sales_invoice", function (e) {
        var auto_serial = $(this).data("auto_serial");
        update_sales_invoice(auto_serial);
    });

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
                $("#AddNewOfferPriceModaMlBody").html("");
                $("#AddNewOfferPriceModal").modal("hide");

                $("#UpdateSalesInvoiceModaMlBody").html(data);
                $("#UpdateSalesInvoiceModal").modal("show");
            },
            error: function () {
                alert("حدث خطأ ما");
            },
        });
    }

    $(document).on("mouseenter", "#add_item_to_invoice_details", function (e) {
        get_inv_itemcard_batches();
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
            },
            error: function () {
                alert("error");
            },
        });
    });
});
