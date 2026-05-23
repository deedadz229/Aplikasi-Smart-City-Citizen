<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index(): JsonResponse
    {
        $petugas = Petugas::query()
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data petugas berhasil diambil.',
            'data' => $petugas,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data petugas gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $petugas = Petugas::create($validator->validated());

        return response()->json([
            'message' => 'Data petugas berhasil ditambahkan.',
            'data' => $petugas,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'message' => 'Detail petugas berhasil diambil.',
            'data' => $petugas,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return $this->notFoundResponse();
        }

        $validator = Validator::make($request->all(), $this->rules($petugas->id));

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data petugas gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $petugas->update($validator->validated());

        return response()->json([
            'message' => 'Data petugas berhasil diperbarui.',
            'data' => $petugas->fresh(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return $this->notFoundResponse();
        }

        $petugas->delete();

        return response()->json([
            'message' => 'Data petugas berhasil dihapus.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('petugas', 'nip')->ignore($ignoreId),
            ],
            'jabatan' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ];
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Data petugas tidak ditemukan.',
        ], 404);
    }
}
