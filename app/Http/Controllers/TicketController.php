<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return view('tickets.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'problem_description' => 'required'
        ]);

        $referenceNo = 'TICKET-' . strtoupper(Str::random(8));

        $ticket = Ticket::create([
            'reference_no' => $referenceNo,
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'problem_description' => $request->problem_description,
            'status' => 'new'
        ]);

        Mail::to($request->email)->send(new TicketCreated($ticket));
        
        return response()->json([
            'message' => 'Ticket submitted successfully!',
            'reference_no' => $referenceNo
        ], 200);
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
    public function destroy(string $id)
    {
        //
    }
    
        
    public function dashboard(){
        $tickets = Ticket::with('replies')->paginate(10);
        return view('tickets.index', compact('tickets'));
    }


    public function list()
    {
        $tickets = Ticket::with('replies')->latest()->paginate(10);
        return response()->json($tickets);
    }
    
    public function markAsViewed($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->status = "Opened";
            $ticket->save();
            return response()->json(['success' => true, 'message' => 'Ticket marked as viewed']);
        }
        return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
    }
    

    public function showStatusPage()
    {
        return view('tickets.status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string'
        ]);

        $ticket = Ticket::where('reference_no', $request->reference_no)->first();

        if (!$ticket) {
            return redirect()->route('ticket.status')->with('error', 'Ticket not found. Please check your reference number.');
        }

        return view('tickets.status', compact('ticket'));
    }
}
