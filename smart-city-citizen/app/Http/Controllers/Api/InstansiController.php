<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstansiController extends Controller
{
    public function index(): JsonResponse
    {
        $instansi = Instansi::query()
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data instansi berhasil diambil.',
            'data' => $instansi,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data instansi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $instansi = Instansi::create($validator->validated());

        return response()->json([
            'message' => 'Data instansi berhasil ditambahkan.',
            'data' => $instansi,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $instansi = Instansi::find($id);

        if (! $instansi) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'message' => 'Detail instansi berhasil diambil.',
            'data' => $instansi,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $instansi = Instansi::find($id);

        if (! $instansi) {
            return $this->notFoundResponse();
        }

        $validator = Validator::make($request->all(), $this->rules($instansi->id));

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data instansi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $instansi->update($validator->validated());

        return response()->json([
            'message' => 'Data instansi berhasil diperbarui.',
            'data' => $instansi->fresh(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $instansi = Instansi::find($id);

        if (! $instansi) {
            return $this->notFoundResponse();
        }

        $instansi->delete();

        return response()->json([
            'message' => 'Data instansi berhasil dihapus.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'nama_instansi' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:100'],
            'no_telp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'alamat' => ['required', 'string', 'max:1000'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('instansi', 'email')->ignore($ignoreId),
            ],
            'pimpinan' => ['required', 'string', 'max:150'],
            'status' => ['required', 'string', 'in:Aktif,Non-aktif'],
        ];
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Data instansi tidak ditemukan.',
        ], 404);
    }
}
