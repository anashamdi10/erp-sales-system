<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src=" {{asset('admin/dist/img/AdminLTELogo.png')}} " alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src=" {{asset('admin/dist/img/user2-160x160.jpg')}}  " class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{auth()->user()->name}}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->



        <li class="nav-item has-treeview {{  (request()->is('admin/adminpanelsetting*') ||request()->is('admin/treasures*') )? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  (request()->is('admin/adminpanelsetting*') ||request()->is('admin/treasures*') )? 'active':''}} ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              ضبط العام
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('admin.adminPanelSettings.index')}}" class="nav-link {{  (request()->is('admin/adminpanelsetting*')  )? 'active':''}}">
                *
                <p>
                  الضبط العام
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.treasures.index')}}" class="nav-link {{  (request()->is('admin/treasures*') )? 'active':''}}">
                *
                <p>
                  بيانات الخزن
                </p>
              </a>
            </li>

          </ul>
        </li>
        <li class="nav-item has-treeview {{ ( (request()->is('admin/account_types*') ||request()->is('admin/accounts*') ||request()->is('admin/customer*') ||request()->is('admin/suppliers_categories*') ||request()->is('admin/suppliers*')||request()->is('admin/collect_tranaction*')||request()->is('admin/exchange_tranaction*')||request()->is('admin/delegates*')) && !request()->is('admin/suppliers_orders*') ) ? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  ((request()->is('admin/account_types*') ||request()->is('admin/accounts*') ||request()->is('admin/customer*') ||request()->is('admin/suppliers_categories*') ||request()->is('admin/suppliers*')||request()->is('admin/collect_tranaction*') ||request()->is('admin/exchange_tranaction*')||request()->is('admin/delegates*'))&& !request()->is('admin/suppliers_orders*'))? 'active':''}} ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              الحسابات
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="{{ route('admin.account_types.index') }}" class="nav-link {{  (request()->is('admin/account_types*')  )? 'active':''}}">
                <p>
                  انواع الحسابات المالية
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.accounts.index') }}" class="nav-link {{  (request()->is('admin/accounts*')  )? 'active':''}}">
                <p>
                  الحسابات (الشجرة المحاسبية)
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.customer.index') }}" class="nav-link {{  (request()->is('admin/customer*')  )? 'active':''}}">
                <p>
                  حسابات العملاء
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.delegates.index') }}" class="nav-link {{  (request()->is('admin/delegates*')  )? 'active':''}}">
                <p>
                  حسابات المناديب
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.suppliers_categories.index') }}" class="nav-link {{ (request()->is('admin/suppliers_categories*') )?'active':'' }}">
                <p>
                  فئات الموردين
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.suppliers.index') }}" class="nav-link  {{  (request()->is('admin/suppliers*') and !request()->is('admin/suppliers_categories*') )? 'active':''}}">
                <p>
                  حسابات الموردين
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.collect_tranaction.index') }}" class="nav-link {{  (request()->is('admin/collect_tranaction*')   )? 'active':''}}">
                <p>
                  تحصيل النقدية
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.exchange_tranaction.index') }}" class="nav-link {{  (request()->is('admin/exchange_tranaction*')   )? 'active':''}}">
                <p>
                  صرف النقدية
                </p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item has-treeview {{  (request()->is('admin/sales_material_types*') ||request()->is('admin/store*')||request()->is('admin/uoms*')||request()->is('admin/inv_itemcard_categories*')||request()->is('admin/inv_itemcard*') )? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  (request()->is('admin/sales_material_types*') ||request()->is('admin/store*')||request()->is('admin/uoms*')||request()->is('admin/inv_itemcard_categories*')||request()->is('admin/inv_itemcard*') )? 'active':''}}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              ضبط المخازن
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item   ">
              <a href="{{route('admin.sales_material_types.index')}}" class="nav-link  {{  (request()->is('admin/sales_material_types*')  )? 'active':''}}">
                *
                <p>
                  بيانات فئات الفواتير
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('admin.stores.index')}}" class="nav-link {{  (request()->is('admin/store*')  )? 'active':''}}">
                *
                <p>
                  بيانات المخازن
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('admin.uoms.index')}}" class="nav-link {{  (request()->is('admin/uoms*')  )? 'active':''}}">
                *
                <p>
                  بيانات الوحدات
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('inv_itemcard_categories.index')}}" class="nav-link {{  (request()->is('admin/inv_itemcard_categories*')  )? 'active':''}}">
                *
                <p>
                  فئات الاصناف
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('inv_itemcard.index')}}" class="nav-link {{  (request()->is('admin/inv_itemcard*') and !request()->is('admin/inv_itemcard_categories*') )? 'active':''}}">
                *
                <p>
                  الاصناف
                </p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item has-treeview  {{  (request()->is('admin/suppliers_orders*') || request()->is('admin/suppliers_orders_general_return*') )? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  ( request()->is('admin/suppliers_orders*') ||request()->is('admin/suppliers_orders_general_return*') )? 'active':''}} ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              حركات مخزنية
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('admin.suppliers_orders.index')}}" class="nav-link {{  (request()->is('admin/suppliers_orders*') and !request()->is('admin/suppliers_orders_general_return*')  )? 'active':''}}">
                *
                <p>
                  فواتير المشتريات
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.suppliers_orders_general_return.index')}}" class="nav-link {{  (request()->is('admin/suppliers_orders_general_return*')  )? 'active':''}}">
                *
                <p>
                  فواتير مرتجع المشتريات العام
                </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview  {{  (request()->is('admin/SalesInvoices*') )? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  ( request()->is('admin/SalesInvoices*') )? 'active':''}} ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              المبيعات
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('admin.SalesInvoices.index')}}" class="nav-link {{  (request()->is('admin/SalesInvoices*')  )? 'active':''}}">
                *
                <p>
                  فواتير المبيعات
                </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview  {{  (request()->is('admin/admin_shift*') )? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  (request()->is('admin/admin_shift*') )? 'active':''}} ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              حركة شيفت الخزينة
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('admin.admin_shift.index')}}" class="nav-link {{  (request()->is('admin/admin_shift*')  )? 'active':''}}">
                *
                <p>
                  شفتات الخزن
                </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview  {{  (request()->is('admin/admins_accounts*') )? 'menu-open':''}} ">
          <a href="#" class="nav-link {{  (request()->is('admin/admins_accounts*') ||request()->is('admin/admins_accounts*') )? 'active':''}} ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              صلاحيات
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('admin.admins_accounts.index')}}" class="nav-link {{  (request()->is('admin/admins_accounts*')  )? 'active':''}}">
                *
                <p>
                  المستخدمين
                </p>
              </a>
            </li>

          </ul>
        </li>
        <li class="nav-item has-treeview  ">
          <a href="#" class="nav-link  ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              التقارير
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
          </ul>
        </li>
        <li class="nav-item has-treeview  ">
          <a href="#" class="nav-link  ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              المراقبة والدعم الفني
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>