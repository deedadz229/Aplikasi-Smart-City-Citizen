@extends('layouts.app')

@section('title', 'Berita Kota')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Modul Berita Kota</p>
            <h1>Berita & informasi terkini dari kota.</h1>
            <p class="lead">Kelola berita resmi kota — tambah, perbarui, dan hapus konten tanpa reload halaman.</p>
        </div>
        <div class="button-row">
            <a class="button button-secondary" href="{{ url('/') }}">Kembali ke Dashboard</a>
            <button class="button button-primary" id="toggle-form-btn" type="button">+ Tulis Berita</button>
        </div>
    </section>

    {{-- STATS --}}
    <section class="stats-grid" aria-label="Ringkasan berita">
        <article class="panel stat-card">
            <span>Total Berita</span>
            <strong id="total-berita">0</strong>
        </article>
        <article class="panel stat-card">
            <span>Ditampilkan</span>
            <strong id="shown-berita">0</strong>
        </article>
        <article class="panel stat-card">
            <span>Mode Form</span>
            <strong id="form-mode">—</strong>
        </article>
        <article class="panel stat-card">
            <span>Status</span>
            <strong id="load-status">Siap</strong>
        </article>
    </section>

    <div id="message" class="message"></div>

    {{-- FORM: tersembunyi dulu, muncul saat klik tombol --}}
    <div id="form-wrapper" class="panel panel-pad" style="display:none; margin-bottom:22px;">
        <p class="eyebrow">Form Berita</p>
        <h2 id="form-title">Tulis Berita Baru</h2>

        <form id="berita-form">
            <input type="hidden" id="berita-id">

            {{-- Baris 1: judul full width --}}
            <div style="margin-bottom:16px;">
                <label for="judul">Judul Berita</label>
                <input type="text" id="judul" name="judul" maxlength="200"
                       placeholder="Tulis judul berita yang menarik..." required>
            </div>

            {{-- Baris 2: 3 kolom --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" maxlength="100"
                           placeholder="Contoh: Kesehatan" required>
                </div>
                <div>
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" maxlength="150"
                           placeholder="Nama penulis / redaksi" required>
                </div>
                <div>
                    <label for="tanggal_terbit">Tanggal Terbit</label>
                    <input type="date" id="tanggal_terbit" name="tanggal_terbit" required>
                </div>
            </div>

            {{-- Baris 3: isi berita full width --}}
            <div style="margin-bottom:16px;">
                <label for="isi">Isi Berita</label>
                <textarea id="isi" name="isi"
                          placeholder="Tulis isi berita di sini..."
                          style="min-height:160px;" required></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary" id="submit-button">Publikasikan Berita</button>
                <button type="button" class="btn-secondary" id="reset-button">Batal</button>
            </div>
        </form>
    </div>

    {{-- FILTER KATEGORI --}}
    <div style="margin-bottom:18px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <div class="search-wrap" style="width:min(100%,320px);">
            <input type="search" id="search-berita" placeholder="Cari judul, penulis...">
        </div>
        <div id="filter-kategori" style="display:flex; gap:8px; flex-wrap:wrap;">
            <button type="button" class="pill-btn active" data-kat="">Semua</button>
        </div>
        <span style="margin-left:auto; color:#64748b; font-size:13px; font-weight:700;" id="filter-label">Menampilkan semua berita</span>
    </div>

    {{-- CARD GRID --}}
    <div id="berita-grid" style="
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 18px;
        margin-bottom: 40px;
    ">
        <div class="panel panel-pad" style="color:#64748b;">Memuat berita...</div>
    </div>
@endsection

@section('scripts')
<style>
    .pill-btn {
        border: 2px solid #cbd5e1;
        border-radius: 999px;
        background: #ffffff;
        color: #475569;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.15s ease;
        min-height: unset;
    }
    .pill-btn:hover {
        border-color: #0f766e;
        color: #0f766e;
        transform: none;
    }
    .pill-btn.active {
        background: #0f766e;
        border-color: #0f766e;
        color: #ffffff;
    }

    .berita-card {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 22px;
    }
    .berita-card .card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .berita-card .card-judul {
        margin: 0;
        font-size: 17px;
        line-height: 1.35;
        color: #0f172a;
    }
    .berita-card .card-isi {
        margin: 0;
        color: #526174;
        font-size: 14px;
        line-height: 1.65;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .berita-card .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    .berita-card .card-penulis {
        font-size: 13px;
        color: #64748b;
        font-weight: 700;
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const apiUrl = '/api/berita-kota';
    let beritaItems = [];
    let activeKat = '';

    /* ── helpers ── */
    function escapeHtml(v) { return $('<div>').text(v ?? '').html(); }

    function showMessage(type, text) {
        $('#message').removeClass('success error').addClass(type).text(text);
    }
    function clearMessage() { $('#message').removeClass('success error').text(''); }

    function formatErrors(res) {
        if (res.responseJSON?.errors) return Object.values(res.responseJSON.errors).flat().join(' ');
        if (res.responseJSON?.message) return res.responseJSON.message;
        return 'Terjadi kesalahan. Silakan coba lagi.';
    }

    /* ── form toggle ── */
    $('#toggle-form-btn').on('click', function () {
        const visible = $('#form-wrapper').is(':visible');
        if (visible) {
            $('#form-wrapper').slideUp(180);
            $(this).text('+ Tulis Berita');
            resetForm();
        } else {
            $('#form-wrapper').slideDown(220);
            $(this).text('✕ Tutup Form');
            $('#form-mode').text('Tambah');
        }
    });

    function openForm() {
        $('#form-wrapper').slideDown(220);
        $('#toggle-form-btn').text('✕ Tutup Form');
    }

    /* ── reset ── */
    function resetForm() {
        $('#berita-id').val('');
        $('#berita-form')[0].reset();
        $('#form-title').text('Tulis Berita Baru');
        $('#submit-button').text('Publikasikan Berita');
        $('#form-mode').text('Tambah');
        clearMessage();
    }
    $('#reset-button').on('click', function () {
        resetForm();
        $('#form-wrapper').slideUp(180);
        $('#toggle-form-btn').text('+ Tulis Berita');
    });

    /* ── filter pills ── */
    function rebuildPills() {
        const cats = [...new Set(beritaItems.map(b => b.kategori))].sort();
        const $wrap = $('#filter-kategori');
        $wrap.html('<button type="button" class="pill-btn' + (activeKat === '' ? ' active' : '') + '" data-kat="">Semua</button>');
        cats.forEach(function (c) {
            $wrap.append(
                `<button type="button" class="pill-btn${activeKat === c ? ' active' : ''}" data-kat="${escapeHtml(c)}">${escapeHtml(c)}</button>`
            );
        });
    }

    $(document).on('click', '.pill-btn', function () {
        activeKat = $(this).data('kat');
        $('.pill-btn').removeClass('active');
        $(this).addClass('active');
        renderCards();
    });

    /* ── filter logic ── */
    function filteredItems() {
        const kw = $('#search-berita').val().toLowerCase().trim();
        return beritaItems.filter(function (b) {
            const matchKat = activeKat === '' || b.kategori === activeKat;
            const matchKw  = !kw || [b.judul, b.penulis, b.isi, b.kategori].join(' ').toLowerCase().includes(kw);
            return matchKat && matchKw;
        });
    }

    /* ── render cards ── */
    function renderCards() {
        const items = filteredItems();
        $('#total-berita').text(beritaItems.length);
        $('#shown-berita').text(items.length);
        $('#filter-label').text(
            activeKat ? `Kategori: ${activeKat} (${items.length} berita)` : `Menampilkan semua berita (${items.length})`
        );

        if (!items.length) {
            $('#berita-grid').html('<div class="panel panel-pad" style="color:#64748b; grid-column:1/-1;">Belum ada berita yang cocok.</div>');
            return;
        }

        const cards = items.map(function (b) {
            return `
            <article class="panel berita-card">
                <div class="card-meta">
                    <span class="badge">${escapeHtml(b.kategori)}</span>
                    <span style="font-size:12px; color:#94a3b8;">${escapeHtml(b.tanggal_terbit)}</span>
                </div>
                <h3 class="card-judul">${escapeHtml(b.judul)}</h3>
                <p class="card-isi">${escapeHtml(b.isi)}</p>
                <div class="card-footer">
                    <span class="card-penulis">✍ ${escapeHtml(b.penulis)}</span>
                    <div class="table-actions">
                        <button type="button" class="btn-secondary edit-button" data-id="${b.id}" style="min-height:32px; padding:6px 12px; font-size:13px;">Edit</button>
                        <button type="button" class="btn-danger delete-button" data-id="${b.id}" style="min-height:32px; padding:6px 12px; font-size:13px;">Hapus</button>
                    </div>
                </div>
            </article>`;
        });

        $('#berita-grid').html(cards.join(''));
    }

    /* ── load ── */
    function loadBerita() {
        $('#load-status').text('Memuat');
        $.getJSON(apiUrl)
            .done(function (res) {
                beritaItems = res.data || [];
                rebuildPills();
                renderCards();
                $('#load-status').text('Siap');
            })
            .fail(function (res) {
                $('#load-status').text('Error');
                showMessage('error', formatErrors(res));
            });
    }

    /* ── submit form ── */
    $('#berita-form').on('submit', function (e) {
        e.preventDefault();
        const id     = $('#berita-id').val();
        const method = id ? 'PUT' : 'POST';
        const url    = id ? `${apiUrl}/${id}` : apiUrl;

        $.ajax({
            url, method,
            data: {
                judul:          $('#judul').val(),
                isi:            $('#isi').val(),
                kategori:       $('#kategori').val(),
                penulis:        $('#penulis').val(),
                tanggal_terbit: $('#tanggal_terbit').val(),
            },
        })
        .done(function (res) {
            resetForm();
            $('#form-wrapper').slideUp(180);
            $('#toggle-form-btn').text('+ Tulis Berita');
            showMessage('success', res.message);
            loadBerita();
        })
        .fail(function (res) {
            showMessage('error', formatErrors(res));
        });
    });

    /* ── search ── */
    $('#search-berita').on('input', renderCards);

    /* ── edit ── */
    $(document).on('click', '.edit-button', function () {
        const id = $(this).data('id');
        $.getJSON(`${apiUrl}/${id}`)
            .done(function (res) {
                const b = res.data;
                $('#berita-id').val(b.id);
                $('#judul').val(b.judul);
                $('#isi').val(b.isi);
                $('#kategori').val(b.kategori);
                $('#penulis').val(b.penulis);
                $('#tanggal_terbit').val(b.tanggal_terbit);
                $('#form-title').text('Edit Berita');
                $('#submit-button').text('Simpan Perubahan');
                $('#form-mode').text('Edit');
                openForm();
                clearMessage();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .fail(function (res) { showMessage('error', formatErrors(res)); });
    });

    /* ── delete ── */
    $(document).on('click', '.delete-button', function () {
        const id     = $(this).data('id');
        const button = $(this);
        if (!button.data('confirming')) {
            $('.delete-button').data('confirming', false).text('Hapus');
            button.data('confirming', true).text('Yakin?');
            return;
        }
        $.ajax({ url: `${apiUrl}/${id}`, method: 'DELETE' })
            .done(function (res) {
                showMessage('success', res.message);
                loadBerita();
            })
            .fail(function (res) { showMessage('error', formatErrors(res)); });
    });

    loadBerita();
});
</script>
@endsection