<?php

namespace App\Imports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class MedicinesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $successCount = 0;
    protected $skippedRows = [];

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        // Log to debug
        Log::info('Processing row:', $row);
        
        $productName = trim($row['productname'] ?? '');
        
        if (empty($productName)) {
            $this->skippedRows[] = $row;
            return null;
        }

        // Skip summary rows
        if (str_contains(strtolower($productName), 'total')) {
            $this->skippedRows[] = $row;
            return null;
        }

        // Check if exists
        if (Medicine::where('name', $productName)->exists()) {
            $this->skippedRows[] = $row;
            return null;
        }

        $this->successCount++;

        // Create new medicine with ALL fields mapped correctly
        $medicine = new Medicine();
        $medicine->product_code = isset($row['productcode']) ? (string)$row['productcode'] : null;
        $medicine->name = $productName;
        $medicine->generic_name = $row['generic_name'] ?? null;
        $medicine->generic_name_original = $row['generic_name'] ?? null;
        $medicine->manufacturer = $row['supplier_name'] ?? null;
        $medicine->supplier_name = $row['supplier_name'] ?? null;
        $medicine->supplier_code = isset($row['supplier_code']) ? (string)$row['supplier_code'] : null;
        $medicine->alt_supplier_codes = $row['alt_supplier_code'] ?? null;
        $medicine->category = $row['categorey'] ?? null;
        $medicine->price = isset($row['mrp']) ? (float)$row['mrp'] : 0;
        $medicine->purchase_price = isset($row['purchase_price']) ? (float)$row['purchase_price'] : 0;
        $medicine->purchase_unit = isset($row['purchaseunit']) ? (int)$row['purchaseunit'] : 1;
        $medicine->sale_unit = isset($row['saleunit']) ? (int)$row['saleunit'] : 1;
        $medicine->stock_quantity = 0;
        $medicine->unit = ($medicine->purchase_unit == $medicine->sale_unit) ? $medicine->purchase_unit . ' unit' : 'piece';
        $medicine->is_active = 1;
        
        return $medicine;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getSkippedRows()
    {
        return $this->skippedRows;
    }
}