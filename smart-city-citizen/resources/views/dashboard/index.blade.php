@extends('layouts.app')

@section('title', 'Dashboard Smart City Citizen')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Dashboard Pelayanan Warga</p>
            <h1>Kelola data warga dengan cepat dan rapi.</h1>
            <p class="lead">Pantau ringkasan data kependudukan, akses modul warga, dan siapkan alur layanan digital dari satu halaman awal.</p>
        </div>

        <div class="button-row">
            <a class="button button-primary" href="{{ url('/warga') }}">Buka Data Warga</a>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan sistem">
        <article class="panel stat-card">
            <span>Total Warga</span>
            <strong>{{ $totalWarga }}</strong>
        </article>
        <article class="panel stat-card">
            <span>Modul Aktif</span>
            <strong>1</strong>
        </article>
        <article class="panel stat-card">
            <span>Koneksi DB</span>
            <strong>OK</strong>
        </article>
        <article class="panel stat-card">
            <span>Status API</span>
            <strong>REST</strong>
        </article>
    </section>

    <section class="content-grid">
        <div class="panel hero-band">
            <div>
                <h2>Smart City Citizen siap untuk layanan data warga.</h2>
                <p>Gunakan modul warga untuk menambah, mengubah, mencari, dan menghapus data tanpa reload halaman.</p>
                <a class="button button-primary" href="{{ url('/warga') }}">Kelola Warga</a>
            </div>
        </div>

        <div class="panel panel-pad">
            <div class="toolbar">
                <div>
                    <p class="eyebrow">Data Terbaru</p>
                    <h2>Warga terakhir ditambahkan</h2>
                </div>
                <span class="badge">{{ $totalWarga }} data</span>
            </div>

            <div class="list">
                @forelse ($wargaTerbaru as $warga)
                    <div class="list-item">
                        <div>
                            <strong>{{ $warga->nama }}</strong>
                            <p class="muted">{{ $warga->alamat }}</p>
                        </div>
                        <span class="badge">{{ $warga->nik }}</span>
                    </div>
                @empty
                    <div class="list-item">
                        <div>
                            <strong>Belum ada data warga</strong>
                            <p class="muted">Mulai isi data dari Modul Warga.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
