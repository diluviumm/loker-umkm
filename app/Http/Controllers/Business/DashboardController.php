<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $profile = $user->businessProfile;

        $stats = [
            'total'      => $user->jobListings()->count(),
            'aktif'      => $user->jobListings()->where('status', 'aktif')->count(),
            'pending'    => $user->jobListings()->where('status', 'pending')->count(),
            'ditolak'    => $user->jobListings()->where('status', 'ditolak')->count(),
            'kadaluarsa' => $user->jobListings()->where('status', 'kadaluarsa')->count(),
            'premium'    => $user->jobListings()->where('is_premium', true)->count(),
        ];

        $lokers = $user->jobListings()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('business.dashboard', compact('user', 'profile', 'lokers', 'stats'));
    }
}
