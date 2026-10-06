<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UpdateContactInquiryRequest;
use App\Http\Resources\ContactInquiryResource;
use App\Models\ContactInquiry;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Support\InquiryStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactInquiryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ContactInquiry::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->toString().'%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from')->toString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to')->toString());
        }

        return ContactInquiryResource::collection(
            $query->paginate(perPage: (int) $request->integer('per_page', 20))
        );
    }

    public function show(ContactInquiry $contactInquiry): JsonResponse
    {
        $contactInquiry->markReadIfUnread();

        return response()->json([
            'data' => new ContactInquiryResource($contactInquiry->fresh()),
        ]);
    }

    public function update(UpdateContactInquiryRequest $request, ContactInquiry $contactInquiry): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $originalStatus = $contactInquiry->status;
        $originalAssignee = $contactInquiry->assigned_to_user_id;
        $originalNotes = $contactInquiry->internal_notes;

        $contactInquiry->fill($request->validated());
        $contactInquiry->save();

        if ($request->has('status') && $contactInquiry->status !== $originalStatus) {
            AdminActivityLogger::inquiry('Inquiry status changed', $actor, $contactInquiry, [
                'inquiry_id' => $contactInquiry->id,
                'from' => $originalStatus,
                'to' => $contactInquiry->status,
            ]);
        }

        if ($request->has('internal_notes') && $contactInquiry->internal_notes !== $originalNotes) {
            AdminActivityLogger::inquiry('Inquiry notes updated', $actor, $contactInquiry, [
                'inquiry_id' => $contactInquiry->id,
            ]);
        }

        if ($request->has('assigned_to_user_id') && $contactInquiry->assigned_to_user_id !== $originalAssignee) {
            AdminActivityLogger::inquiry('Inquiry assigned', $actor, $contactInquiry, [
                'inquiry_id' => $contactInquiry->id,
                'assignee_id' => $contactInquiry->assigned_to_user_id,
            ]);
        }

        return response()->json([
            'data' => new ContactInquiryResource($contactInquiry),
            'message' => 'Inquiry updated.',
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user?->can('view-contact-inquiries') && ! $user?->can('manage-contact-inquiries')) {
            return response()->json(['data' => ['new_inquiries' => null]]);
        }

        $count = ContactInquiry::query()
            ->where('status', InquiryStatus::NEW)
            ->count();

        return response()->json(['data' => ['new_inquiries' => $count]]);
    }
}
