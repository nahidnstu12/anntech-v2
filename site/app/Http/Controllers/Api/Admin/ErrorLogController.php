<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationErrorLogResource;
use App\Models\ApplicationErrorLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ErrorLogController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ApplicationErrorLog::query()
            ->with('user')
            ->latest('created_at');

        if ($request->filled('request_id')) {
            $query->where('request_id', $request->string('request_id')->toString());
        }

        if ($request->filled('exception_class')) {
            $query->where('exception_class', 'like', '%'.$request->string('exception_class')->toString().'%');
        }

        if ($request->filled('search')) {
            $term = '%'.$request->string('search')->toString().'%';
            $query->where(function ($q) use ($term): void {
                $q->where('message', 'like', $term)
                    ->orWhere('url', 'like', $term);
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from')->toString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to')->toString());
        }

        return ApplicationErrorLogResource::collection(
            $query->paginate(perPage: (int) $request->integer('per_page', 20))
        );
    }

    public function show(ApplicationErrorLog $errorLog): ApplicationErrorLogResource
    {
        $errorLog->load('user');

        return new ApplicationErrorLogResource($errorLog);
    }
}
