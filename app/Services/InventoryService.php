<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Manufacturing;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Process inventory when a manufacturing order is completed
     * - Reduces component product quantities
     * - Increases finished product quantity
     */
    public function completeManufacturingOrder(Manufacturing $mo)
    {
        return DB::transaction(function () use ($mo) {
            $moQuantity = floatval($mo->quantity);

            // If BOM is used, calculate and reduce component quantities
            if ($mo->bom_id && $mo->bom) {
                $bom = $mo->bom;
                $bomBaseQty = floatval($bom->quantity) ?: 1;

                foreach ($bom->components as $component) {
                    $componentProduct = $component->componentProduct;
                    if (!$componentProduct) continue;

                    // Calculate quantity needed: (component qty / bom base qty) * mo qty
                    $neededQty = (floatval($component->qty) / $bomBaseQty) * $moQuantity;

                    // Reduce component quantity
                    if ($componentProduct->track_inventory) {
                        $componentProduct->decrement('quantity', $neededQty);

                        // Record the movement
                        InventoryMovement::create([
                            'product_id' => $componentProduct->id,
                            'reference_type' => 'manufacturing_order',
                            'reference_id' => $mo->id,
                            'quantity_in' => 0,
                            'quantity_out' => $neededQty,
                            'notes' => "Components used for MO {$mo->reference}",
                        ]);
                    }
                }
            }

            // Increase finished product quantity
            if ($mo->product_id) {
                $finishedProduct = Product::find($mo->product_id);
                if ($finishedProduct && $finishedProduct->track_inventory) {
                    $finishedProduct->increment('quantity', $moQuantity);

                    // Record the movement
                    InventoryMovement::create([
                        'product_id' => $finishedProduct->id,
                        'reference_type' => 'manufacturing_order',
                        'reference_id' => $mo->id,
                        'quantity_in' => $moQuantity,
                        'quantity_out' => 0,
                        'notes' => "Finished product from MO {$mo->reference}",
                    ]);
                }
            }
        });
    }

    /**
     * Reverse inventory changes when a manufacturing order status changes back from done
     */
    public function reverseManufacturingOrder(Manufacturing $mo)
    {
        return DB::transaction(function () use ($mo) {
            $moQuantity = floatval($mo->quantity);

            // Reverse component reductions
            if ($mo->bom_id && $mo->bom) {
                $bom = $mo->bom;
                $bomBaseQty = floatval($bom->quantity) ?: 1;

                foreach ($bom->components as $component) {
                    $componentProduct = $component->componentProduct;
                    if (!$componentProduct) continue;

                    $neededQty = (floatval($component->qty) / $bomBaseQty) * $moQuantity;

                    if ($componentProduct->track_inventory) {
                        $componentProduct->increment('quantity', $neededQty);
                    }
                }
            }

            // Reverse finished product increase
            if ($mo->product_id) {
                $finishedProduct = Product::find($mo->product_id);
                if ($finishedProduct && $finishedProduct->track_inventory) {
                    $finishedProduct->decrement('quantity', $moQuantity);
                }
            }

            // Delete movements related to this MO
            InventoryMovement::where('reference_type', 'manufacturing_order')
                ->where('reference_id', $mo->id)
                ->delete();
        });
    }
}
