<?php

use Illuminate\Http\Resources\Json\ResourceCollection;

function sendResponse($status, $message, $data = null, $statusCode = 200, $additional = null)
{
    // Initialize the base response data
    $responseData = [
        'success' => $status,
        'message' => $message,
    ];

    // Check if the data is a Laravel ResourceCollection
    if ($data instanceof ResourceCollection) {
        // Get the paginated data and metadata from the resource collection
        $paginatedData = $data->response()->getData(true);

        // Merge the paginated data into your response structure
        $responseData = array_merge($responseData, [
            'data' => $paginatedData['data'],
            'links' => $paginatedData['links'],
            'meta' => $paginatedData['meta'],
        ]);
    } else {
        // If it's not a collection, just add the data directly
        $responseData['data'] = $data;
    }

    // Merge any additional data if provided
    if (!empty($additional) && is_array($additional)) {
        $responseData = array_merge($responseData, $additional);
    }

    return response()->json($responseData, $statusCode);
}
