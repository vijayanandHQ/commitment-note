<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommitmentNote;
use App\Models\CommitmentNotesProduct;

class ReportController extends Controller
{
    public function csSales()
    {
        $commitments = CommitmentNote::with(['products' => function ($query) {
                $query->where('contacted_status', 0)
                      ->with('supplier');
            }])
            ->whereHas('products', function ($query) {
                $query->where('contacted_status', 0);
            })
            ->latest()
            ->get();

        return view('admin.reports.cs-sales', compact('commitments'));
    }

    public function csReturn()
    {
        $commitments = CommitmentNote::with(['products' => function ($query) {
                $query->where('returned_status', 0)
                      ->with('supplier');
            }])
            ->whereHas('products', function ($query) {
                $query->where('returned_status', 0);
            })
            ->latest()
            ->get();

        return view('admin.reports.cs-return', compact('commitments'));
    }

    public function csNilStock()
    {
        $commitments = CommitmentNote::with(['products' => function ($query) {
                $query->where('ns_status', 0)
                      ->with('supplier');
            }])
            ->whereHas('products', function ($query) {
                $query->where('ns_status', 0);
            })
            ->latest()
            ->get();

        return view('admin.reports.cs-nil-stock', compact('commitments'));
    }
}
