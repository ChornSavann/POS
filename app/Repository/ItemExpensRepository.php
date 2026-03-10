<?php

namespace App\Repository;

use App\Models\ItemExpens;
use App\Repository\IRepository\IItemExpenseRepository;

class ItemExpensRepository implements IItemExpenseRepository
{

    public function all($request)
    {
        return ItemExpens::query()
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);
    }

    public function create(array $data)
    {
        return ItemExpens::create($data);
    }

    public function find($id)
    {
        return ItemExpens::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $item = ItemExpens::findOrFail($id);
        $item->update($data);

        return $item;
    }

    public function delete($id)
    {
        $item = ItemExpens::findOrFail($id);
        return $item->delete();
    }
}
