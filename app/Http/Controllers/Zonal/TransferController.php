<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\TransferRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $query = TransferRequest::with(['teacher', 'fromSchool', 'toSchool']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        } else {
            $query->orderByRaw("FIELD(status, 'PENDING', 'APPROVED', 'REJECTED')");
        }

        $transfers = $query->orderBy('createdAt', 'desc')->paginate(20);

        return view('dashboard.zonal.transfers.index', compact('transfers'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $transfer = TransferRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:APPROVED,REJECTED',
        ]);

        $transfer->update([
            'status' => $request->status,
            'decidedBy' => auth()->id(),
            'decidedAt' => now(),
        ]);

        // If approved, move teacher to new school
        if ($request->status === 'APPROVED' && $transfer->toSchoolId) {
            Teacher::where('id', $transfer->teacherId)->update([
                'schoolId' => $transfer->toSchoolId,
                'joinedCurrentSchoolDate' => now(),
            ]);
        }

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'TRANSFER_' . $request->status,
            'resourceType' => 'transfer_request',
            'resourceId' => $id,
        ]);

        return back()->with('success', 'Transfer ' . strtolower($request->status) . ' successfully.');
    }
}
