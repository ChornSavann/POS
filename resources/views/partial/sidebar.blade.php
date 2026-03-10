<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('./assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-bold">POS SYSTEM</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2 text-info"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ Request::is('products*') || Request::is('sales*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('products*') || Request::is('sales*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-box-seam-fill text-warning"></i>
                        <p>
                            Product Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ Route('products.index') }}" class="nav-link {{ Request::is('products') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>List Product</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ Route('products.create') }}" class="nav-link {{ Request::is('products/create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-plus-circle-dotted"></i>
                                <p>Create Product</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ Route('products.generateBarcode') }}" class="nav-link {{ Request::is('products/generateBarcode') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-upc-scan"></i>
                                <p>Print Barcode</p>
                            </a>
                        </li>

                    </ul>
                </li>
                {{-- Salere --}}
               <li class="nav-item {{ Request::is('sales*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('sales*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cart-check-fill text-warning"></i>
                        <p>
                            Sale Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('order.list') }}" class="nav-link {{ Request::is('sales') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-journal-text"></i>
                                <p>List Sales</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('sales/create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-plus-square-dotted"></i>
                                <p>Add Sale (POS)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('sales/returns*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-arrow-counterclockwise"></i>
                                <p>Sale Returns</p>
                            </a>
                        </li>
                    </ul>
                </li>
               {{-- // Purchase Management Menu --}}
               {{-- ១. ពិនិត្យមើលពាក្យ purchase ឱ្យមានអក្សរ 's' ដូចគ្នាទាំងអស់ដើម្បីកុំឱ្យច្រឡំ --}}
                <li class="nav-item {{ Request::is('purchases*') || Request::is('item-expenses*') || Request::is('banks*') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link {{ Request::is('purchases*') || Request::is('item-expenses*') || Request::is('banks*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cart-check-fill text-success"></i>
                        <p>
                            Manage Purchases
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        {{-- List Purchases --}}
                        <li class="nav-item">
                            <a href="{{ route('purchases.index') }}" class="nav-link {{ Request::is('purchases') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>List Purchase</p>
                            </a>
                        </li>

                        {{-- Create Purchase --}}
                        <li class="nav-item">
                            <a href="{{ route('purchases.create') }}" class="nav-link {{ Request::is('purchases/create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-plus-square"></i>
                                <p>Create</p>
                            </a>
                        </li>


                        {{-- Banks --}}
                        <li class="nav-item">
                            <a href="{{ route('bank.index') }}"
                            class="nav-link {{ (Request::is('bank*') || Route::is('bank.*')) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-bank text-info"></i>
                                <p>Banks</p>
                            </a>
                        </li>

                       {{-- Item Expenses - ប្រើ Icon រាងជាបញ្ជីទំនិញ --}}
                        <li class="nav-item">
                            <a href="{{ route('item_expense.index') }}"
                            class="nav-link {{ Request::is('item-expenses*') ? 'active' : '' }}"
                            style="transition: all 0.3s ease;">
                                <i class="nav-icon bi bi-list-ul text-primary"></i>
                                <p>Item Expenses</p>
                            </a>
                        </li>

                        {{-- Expenses Type - ប្រើ Icon រាងជាវិក្កយបត្រ ឬបង្កាន់ដៃ --}}
                        <li class="nav-item">
                            <a href="{{ route('expense_types.index') }}"
                            class="nav-link {{ Request::is('expense_types*') ? 'active' : '' }}"
                            style="transition: all 0.3s ease;">
                                <i class="nav-icon bi bi-receipt text-success"></i>
                                <p>Expenses Type</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Request::is('units*') || Request::is('category*') || Request::is('brand*') || Request::is('store*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('units*') || Request::is('category*') || Request::is('brand*') || Request::is('store*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear-wide-connected text-secondary"></i>
                        <p>Setting <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('units.index') }}" class="nav-link {{ Request::is('units*') ? 'active' : '' }}">
                                 <i class="nav-icon bi bi-unity"></i>
                                <p>Units</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link {{ Request::is('category*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-tags-fill"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brand.index') }}" class="nav-link {{ Request::is('brand*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-patch-check-fill"></i>
                                <p>Brands</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('store.index') }}" class="nav-link {{ Request::is('store*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shop"></i>
                                <p>Store</p>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a href="{{ route('table.index') }}" class="nav-link {{ Request::is('table*') ? 'active' : '' }}">
                               <i class="nav-icon fas fa-chair"></i>
                                <p>Table</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Request::is('users*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-badge-fill text-success"></i>
                        <p>Manage User <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>User List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}" class="nav-link {{ Request::is('users/create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-plus"></i>
                                <p>Create User</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Request::is('people*') || Request::is('customers*') || Request::is('suppliers*') ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ Request::is('people*') || Request::is('customers*') || Request::is('suppliers*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-person-vcard-fill text-primary"></i>
                          <p>
                              People
                              <i class="nav-arrow bi bi-chevron-right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="{{ route('customer.index') }}" class="nav-link {{ Request::is('customer*') ? 'active' : '' }}">
                                  <i class="nav-icon bi bi-person-check"></i>
                                  <p>Customer</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('suppliers.index') }}" class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}">
                                  <i class="nav-icon bi bi-truck"></i>
                                  <p>Supplier</p>
                              </a>
                          </li>
                           <li class="nav-item">
                              <a href="{{ route('seller.index') }}" class="nav-link {{ Request::is('seller*') ? 'active' : '' }}">
                                  <i class="nav-icon bi bi-person-badge"></i>
                                  <p>Seller</p>
                              </a>
                          </li>
                      </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('test.index') }}" class="nav-link {{ Request::is('test*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-palette-fill text-danger"></i>
                        <p>Test Design</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('reports*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line-fill text-danger"></i>
                        <p>
                            Reports
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        {{-- Sale Report --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('reports/sales*') ? 'active' : '' }}">
                                <i class=" bi bi-graph-up-arrow text-success"></i>
                                <p>Sale Reports</p>
                            </a>
                        </li>

                        {{-- Purchase Report --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('reports/purchases*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-cart-check text-warning"></i>
                                <p>Purchase Reports</p>
                            </a>
                        </li>

                        {{-- Expense Report --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('reports/expenses*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-cash-stack text-info"></i>
                                <p>Expense Reports</p>
                            </a>
                        </li>

                        {{-- Stock Report --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('reports/stocks*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-box-seam text-primary"></i>
                                <p>Stock Reports</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
