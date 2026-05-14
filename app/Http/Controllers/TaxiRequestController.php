<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxiRequest;
use App\Models\User;
use Carbon\Carbon;

class TaxiRequestController extends Controller
{
    public function gestorIndex()
    {
        $query = TaxiRequest::orderBy('created_at', 'desc');
        $requests = $query->get(); // Get all for current view logic

        $stats = [
            'solicitados' => TaxiRequest::where('status', 'solicitado')->count(),
            'en_viaje' => TaxiRequest::where('status', 'en_viaje')->count(),
            'completados' => TaxiRequest::where('status', 'completado')->count(),
            'cancelados' => TaxiRequest::where('status', 'cancelado')->count(),
        ];

        return view('gestor.index', compact('requests', 'stats'));
    }

    public function index()
    {
        $query = TaxiRequest::orderBy('created_at', 'desc');
        
        // If not authorized to see all, only see own requests
        if (!auth()->user()->canViewAllRequests()) {
            $query->where('user_id', auth()->id());
        }

        $requests = $query->paginate(10);

        // Stats
        $stats = [
            'solicitados' => TaxiRequest::where('status', 'solicitado')->count(),
            'en_viaje' => TaxiRequest::where('status', 'en_viaje')->count(),
            'completados' => TaxiRequest::where('status', 'completado')->count(),
            'cancelados' => TaxiRequest::where('status', 'cancelado')->count(),
        ];

        return view('taxi-request', compact('requests', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'rut_body' => 'required|numeric|digits_between:7,8',
            'rut_dv' => ['required', 'string', 'size:1', 'regex:/^[0-9Kk]$/'],
            'phone' => 'required|digits:8',
            'is_associated_ot' => 'boolean',
            'project_prefix' => 'required_if:is_associated_ot,1|nullable|string',
            'project_number' => 'required_if:is_associated_ot,1|nullable|numeric',
            'start_address' => 'required|string',
            'destination_address' => 'required|string',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
        ]);

        $requestTime = Carbon::now();
        $estimatedArrival = $requestTime->copy()->addMinutes(rand(15, 30));
        
        $taxiRequest = TaxiRequest::create([
            'user_id' => auth()->id(),
            'email' => $validated['email'],
            'rut' => $validated['rut_body'] . '-' . $validated['rut_dv'],
            'phone' => '+56 9 ' . $validated['phone'],
            'is_associated_ot' => $request->has('is_associated_ot'),
            'project_number' => $request->has('is_associated_ot') ? $validated['project_prefix'] . ' ' . $validated['project_number'] : null,
            'start_address' => $validated['start_address'],
            'destination_address' => $validated['destination_address'],
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'],
            'price' => 0, // Initial price is 0 (Pending)
            'request_time' => $requestTime,
            'estimated_arrival_time' => $estimatedArrival,
        ]);

        return back()->with('success', [
            'message' => 'Solicitud de taxi creada correctamente.',
            'arrival_time' => $estimatedArrival->format('H:i'),
        ]);
    }

    public function updateStatus(TaxiRequest $taxiRequest, Request $request)
    {
        $data = ['status' => $request->status];

        if ($request->status === 'en_viaje' && !$taxiRequest->started_at) {
            $data['started_at'] = now();
        }

        if ($request->status === 'completado') {
            // Limpiar el monto: quitar los puntos (separador de miles chileno)
            if ($request->has('price')) {
                $cleanPrice = str_replace('.', '', $request->price);
                $request->merge(['price' => $cleanPrice]);
            }

            $request->validate([
                'price' => 'required|numeric|min:0'
            ]);
            $data['price'] = $request->price;
            $data['completed_at'] = now();
        }

        $taxiRequest->update($data);

        return back()->with('success', 'Estado actualizado correctamente');
    }


    public function history()
    {
        $requests = TaxiRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalSpent = $requests->sum('price');

        return view('history', compact('requests', 'totalSpent'));
    }
}
