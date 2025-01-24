<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Notifications\TravelOrderStatusChanged;
use Illuminate\Support\Facades\Notification;

class TravelOrderController extends Controller
{
    public function __construct()
    {
        // Garantir que o usuário esteja autenticado
        $this->middleware('auth:api');
    }

    // Criar um pedido de viagem
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'requester_name' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'status' => 'required|in:requested,approved,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $travelOrder = TravelOrder::create([
            'requester_name' => $request->requester_name,
            'destination' => $request->destination,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'status' => $request->status,
            'user_id' => auth()->user()->id,  // Relacionando o pedido ao usuário autenticado
        ]);

        return response()->json(['travel_order' => $travelOrder], 201);
    }

    // Atualizar status de um pedido de viagem
    public function update($id, Request $request)
    {
        $travelOrder = TravelOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,canceled',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Verificar se o pedido pode ser cancelado (somente se for na mesma data de criação)
        if ($request->status === 'canceled' && !Carbon::parse($travelOrder->created_at)->isToday()) {
            return response()->json(['message' => 'You can only cancel a travel order on the same day it was created.'], 400);
        }

        // Verificar se o usuário está tentando alterar o próprio pedido
        if ($travelOrder->user_id === auth()->user()->id) {
            return response()->json(['message' => 'You can only update your own travel orders.'], 403);
        }

        $travelOrder->status = $request->status;
        $travelOrder->save();

        // Enviar notificação para o usuário que solicitou o pedido
        $user = $travelOrder->user;
        $user->notify(new TravelOrderStatusChanged($request->status, $travelOrder));

        return response()->json(['travel_order' => $travelOrder]);
    }

    // Consultar um pedido de viagem
    public function show($id)
    {
        $travelOrder = TravelOrder::findOrFail($id);

        if ($travelOrder->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'You can only view your own travel orders.'], 403);
        }

        return response()->json(['travel_order' => $travelOrder]);
    }

    // Listar todos os pedidos de viagem
    public function index(Request $request)
    {
        $query = TravelOrder::where('user_id', auth()->user()->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('departure_date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%'.$request->destination.'%');
        }

        $travelOrders = $query->get();

        return response()->json(['travel_orders' => $travelOrders]);
    }

}
