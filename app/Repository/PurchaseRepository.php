<?php
namespace App\Repository;

use App\Models\Purchase;
use App\Repository\IRepository\IPurchaseRepository;

class PurchaseRepository implements IPurchaseRepository {
    protected $model;

    public function __construct(Purchase $purchase) {
        $this->model = $purchase;
    }

    public function all(array $filters = []) {
        // ប្រាកដថាមាន relationship ឈ្មោះ supplier, store, និង user ក្នុង Model
        $query = $this->model->with(['supplier', 'store', 'user']);

        if (!empty($filters['search'])) {
            $query->where('reference_no', 'LIKE', "%{$filters['search']}%");
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    public function find($id) {
        return $this->model->with('items.product')->findOrFail($id);
    }

    public function create(array $data) {
        return $this->model->create($data);
    }

    public function update($id, array $data) {
        $purchase = $this->find($id);
        $purchase->update($data);
        return $purchase;
    }

    public function delete($id) {
        return $this->model->destroy($id);
    }

    public function getLatestReference() {
        return $this->model->latest()->first()?->reference_no;
    }
}
