<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function importForm()
    {
        return view('imports.excel-form'); // Ensure the blade file exists
    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Validate file extension
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Load the Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($file);

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Loop through each row and process the product data
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getFormattedValue();
            }

            // Skip the header row
            if ($row->getRowIndex() > 1) {
                $name = $rowData[0];
                $price = $rowData[1];
                $stock = $rowData[2];

                // Check if the product already exists
                $product = Product::where('name', $name)->first();

                if ($product) {
                    // If the product exists, update the stock
                    $product->stock += $stock;
                    $product->price = $price; // Optionally update the price
                    $product->updated_at = now();
                    $product->user_id = auth()->id(); // Associate with the current user
                    $product->save();
                } else {
                    // If the product doesn't exist, create a new one
                    Product::create([
                        'name' => $name,
                        'price' => $price,
                        'stock' => $stock,
                        'user_id' => auth()->id(), // Associate with the current user
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Redirect back with a success message
        return redirect()->route('products.index')->with('success', 'Products imported successfully!');
    }
}

