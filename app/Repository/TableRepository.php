<?php
namespace App\Repository;

use App\Models\Tables;
use App\Repository\IRepository\ITableRepository;

class TableRepository implements ITableRepository {

    public function all()
    {
        return Tables::all();
    }

    public function create(array $data)
    {
        return Tables::create($data);
    }

   public function update($id, array $data)
   {
        // ត្រូវតែទទួល $id មុនគេបង្អស់ដូចគ្នា
        $table = Tables::findOrFail($id);
        $table->update($data);
        return $table;
    }


    public function delete($id)
    {
        return Tables::destroy($id);
    }
}
