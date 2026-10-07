<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Activity::query()
            ->with('causer')
            ->latest();

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->integer('user_id'))
                ->where('causer_type', \App\Models\User::class);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->string('log_name')->toString());
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from')->toString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to')->toString());
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->string('search')->toString().'%');
        }

        return ActivityLogResource::collection(
            $query->paginate(perPage: (int) $request->integer('per_page', 20))
        );
    }
}
