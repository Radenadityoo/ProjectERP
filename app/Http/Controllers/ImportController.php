<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BomHeader;
use App\Models\BomComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function showUploadForm()
    {
        return view('import.upload');
    }

    public function processCsv(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->store('imports');
        $fullPath = Storage::path($path);

        $requiredProductCols = ['product_name','sku','category','cost','price','uom'];
        $requiredBomCols = ['bom_name','product_sku','component_sku','component_qty','unit_cost'];

        $handle = fopen($fullPath, 'r');
        if (! $handle) {
            return back()->withErrors(['file' => 'Cannot read uploaded file']);
        }

        $header = null;
        $products = [];
        $bomRows = [];

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($header === null) {
                $header = array_map(fn($h) => Str::slug(trim($h), '_'), $row);
                continue;
            }
            if (count(array_filter($row)) === 0) {
                continue;
            }
            $assoc = [];
            foreach ($header as $idx => $key) {
                $assoc[$key] = $row[$idx] ?? null;
            }
            $hasProductCols = count(array_intersect($requiredProductCols, array_keys($assoc))) === count($requiredProductCols);
            $hasBomCols = count(array_intersect($requiredBomCols, array_keys($assoc))) === count($requiredBomCols);

            if ($hasProductCols) {
                $products[] = $assoc;
            }
            if ($hasBomCols) {
                $bomRows[] = $assoc;
            }
        }
        fclose($handle);

        if (empty($products) && empty($bomRows)) {
            return back()->withErrors(['file' => 'CSV does not contain required columns.']);
        }

        DB::transaction(function () use ($products, $bomRows) {
            foreach ($products as $p) {
                $sku = trim($p['sku'] ?? '');
                if (! $sku) {
                    continue;
                }
                $product = Product::updateOrCreate(
                    ['reference' => $sku],
                    [
                        'name' => $p['product_name'] ?? $sku,
                        'category' => $p['category'] ?? null,
                        'cost' => (float) str_replace([','], '', $p['cost'] ?? 0),
                        'price' => (float) str_replace([','], '', $p['price'] ?? 0),
                        'uom' => $p['uom'] ?? 'pcs',
                        'type' => $p['category'] ?? null,
                    ]
                );
            }

            // Group BoM rows by bom_name
            $grouped = [];
            foreach ($bomRows as $row) {
                $bomName = $row['bom_name'] ?? null;
                if (! $bomName) continue;
                $grouped[$bomName][] = $row;
            }

            foreach ($grouped as $bomName => $rows) {
                $productSku = $rows[0]['product_sku'] ?? null;
                $product = $productSku ? Product::where('reference', $productSku)->first() : null;

                $bom = BomHeader::updateOrCreate(
                    ['name' => $bomName],
                    [
                        'product_id' => $product?->id,
                        'quantity' => 1,
                        'total_cost' => 0,
                    ]
                );

                $bom->components()->delete();
                $total = 0;
                foreach ($rows as $row) {
                    $componentSku = $row['component_sku'] ?? null;
                    $componentProduct = $componentSku ? Product::where('reference', $componentSku)->first() : null;
                    $qty = (float) str_replace([','], '', $row['component_qty'] ?? 0);
                    $unit = (float) str_replace([','], '', $row['unit_cost'] ?? 0);
                    $subtotal = $qty * $unit;

                    BomComponent::create([
                        'bom_id' => $bom->id,
                        'component_product_id' => $componentProduct?->id,
                        'qty' => $qty,
                        'unit_cost' => $unit,
                        'subtotal' => $subtotal,
                    ]);
                    $total += $subtotal;
                }

                $bom->update(['total_cost' => $total]);
            }
        });

        return back()->with('success', 'CSV imported successfully.');
    }
}
