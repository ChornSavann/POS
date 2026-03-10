<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Repository\IRepository\IUserRepository;
use App\Repository\UserRepository;
use App\Service\IService\IUserService;
use App\Service\UserService;
use App\Repository\IRepository\ICategoryRepository;
use App\Repository\CategoryRepository;
use App\Service\IService\ICategoryService;
use App\Service\CategoryService;
use App\Repository\IRepository\IUnitsRepository;
use App\Repository\UnitRepository;
use App\Service\IService\IUnitService;
use App\Service\UnitService;
use App\Repository\IRepository\IBrandRepository;
use App\Repository\BrandRepository;
use App\Service\IService\IBrandService;
use App\Service\BrandService;
use App\Repository\IRepository\IStoreRepository;
use App\Repository\StoreRepository;
use App\Service\IService\IStoreService;
use App\Service\StoreService;
use App\Repository\IRepository\ISupplierRepository;
use App\Repository\SupplierRepository;
use App\Service\IService\ISupplierService;
use App\Service\SupplierService;
use Illuminate\Pagination\Paginator;
use App\Repository\IRepository\ICustomerRepository;
use App\Repository\CustomerRepository;
use App\Service\IService\ICustomerService;
use App\Service\CustomerService;
use App\Repository\IRepository\ISellerRepository;
use App\Repository\SellerRepository;
use App\Service\IService\ISellerService;
use App\Service\SellerService;
use App\Repository\IRepository\IPurchaseRepository;
use App\Repository\PurchaseRepository;
use App\Service\IService\IPurchaseService;
use App\Service\PurchaseService;
use App\Repository\IRepository\IProductRepository;
use App\Repository\ProductRepository;
use App\Service\IService\IProductService;
use App\Service\IService\IBankService;
use App\Service\BankService;
use App\Repository\IRepository\IBankRepository;
use App\Repository\BankRepository;
use App\Repository\ExpenseTypeRepository;
use App\Repository\IRepository\IExpenseTypeRepository;
use App\Service\ExpenseTypeService as ServiceExpenseTypeService;
use App\Service\IService\IExpenseTypeService;
use App\Service\ProductService as ServiceProductService;
use Illuminate\Support\Facades\View;
use App\Service\ProductService;
use App\Service\ExpenseTypeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repository\IRepository\IUserRepository::class,
            \App\Repository\UserRepository::class
        );
        $this->app->bind(
            \App\Service\IService\IUserService::class,
            \App\Service\UserService::class
        );

        $this->app->bind(
            \App\Repository\IRepository\ICategoryRepository::class,
            \App\Repository\CategoryRepository::class
        );
        $this->app->bind(
            \App\Service\IService\ICategoryService::class,
            \App\Service\CategoryService::class
        );

        $this->app->bind(
            \App\Repository\IRepository\IUnitsRepository::class,
            \App\Repository\UnitRepository::class
        );
        $this->app->bind(
            \App\Service\IService\IUnitService::class,
            \App\Service\UnitService::class
        );

        $this->app->bind(
            \App\Repository\IRepository\IBrandRepository::class,
            \App\Repository\BrandRepository::class
        );
        $this->app->bind(
            \App\Service\IService\IBrandService::class,
            \App\Service\BrandService::class
        );
         $this->app->bind(
            \App\Repository\IRepository\IStoreRepository::class,
            \App\Repository\StoreRepository::class
        );
        $this->app->bind(
            \App\Service\IService\IStoreService::class,
            \App\Service\StoreService::class
        );
        $this->app->bind(
                \App\Repository\IRepository\ISupplierRepository::class,
                \App\Repository\SupplierRepository::class
        );
        $this->app->bind(
                \App\Service\IService\ISupplierService::class,
                \App\Service\SupplierService::class
        );

        $this->app->bind(
            \App\Repository\IRepository\ICustomerRepository::class,
            \App\Repository\CustomerRepository::class
        );
        $this->app->bind(
            \App\Service\IService\ICustomerService::class,
            \App\Service\CustomerService::class
        );

        $this->app->bind(
            \App\Repository\IRepository\ISellerRepository::class,
            \App\Repository\SellerRepository::class
        );
        $this->app->bind(
            \App\Service\IService\ISellerService::class,
            \App\Service\SellerService::class
        );
      // Bind Repository
        $this->app->bind(
            \App\Repository\IRepository\IProductRepository::class,
            \App\Repository\ProductRepository::class
        );

        // Bind Service
        $this->app->bind(
            \App\Service\IService\IProductService::class,
            \App\Service\ProductService::class
        );
        // Bind Repository
        $this->app->bind(
            \App\Repository\IRepository\IPurchaseRepository::class,
            \App\Repository\PurchaseRepository::class
        );

        // Bind Service
        $this->app->bind(
            \App\Service\IService\IPurchaseService::class,
            \App\Service\PurchaseService::class
        );

        // Bind Repository
        $this->app->bind(
            \App\Repository\IRepository\ITableRepository::class,
            \App\Repository\TableRepository::class
        );

        // Bind Service
        $this->app->bind(
            \App\Service\IService\ITableService::class,
            \App\Service\TableService::class
        );
        // Bind Repository
        $this->app->bind(
            \App\Repository\IRepository\IOrderRepository::class,
            \App\Repository\OrderRepository::class
        );

        // Bind Service
        $this->app->bind(
            \App\Service\IService\IOrderService::class,
            \App\Service\OrderService::class
        );
         // Bind Repository
        $this->app->bind(
            \App\Repository\IRepository\IBankRepository::class,
            \App\Repository\BankRepository::class
        );

        // Bind Service
        $this->app->bind(
            \App\Service\IService\IBankService::class,
            \App\Service\BankService::class
        );
         // Bind Repository
        $this->app->bind(
            \App\Repository\IRepository\IItemExpenseRepository::class,
            \App\Repository\ItemExpensRepository::class
        );

        // Bind Service
        $this->app->bind(
            \App\Service\IService\IItemExpenseService::class,
            \App\Service\ItemExpenseService::class
        );

        $this->app->bind(
            IExpenseTypeRepository::class,
            ExpenseTypeRepository::class
        );
        $this->app->bind(
            IExpenseTypeService::class,
            ExpenseTypeService::class
        );

        $this->app->bind(
            IExpenseTypeRepository::class,
            ExpenseTypeRepository::class
        );
        $this->app->bind(
            IExpenseTypeService::class,
            ExpenseTypeService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
         View::composer('partial.navbar', function ($view) {
            $lowStockData = app(\App\Service\ProductService::class)->getLowStockProducts();
            $view->with([
                'lowStockProducts' => $lowStockData['lowStockProducts'],
                'lowStockCount'    => $lowStockData['lowStockCount']
            ]);
        });

        view()->composer('order.*', function ($view) {
            $view->with('store', \App\Models\Stores::first());
        });
    }
}
