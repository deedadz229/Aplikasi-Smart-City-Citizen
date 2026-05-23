<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BeritaKota;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeritaKotaController extends Controller
{
    public function index(): JsonResponse
    {
        $berita = BeritaKota::query()
            ->latest('tanggal_terbit')
            ->get();

        return response()->json([
            'message' => 'Data berita kota berhasil diambil.',
            'data' => $berita,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data berita gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $berita = BeritaKota::create($validator->validated());

        return response()->json([
            'message' => 'Berita berhasil ditambahkan.',
            'data' => $berita,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $berita = BeritaKota::find($id);

        if (! $berita) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'message' => 'Detail berita berhasil diambil.',
            'data' => $berita,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $berita = BeritaKota::find($id);

        if (! $berita) {
            return $this->notFoundResponse();
        }

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi data berita gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $berita->update($validator->validated());

        return response()->json([
            'message' => 'Berita berhasil diperbarui.',
            'data' => $berita->fresh(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $berita = BeritaKota::find($id);

        if (! $berita) {
            return $this->notFoundResponse();
        }

        $berita->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'judul'          => ['required', 'string', 'max:200'],
            'isi'            => ['required', 'string'],
            'kategori'       => ['required', 'string', 'max:100'],
            'penulis'        => ['required', 'string', 'max:150'],
            'tanggal_terbit' => ['required', 'date'],
        ];
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Data berita tidak ditemukan.',
        ], 404);
    }
}