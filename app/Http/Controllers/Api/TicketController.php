<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Listar chamados (com filtros)
     */
    public function index(Request $request)
    {
        $query = Ticket::with('category');

        // Filtro por status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filtro por categoria
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return $query->get();
    }

    /**
     * Criar chamado
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'created_by' => 'required|string'
        ]);

        // Status padrão
        $data['status'] = 'aberto';

        return Ticket::create($data);
    }

    /**
     * Mostrar chamado
     */
    public function show(string $id)
    {
        return Ticket::with('category')->findOrFail($id);
    }

    /**
     * Atualizar chamado
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:aberto,em_progresso,resolvido',
            'category_id' => 'sometimes|exists:categories,id'
        ]);

        $ticket->update($data);

        return $ticket;
    }

    /**
     * Deletar chamado
     */
    public function destroy(string $id)
    {
        Ticket::findOrFail($id)->delete();

        return response()->noContent();
    }
}