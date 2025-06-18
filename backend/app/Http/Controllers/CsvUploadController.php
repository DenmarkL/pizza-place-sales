<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadOrderDetails;
use App\Http\Requests\UploadOrders;
use App\Http\Requests\UploadPizzas;
use App\Http\Requests\UploadPizzaTypes;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pizza;
use App\Models\PizzaType;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
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
                [
                    'category' => $data['category'],
                    'name' => $data['name'],
                    'ingredients' => $this->sanitizeString($data['ingredients'])
                ]
            );
        });
        return response()->json(['message' => 'Pizza types uploaded.']);
    }

    public function uploadPizzas(UploadPizzas $request)
    {
        $requiredHeaders = ['pizza_id', 'pizza_type_id', 'size', 'price'];

        $file = $request->file('file');
        $result = validate_csv_headers($file, $requiredHeaders);

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        $handle = $result['handle'];
        $header = $result['header'];

        $this->importCsv($file, function ($data) {
            Pizza::updateOrCreate(
                ['pizza_id' => $data['pizza_id']],
                [
                    'pizza_type_id' => $data['pizza_type_id'],
                    'size' => $data['size'],
                    'price' => $data['price']
                ]
            );
        });

        return response()->json(['message' => 'Pizzas uploaded.']);
    }

    public function uploadOrders(UploadOrders $request)
    {
        ini_set('max_execution_time', 300);
        $requiredHeaders = ['order_id', 'date', 'time'];

        $file = $request->file('file');
        $result = validate_csv_headers($file, $requiredHeaders);

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        $handle = $result['handle'];
        $header = $result['header'];

        $batchSize = 100; // adjust depending on server power
        $batch = [];
        $rowCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowCount++;

            if (count($row) !== count($header)) {
                continue; // skip malformed rows
            }

            $data = array_combine($header, $row);
            if (!$data) continue;

            $batch[] = [
                'id' => $data['order_id'],
                'date' => $data['date'],
                'time' => $data['time'],
            ];

            // Insert when batch is full
            if (count($batch) >= $batchSize) {
                $this->insertOrUpdateOrders($batch);
                $batch = [];
            }
        }

        // Final insert for remaining rows
        if (!empty($batch)) {
            $this->insertOrUpdateOrders($batch);
        }

        fclose($handle);

        return response()->json([
            'message' => "Orders uploaded successfully.",
            'rows' => $rowCount
        ]);
    }

    public function uploadOrderDetails(UploadOrderDetails $request)
    {
        ini_set('max_execution_time', 300);
        $requiredHeaders = ['order_details_id', 'order_id', 'pizza_id', 'quantity'];

        $file = $request->file('file');
        $result = validate_csv_headers($file, $requiredHeaders);

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        $handle = $result['handle'];
        $header = $result['header'];

        $batchSize = 100; // Customize as needed
        $batch = [];
        $rowCount = 0;
        $skipped = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowCount++;

            if (count($row) !== count($header)) {
                continue; // Skip malformed rows
            }

            $data = array_combine($header, $row);

            // Skip rows with missing data
            if (!$data || empty($data['order_details_id'])) {
                $skipped[] = $data;
                continue;
            }

            // ✅ Check foreign key existence
            if (!Order::where('id', $data['order_id'])->exists()) {
                $skipped[] = $data;
                continue; // or log or collect invalid rows
            }

            if (!Pizza::where('pizza_id', $data['pizza_id'])->exists()) {
                $skipped[] = $data;
                continue; // or log or collect invalid rows
            }

            $batch[] = [
                'order_details_id' => $data['order_details_id'],
                'order_id' => $data['order_id'],
                'pizza_id' => $data['pizza_id'],
                'quantity' => $data['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                $this->insertOrUpdateOrderDetails($batch);
                $batch = [];
            }
        }

        return response()->json([
            'message' => "Orders uploaded successfully.",
            'rows' => $rowCount,
            'skipped' => count($skipped)
        ]);
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

    private function insertOrUpdateOrders(array $orders)
    {
        foreach ($orders as $order) {
            Order::updateOrCreate(
                ['id' => $order['id']],
                ['date' => $order['date'], 'time' => $order['time']]
            );
        }
    }

    private function insertOrUpdateOrderDetails(array $orders)
    {
        foreach ($orders as $data) {
            OrderDetail::updateOrCreate(
                ['order_details_id' => $data['order_details_id']],
                [
                    'id' => $data['order_id'],
                    'pizza_id' => $data['pizza_id'],
                    'quantity' => $data['quantity'],
                    'updated_at' => $data['updated_at'],
                ]
            );
        }
    }

    private function sanitizeString($string)
    {
        // Convert smart quotes and symbols to regular characters
        $string = str_replace(['‘', '’', '“', '”', '–', '—'], ["'", "'", '"', '"', '-', '-'], $string);

        // Remove any remaining invalid UTF-8 characters
        return iconv('UTF-8', 'UTF-8//IGNORE', $string);
    }
}
