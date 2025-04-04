<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\FormData;
use Illuminate\Http\Request;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Request as FacadesRequest;

class HomeController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'filters' => FacadesRequest::all('search', 'sort', 'id'),
            'form_datas' => FormData::filter(FacadesRequest::only('search', 'sort'))
                ->paginate(FacadesRequest::get('per_page', 20))
                ->withQueryString()
                ->through(
                    fn($item) => [
                        ...$item->toArray(),
                        'created_at' => $item->created_at->diffForHumans(),
                    ],
                ),
            'setting' => site_setting(),
        ]);
    }

    public function tokenManager()
    {
        $data = (new TokenController())->getTokensData();
        return Inertia::render('TokenManager', [
            ...$data
        ]);
    }

    public function settingUpdate(Request $request)
    {
        $request->validate([
            'receiver_email' => 'required|email',
            'invert_code' => 'required|boolean',
            'secure_api' => 'required|boolean',
            'delay' => 'required|integer|min:0',
        ]);

        site_setting()->update($request->all());

        return back(303)->with('success', 'Setting updated successfully.');
    }
}
