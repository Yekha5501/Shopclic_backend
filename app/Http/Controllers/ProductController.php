<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Show the form to add a new product
  public function index(Request $request)
{
    $userId = Auth::id(); // Authenticated user ID

    // Get the search term from the request
    $search = $request->query('search');

    // Fetch Products with Search Filter
    $products = Product::where('user_id', $userId)
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->paginate(5); // Adjust the number of items per page as needed

    return view('products.index', compact('products'));
}

    public function create()
    {
        return view('products.create');
    }

    // Store a new product in the database
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Create a new product and associate it with the authenticated user
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'user_id' => Auth::id(), // Associate the product with the authenticated user
        ]);

        // Redirect to the products list with a success message
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    // Show the form to edit an existing product
    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id); // Ensure only the user's own product is editable
        return view('products.edit', compact('product'));
    }

    // Update the product details
    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::where('user_id', Auth::id())->findOrFail($id); // Ensure the product belongs to the user
        $product->update($request->only('price', 'stock'));

        return redirect()->route('products.edit', $id)->with('success', 'Product updated successfully.');
    }

    // Show the form to upload Excel file
    public function importForm()
    {
        return view('products.import');
    }

    // Process the uploaded Excel file and store the data
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // validate file extension
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Load the Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($file);

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Loop through each row and insert the product data
        $data = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getFormattedValue();
            }

            // Skip the header row
            if ($row->getRowIndex() > 1) {
                $data[] = [
                    'name' => $rowData[0],
                    'price' => $rowData[1],
                    'stock' => $rowData[2],
                    'user_id' => Auth::id(), // Associate the products with the authenticated user
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert the data into the products table
        Product::insert($data);

        // Redirect back with success message
        return redirect()->route('products.index')->with('success', 'Products imported successfully!');
    }

    // Show details of a product
    public function show($id)
    {
        // Find the product by its ID, ensuring it belongs to the authenticated user
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        // Return a view with the product
        return view('products.show', compact('product'));
    }

    // Delete a product
    public function destroy($id)
    {
        // Find the product by ID, ensuring it belongs to the authenticated user
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        // Delete the product
        $product->delete();

        // Redirect back with a success message
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
