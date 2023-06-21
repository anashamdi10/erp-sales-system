<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_invoices extends Model
{
    use HasFactory;
    protected $table = "sales_invoices";
    protected $fillable = [
        'sales_material_type', 'auto_serial', 'invoice_date', 'cutomer_code', 'is_approved', 'com_code',
        'notes', 'discount_type', 'discount_percent', 'discount_value', 'tax_percent', 'total_cost_items',
        'tax_value', 'total_befor_discount', 'total_cost', 'account_number', 'money_for_account', 'pill_type', 
        'what_paid', 'what_remain', 'treasuries_tranaction_id', 'cutomer_balance_befor', 'customer_balance_after', 
        'added_by', 'created_at', 'updated_at', 'updated_by', 'approved_by','is_has_customer'
    ];
}
