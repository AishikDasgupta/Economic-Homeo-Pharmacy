<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    /**
     * Set an image as the primary image for a product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setPrimary($id)
    {
        try {
            $image = ProductImage::findOrFail($id);
            $productId = $image->product_id;
            
            // Reset all images for this product to non-primary
            ProductImage::where('product_id', $productId)
                ->update(['is_primary' => false]);
            
            // Set this image as primary
            $image->is_primary = true;
            $image->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Primary image set successfully',
                'data' => $image
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified image from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $image = ProductImage::findOrFail($id);
            $productId = $image->product_id;
            $isPrimary = $image->is_primary;
            
            // Delete the image file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete the image record
            $image->delete();
            
            // If this was the primary image, set another image as primary if available
            if ($isPrimary) {
                $newPrimaryImage = ProductImage::where('product_id', $productId)->first();
                if ($newPrimaryImage) {
                    $newPrimaryImage->is_primary = true;
                    $newPrimaryImage->save();
                }
            }
            
            // Reorder remaining images
            $remainingImages = ProductImage::where('product_id', $productId)
                ->orderBy('sort_order')
                ->get();
            
            $order = 1;
            foreach ($remainingImages as $img) {
                $img->sort_order = $order++;
                $img->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update the sort order of product images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.sort_order' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            foreach ($request->images as $imageData) {
                $image = ProductImage::find($imageData['id']);
                if ($image) {
                    $image->sort_order = $imageData['sort_order'];
                    $image->save();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Image order updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}