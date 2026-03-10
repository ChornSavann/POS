<?php

namespace App\Http\Controllers;

use App\Request\TableRequest;
use App\Service\IService\ITableService;


class TableController extends Controller
{
   protected $tableService;

    public function __construct(ITableService $tableService)
    {
        $this->tableService = $tableService;
    }

    public function index()
    {
        $tables = $this->tableService->getAllTable();
        return view('table.index', compact('tables'));
    }

   public function store(TableRequest $request)
    {
        $data = $request->validated();
        $table = $this->tableService->createTable($data);

        return response()->json([
            'success' => true,
            'message' => 'តុថ្មីត្រូវបានបង្កើតដោយជោគជ័យ!',
            'data'    => $table
        ], 201);
    }
    public function update(TableRequest $request, $id)
    {
        $data = $request->validated();
        $table=$this->tableService->updateTable($data, $id);
        return response()->json([
            'success' => true,
            'message' => 'កែប្រែព័ត៌មានតុជោគជ័យ!',
            'data'    => $table // $table ឥឡូវគឺជា Object មិនមែន true/false ទេ
        ]);
    }
    public function destroy($id)
    {
        try {
            $this->tableService->deleteTable($id);

            return response()->json([
                'success' => true,
                'message' => 'តុត្រូវបានលុបដោយជោគជ័យ!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាក្នុងការលុបតុនេះ។'
            ], 500);
        }
    }
}
