<?php
namespace App\Repository;
use App\Models\Stores;
use App\Repository\IRepository\IStoreRepository;
class StoreRepository implements IStoreRepository {
    public function all($request) 
    {
        // ១. ទាញយកតម្លៃលេខពី Request (បើគ្មាន ប្រើតម្លៃ Default គឺ 10)
        // ត្រូវប្រាកដថាវាជា Integer
        $perPage = (int) $request->get('perPage', 10); 
        
        $search = $request->get('search');
        $status = $request->get('status');

        return Stores::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage) // ឥឡូវ $perPage គឺជាលេខហើយ (ឧទាហរណ៍: 10)
            ->withQueryString();
    }

    public function find($id) {
        return Stores::find($id);
    }

    public function create(array $data) {
        return Stores::create($data);
    }

    public function update($id, array $data) {
        $store = Stores::find($id);
        if ($store) {
            $store->update($data);
            return $store;
        }
        return null;
    }

    public function delete($id) {
        $store = Stores::find($id);
        if ($store) {
            return $store->delete();
        }
        return false;
    }
}