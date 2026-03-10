<?php

namespace App\Repository;

use App\Models\Bank;
use App\Repository\IRepository\IBankRepository;

class BankRepository implements IBankRepository
{
    public function all($request)
    {
        $query = Bank::query();
        if ($request->search)
        {
            $query->where(function ($q) use ($request)
            {
                $q->where('bank_name', 'like', '%' . $request->search . '%')
                ->orWhere('account_number', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status !== null && $request->status !== '')
        {
            $query->where('is_active', $request->status);
        }

        return $query->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return Bank::create($data);
    }

    public function find($id)
    {
        return Bank::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $bank = Bank::findOrFail($id);
        $bank->update($data);

        return $bank;
    }

    public function delete($id)
    {
        $bank = Bank::findOrFail($id);

        return $bank->delete();
    }
}
