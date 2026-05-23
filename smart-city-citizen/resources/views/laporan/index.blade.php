@extends('layouts.app')

@section('title', 'Laporan Data Warga')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Modul Laporan</p>
            <h1>Laporan Data Warga</h1>
            <p class="lead">
                Halaman ini digunakan untuk menampilkan rekap data warga yang sudah tersimpan
                pada sistem Smart City Citizen.
            </p>
        </div>

        <div class="button-row">
            <a class="button button-secondary" href="{{ url('/') }}">Kembali</a>
            <button type="button" class="button button-primary" onclick="window.print()">Cetak Laporan</button>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan laporan">
        <article class="panel stat-card">
            <span>Total Data Warga</span>
            <strong>{{ $totalWarga }}</strong>
        </article>

        <article class="panel stat-card">
            <span>Tanggal Awal</span>
            <strong style="font-size: 18px;">{{ $tanggalAwal ?? '-' }}</strong>
        </article>

        <article class="panel stat-card">
            <span>Tanggal Akhir</span>
            <strong style="font-size: 18px;">{{ $tanggalAkhir ?? '-' }}</strong>
        </article>

        <article class="panel stat-card">
            <span>Status Laporan</span>
            <strong style="font-size: 18px;">Siap Cetak</strong>
        </article>
    </section>

    <section class="panel panel-pad" style="margin-bottom: 22px;">
        <p class="eyebrow">Filter Laporan</p>
        <h2>Atur Data yang Ditampilkan</h2>

        <form method="GET" action="{{ url('/laporan') }}">
            <div class="form-grid">
                <div>
                    <label for="keyword">Kata Kunci</label>
                    <input
                        type="text"
                        id="keyword"
                        name="keyword"
                        value="{{ $keyword }}"
                        placeholder="Cari nama, NIK, alamat, atau no. HP"
                    >
                </div>

                <div>
                    <label for="tanggal_awal">Tanggal Awal</label>
                    <input
                        type="date"
                        id="tanggal_awal"
                        name="tanggal_awal"
                        value="{{ $tanggalAwal }}"
                    >
                </div>

                <div>
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input
                        type="date"
                        id="tanggal_akhir"
                        name="tanggal_akhir"
                        value="{{ $tanggalAkhir }}"
                    >
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">Tampilkan Laporan</button>
                <a href="{{ url('/laporan') }}" class="button button-secondary">Reset Filter</a>
            </div>
        </form>
    </section>

    <section class="panel panel-pad">
        <div class="toolbar">
            <div>
                <p class="eyebrow">Hasil Laporan</p>
                <h2>Daftar Data Warga</h2>
            </div>

            <span class="badge">{{ $totalWarga }} data</span>
        </div>

        <div class="table-shell">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Warga</th>
                        <th>NIK</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warga as $index => $item)
                        <tr>
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="Nama Warga">
                                <strong>{{ $item->nama }}</strong>
                            </td>
                            <td data-label="NIK">{{ $item->nik }}</td>
                            <td data-label="No. HP">{{ $item->no_hp }}</td>
                            <td data-label="Alamat">{{ $item->alamat }}</td>
                            <td data-label="Tanggal Input">
                                {{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Belum ada data warga yang sesuai dengan filter laporan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <style>
        @media print {
            .topbar,
            .button-row,
            form,
            .actions {
                display: none !important;
            }

            body {
                background: #ffffff !important;
            }

            .page {
                max-width: 100%;
                padding: 0;
            }

            .panel {
                box-shadow: none;
                border: 1px solid #dddddd;
            }

            table {
                min-width: 100%;
                font-size: 12px;
            }
        }
    </style>
@endsection