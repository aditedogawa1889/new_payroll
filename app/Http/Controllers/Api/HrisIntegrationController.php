<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHrisIntegrationRequest;
use App\Models\EmployeeIntegration;
use Illuminate\Http\JsonResponse;

class HrisIntegrationController extends Controller
{
    public function store(StoreHrisIntegrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $validated['is_processed'] = 0;
        $validated['created_by'] = $request->user() ? $request->user()->name : 'API';
        $validated['updated_by'] = $request->user() ? $request->user()->name : 'API';

        $integration = EmployeeIntegration::create($validated);

        return response()->json([
            'message' => 'Integration stored',
            'integration_id' => $integration->id_integration,
        ], 201);
    }
}
