<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WargaController extends Controller
{
    public function index(): JsonResponse
    {
        $warga = Warga::query()
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data warga berhasil diambil.',
            'data' => $warga,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data warga gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $warga = Warga::create($validator->validated());

        return response()->json([
            'message' => 'Data warga berhasil ditambahkan.',
            'data' => $warga,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $warga = Warga::find($id);

        if (! $warga) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'message' => 'Detail warga berhasil diambil.',
            'data' => $warga,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $warga = Warga::find($id);

        if (! $warga) {
            return $this->notFoundResponse();
        }

        $validator = Validator::make($request->all(), $this->rules($warga->id));

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data warga gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $warga->update($validator->validated());

        return response()->json([
            'message' => 'Data warga berhasil diperbarui.',
            'data' => $warga->fresh(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $warga = Warga::find($id);

        if (! $warga) {
            return $this->notFoundResponse();
        }

        $warga->delete();

        return response()->json([
            'message' => 'Data warga berhasil dihapus.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'nik' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('warga', 'nik')->ignore($ignoreId),
            ],
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ];
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Data warga tidak ditemukan.',
        ], 404);
    }
}
