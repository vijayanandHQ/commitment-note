<?php
// app/Http/Requests/Admin/MedicineImportRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicineImportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ];
    }

    public function messages()
    {
        return [
            'import_file.required' => 'Please select a file to import',
            'import_file.mimes' => 'The file must be an Excel file (xlsx, xls, or csv)',
            'import_file.max' => 'The file size must not exceed 10MB',
        ];
    }
}