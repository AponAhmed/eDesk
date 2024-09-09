<?php

namespace App\Http\Controllers;

use App\Models\Canned;
use Illuminate\Http\Request;

class CannedController extends Controller
{
    //
    public static function list($type = "hint")
    {
        return Canned::where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getHints()
    {
        return Canned::where('type', 'hint')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Store a newly created canned hint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'nullable|string|in:hint,other_type', // You can add more types as needed
        ]);

        try {
            // Create a new canned hint
            Canned::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'] ?? 'hint', // Default to 'hint' if not provided
            ]);

            // Return a success response with the created data
            return response()->json([
                'success' => true,
                'message' => 'Canned hint saved successfully.',
            ], 201);
        } catch (\Exception $e) {
            // If something goes wrong, return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to save canned hint.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $hint = Canned::findOrFail($id);

        // Perform the deletion
        $hint->delete();

        return response()->json(['message' => 'Hint deleted successfully'], 200);
    }
}
