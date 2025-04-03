<?php

namespace App\Http\Controllers;

use App\NotifData;
use App\Models\FormData;
use App\Jobs\SetLocation;
use App\Mail\FormDataMail;
use App\Jobs\TelegramMsgJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class FormDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'code' => 'required|string',
            'amount' => 'required|string',
        ]);

        $formData = FormData::create([
            'data' => $validated,
            'entries' => $request->except('type', 'code', 'amount'),
            'ip_address' => $request->ip(),
        ]);

        $data = [
            ...$validated,
            ...$formData->entries,
        ];

        $notifData = new NotifData("👉 <b>" . $validated['code'] . "</b>");
        $notifData->setSubject('A new form data has been submitted');
        $notifData->setBody(json_encode($data));
        TelegramMsgJob::dispatchSync($notifData);

        SetLocation::dispatch($request->ip(), $formData, 'location');

        if ($email = site_setting('receiver_email')) {
            Mail::to($email)->send(new FormDataMail($data));
        }

        return response()->json(['success' => true], 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $formData = FormData::findOrFail($id);
        $formData->delete();
        return back(303)->with('success', 'Form data deleted successfully.');
    }
}
