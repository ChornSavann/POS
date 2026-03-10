<?php

namespace App\Repository;

use App\Models\Bank;
use App\Models\ExpenseType;
use App\Models\ItemExpens;
use App\Repository\IRepository\IExpenseTypeRepository;

class ExpenseTypeRepository implements IExpenseTypeRepository
{
    /**
     * ទាញយកទិន្នន័យទាំងអស់ជាមួយ Search និង Filter
     */
    public function all($request)
    {
        return ExpenseType::query()
            ->with(['itemExpense', 'bank']) // ទាញយក Relationship មកជាមួយ
            ->when($request->search, function ($q, $search) {
                $q->where('code', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10); // ប្រើ Pagination ដើម្បីឱ្យ UI មើលទៅ Soft
    }
    public function bank()
    {
        return Bank::all();
    }

    public function itemExpense()
    {
        return ItemExpens::all();
    }
    public function store(array $data)
    {
        return ExpenseType::create($data);
    }

    public function find($id)
    {
        return ExpenseType::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = $this->find($id);
        return $item->delete();
    }
}
