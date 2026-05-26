@extends('layouts.app')

@section('title', 'Dashboard Smart City Citizen')

@section('content')
    @php
        $heroSlides = [
            file_exists(public_path('images/hero-1.jpg'))
                ? asset('images/hero-1.jpg')
                : 'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1600&q=80',
            file_exists(public_path('images/hero-2.jpg'))
                ? asset('images/hero-2.jpg')
                : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80',
            file_exists(public_path('images/hero-3.jpg'))
                ? asset('images/hero-3.jpg')
                : 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1600&q=80',
        ];
    @endphp

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
        <div class="panel hero-band full" data-hero-slider>
            <div class="hero-slide-track" aria-hidden="true">
                @foreach ($heroSlides as $index => $slide)
                    <div
                        class="hero-slide {{ $index === 0 ? 'is-active' : '' }}"
                        data-hero-slide="{{ $index }}"
                    >
                        <img src="{{ $slide }}" alt="">
                    </div>
                @endforeach
            </div>

            <div class="hero-content">
                <h2>Smart City Citizen siap untuk layanan data warga & petugas.</h2>
                <p>Kelola data kependudukan dan petugas lapangan kota Anda secara langsung, dinamis, dan terintegrasi tanpa reload halaman.</p>
                <div class="button-row">
                    <a class="button button-primary" href="{{ url('/warga') }}">Kelola Warga</a>
                    <a class="button button-on-media" href="{{ url('/petugas') }}">Kelola Petugas</a>
                </div>
            </div>

            <div class="hero-slider-controls" aria-label="Kontrol gambar utama">
                <button type="button" class="hero-slider-button" data-hero-prev aria-label="Gambar sebelumnya">&lt;</button>
                <div class="hero-slider-dots">
                    @foreach ($heroSlides as $index => $slide)
                        <button
                            type="button"
                            class="hero-slider-dot {{ $index === 0 ? 'is-active' : '' }}"
                            data-hero-dot="{{ $index }}"
                            aria-label="Tampilkan gambar {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
                <button type="button" class="hero-slider-button" data-hero-next aria-label="Gambar berikutnya">&gt;</button>
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.querySelector('[data-hero-slider]');

            if (! slider) {
                return;
            }

            const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
            const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));
            const previousButton = slider.querySelector('[data-hero-prev]');
            const nextButton = slider.querySelector('[data-hero-next]');
            let activeIndex = 0;
            let timer = null;

            function showSlide(index) {
                activeIndex = (index + slides.length) % slides.length;

                slides.forEach(function (slide, slideIndex) {
                    slide.classList.toggle('is-active', slideIndex === activeIndex);
                });

                dots.forEach(function (dot, dotIndex) {
                    dot.classList.toggle('is-active', dotIndex === activeIndex);
                });
            }

            function startAutoPlay() {
                window.clearInterval(timer);
                timer = window.setInterval(function () {
                    showSlide(activeIndex + 1);
                }, 5000);
            }

            previousButton.addEventListener('click', function () {
                showSlide(activeIndex - 1);
                startAutoPlay();
            });

            nextButton.addEventListener('click', function () {
                showSlide(activeIndex + 1);
                startAutoPlay();
            });

            dots.forEach(function (dot, index) {
                dot.addEventListener('click', function () {
                    showSlide(index);
                    startAutoPlay();
                });
            });

            startAutoPlay();
        });
    </script>
@endsection
