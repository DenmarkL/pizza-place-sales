<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPizzas;
use App\Http\Requests\UploadPizzaTypes;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pizza;
use App\Models\PizzaType;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
    private function sanitizeString($string)
    {
        // Convert smart quotes and symbols to regular characters
        $string = str_replace(['‘', '’', '“', '”', '–', '—'], ["'", "'", '"', '"', '-', '-'], $string);

        // Remove any remaining invalid UTF-8 characters
        return iconv('UTF-8', 'UTF-8//IGNORE', $string);
    }

    public function uploadPizzaTypes(UploadPizzaTypes $request)
    {
        $requiredHeaders = ['pizza_type_id', 'name', 'category', 'ingredients'];

        $file = $request->file('file');
        $result = validate_csv_headers($file, $requiredHeaders);

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        $handle = $result['handle'];
        $header = $result['header'];

        $this->importCsv($request->file('file'), function ($data) {
            PizzaType::updateOrCreate(
                ['pizza_type_id' => $data['pizza_type_id']],
                ['category' => $data['category'], 'name' => $data['name'], 'ingredients' => $this->sanitizeString($data['ingredients'])]
            );
        });
        return response()->json(['message' => 'Pizza types uploaded.']);
    }

    public function uploadPizzas(UploadPizzas $request)
    {
        $requiredHeaders = ['pizza_type_id', 'name', 'category', 'ingredients'];

        $file = $request->file('file');
        $result = validate_csv_headers($file, $requiredHeaders);

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        $handle = $result['handle'];
        $header = $result['header'];
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $this->importCsv($request->file('file'), function ($data) {
            Pizza::updateOrCreate(
                ['pizza_type_id' => $data['pizza_type_id'], 'size' => $data['size']],
                ['price' => $data['price']]
            );
        });
        return response()->json(['message' => 'Pizzas uploaded.']);
    }

    public function uploadOrders(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $this->importCsv($request->file('file'), function ($data) {
            Order::updateOrCreate(
                ['order_id' => $data['order_id']],
                ['date' => $data['date'], 'time' => $data['time']]
            );
        });
        return response()->json(['message' => 'Orders uploaded.']);
    }

    public function uploadOrderItems(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $this->importCsv($request->file('file'), function ($data) {
            OrderItem::updateOrCreate(
                ['order_id' => $data['order_id'], 'pizza_id' => $data['pizza_id']],
                ['quantity' => $data['quantity']]
            );
        });
        return response()->json(['message' => 'Order items uploaded.']);
    }

    private function importCsv($file, $callback)
    {
        $path = $file->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            $callback($data);
        }
    }
}
