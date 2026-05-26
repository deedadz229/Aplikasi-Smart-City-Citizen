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
            <a class="button button-secondary" href="{{ url('/instansi') }}">Buka Data Instansi</a>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan sistem">
        <article class="panel stat-card">
            <span>Total Warga</span>
            <strong>{{ $totalWarga }}</strong>
        </article>
        <article class="panel stat-card">
            <span>Total Instansi</span>
            <strong>{{ $totalInstansi }}</strong>
        </article>
        <article class="panel stat-card">
            <span>Total Petugas</span>
            <strong>{{ $totalPetugas }}</strong>
        </article>
        <article class="panel stat-card">
            <span>Status DB & API</span>
            <strong>OK / REST</strong>
        </article>
    </section>

    <section class="content-grid">
        <div class="panel hero-band full">
            <div>
                <h2>Smart City Citizen siap untuk layanan data warga & petugas.</h2>
                <p>Kelola data kependudukan dan petugas lapangan kota Anda secara langsung, dinamis, dan terintegrasi tanpa reload halaman.</p>
                <div class="button-row">
                    <a class="button button-primary" href="{{ url('/warga') }}">Kelola Warga</a>
                    <a class="button button-secondary" href="{{ url('/petugas') }}" style="background: rgba(255, 255, 255, 0.25); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.45)">Kelola Petugas</a>
                </div>
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

        <div class="panel panel-pad">
            <div class="toolbar">
                <div>
                    <p class="eyebrow">Data Terbaru</p>
                    <h2>Petugas terakhir ditambahkan</h2>
                </div>
                <span class="badge">{{ $totalPetugas }} data</span>
            </div>

            <div class="list">
                @forelse ($petugasTerbaru as $petugas)
                    <div class="list-item">
                        <div>
                            <strong>{{ $petugas->nama }}</strong>
                            <p class="muted">{{ $petugas->jabatan }}</p>
                        </div>
                        <span class="badge">{{ $petugas->nip }}</span>
                    </div>
                @empty
                    <div class="list-item">
                        <div>
                            <strong>Belum ada data petugas</strong>
                            <p class="muted">Mulai isi data dari Modul Petugas.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
