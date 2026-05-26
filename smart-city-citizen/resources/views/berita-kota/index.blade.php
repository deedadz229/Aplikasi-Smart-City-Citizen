@extends('layouts.app')

@section('title', 'Berita Kota')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Modul Berita Kota</p>
            <h1>Berita & informasi terkini dari kota.</h1>
            <p class="lead">Kelola berita resmi kota, mulai dari publikasi baru sampai pembaruan konten, langsung dari halaman ini.</p>
        </div>
        <div class="button-row">
            <a class="button button-secondary" href="{{ url('/') }}">Kembali ke Dashboard</a>
            <button class="button button-primary" id="toggle-form-btn" type="button">Tulis Berita</button>
        </div>
    </section>

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
            <strong id="form-mode">-</strong>
        </article>
        <article class="panel stat-card">
            <span>Status</span>
            <strong id="load-status">Siap</strong>
        </article>
    </section>

    <div id="message" class="message"></div>

    <div id="form-wrapper" class="panel panel-pad mb-22 hidden">
        <p class="eyebrow">Form Berita</p>
        <h2 id="form-title">Tulis Berita Baru</h2>

        <form id="berita-form">
            <input type="hidden" id="berita-id">

            <div class="mb-16">
                <label for="judul">Judul Berita</label>
                <input type="text" id="judul" name="judul" maxlength="200" placeholder="Tulis judul berita" required>
            </div>

            <div class="field-grid-3">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" maxlength="100" placeholder="Contoh: Kesehatan" required>
                </div>
                <div>
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" maxlength="150" placeholder="Nama penulis atau redaksi" required>
                </div>
                <div>
                    <label for="tanggal_terbit">Tanggal Terbit</label>
                    <input type="date" id="tanggal_terbit" name="tanggal_terbit" required>
                </div>
            </div>

            <div class="mb-16">
                <label for="isi">Isi Berita</label>
                <textarea id="isi" name="isi" class="textarea-tall" placeholder="Tulis isi berita di sini" required></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary" id="submit-button">Publikasikan Berita</button>
                <button type="button" class="btn-secondary" id="reset-button">Batal</button>
            </div>
        </form>
    </div>

    <div class="filter-bar">
        <div class="search-wrap search-compact">
            <input type="search" id="search-berita" placeholder="Cari judul, penulis, kategori">
        </div>
        <div id="filter-kategori" class="pill-group">
            <button type="button" class="pill-btn active" data-kat="">Semua</button>
        </div>
        <span class="filter-label" id="filter-label">Menampilkan semua berita</span>
    </div>

    <div id="berita-grid" class="news-grid">
        <div class="panel panel-pad empty-state">Memuat berita...</div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const apiUrl = '/api/berita-kota';
    let beritaItems = [];
    let activeKat = '';

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function showMessage(type, text) {
        $('#message').removeClass('success error').addClass(type).text(text);
    }

    function clearMessage() {
        $('#message').removeClass('success error').text('');
    }

    function formatErrors(response) {
        if (response.responseJSON?.errors) {
            return Object.values(response.responseJSON.errors).flat().join(' ');
        }

        if (response.responseJSON?.message) {
            return response.responseJSON.message;
        }

        return 'Terjadi kesalahan. Silakan coba lagi.';
    }

    $('#toggle-form-btn').on('click', function () {
        const visible = $('#form-wrapper').is(':visible');

        if (visible) {
            $('#form-wrapper').slideUp(180);
            $(this).text('Tulis Berita');
            resetForm();
            return;
        }

        $('#form-wrapper').slideDown(220);
        $(this).text('Tutup Form');
        $('#form-mode').text('Tambah');
    });

    function openForm() {
        $('#form-wrapper').slideDown(220);
        $('#toggle-form-btn').text('Tutup Form');
    }

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
        $('#toggle-form-btn').text('Tulis Berita');
    });

    function rebuildPills() {
        const categories = [...new Set(beritaItems.map(function (item) {
            return item.kategori;
        }).filter(Boolean))].sort();

        const $wrap = $('#filter-kategori');
        $wrap.html('<button type="button" class="pill-btn' + (activeKat === '' ? ' active' : '') + '" data-kat="">Semua</button>');

        categories.forEach(function (category) {
            $wrap.append(
                `<button type="button" class="pill-btn${activeKat === category ? ' active' : ''}" data-kat="${escapeHtml(category)}">${escapeHtml(category)}</button>`
            );
        });
    }

    $(document).on('click', '.pill-btn', function () {
        activeKat = $(this).data('kat') || '';
        $('.pill-btn').removeClass('active');
        $(this).addClass('active');
        renderCards();
    });

    function filteredItems() {
        const keyword = $('#search-berita').val().toLowerCase().trim();

        return beritaItems.filter(function (item) {
            const matchCategory = activeKat === '' || item.kategori === activeKat;
            const haystack = [
                item.judul,
                item.penulis,
                item.isi,
                item.kategori,
            ].join(' ').toLowerCase();

            return matchCategory && (! keyword || haystack.includes(keyword));
        });
    }

    function renderCards() {
        const items = filteredItems();
        $('#total-berita').text(beritaItems.length);
        $('#shown-berita').text(items.length);
        $('#filter-label').text(
            activeKat ? `Kategori: ${activeKat} (${items.length} berita)` : `Menampilkan semua berita (${items.length})`
        );

        if (! items.length) {
            $('#berita-grid').html('<div class="panel panel-pad empty-state empty-wide">Belum ada berita yang cocok.</div>');
            return;
        }

        const cards = items.map(function (item) {
            return `
                <article class="panel berita-card">
                    <div class="card-meta">
                        <span class="badge">${escapeHtml(item.kategori)}</span>
                        <span class="contact-subline">${escapeHtml(item.tanggal_terbit)}</span>
                    </div>
                    <h3 class="card-judul">${escapeHtml(item.judul)}</h3>
                    <p class="card-isi">${escapeHtml(item.isi)}</p>
                    <div class="card-footer">
                        <span class="card-penulis">Ditulis oleh ${escapeHtml(item.penulis)}</span>
                        <div class="table-actions">
                            <button type="button" class="btn-secondary edit-button compact-action" data-id="${item.id}">Edit</button>
                            <button type="button" class="btn-danger delete-button compact-action" data-id="${item.id}">Hapus</button>
                        </div>
                    </div>
                </article>
            `;
        });

        $('#berita-grid').html(cards.join(''));
    }

    function loadBerita() {
        $('#load-status').text('Memuat');

        $.getJSON(apiUrl)
            .done(function (response) {
                beritaItems = response.data || [];
                rebuildPills();
                renderCards();
                $('#load-status').text('Siap');
            })
            .fail(function (response) {
                $('#load-status').text('Error');
                showMessage('error', formatErrors(response));
            });
    }

    $('#berita-form').on('submit', function (event) {
        event.preventDefault();

        const id = $('#berita-id').val();
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${apiUrl}/${id}` : apiUrl;

        $.ajax({
            url: url,
            method: method,
            data: {
                judul: $('#judul').val(),
                isi: $('#isi').val(),
                kategori: $('#kategori').val(),
                penulis: $('#penulis').val(),
                tanggal_terbit: $('#tanggal_terbit').val(),
            },
        })
            .done(function (response) {
                resetForm();
                $('#form-wrapper').slideUp(180);
                $('#toggle-form-btn').text('Tulis Berita');
                showMessage('success', response.message);
                loadBerita();
            })
            .fail(function (response) {
                showMessage('error', formatErrors(response));
            });
    });

    $('#search-berita').on('input', renderCards);

    $(document).on('click', '.edit-button', function () {
        const id = $(this).data('id');

        $.getJSON(`${apiUrl}/${id}`)
            .done(function (response) {
                const berita = response.data;

                $('#berita-id').val(berita.id);
                $('#judul').val(berita.judul);
                $('#isi').val(berita.isi);
                $('#kategori').val(berita.kategori);
                $('#penulis').val(berita.penulis);
                $('#tanggal_terbit').val(berita.tanggal_terbit);
                $('#form-title').text('Edit Berita');
                $('#submit-button').text('Simpan Perubahan');
                $('#form-mode').text('Edit');
                openForm();
                clearMessage();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .fail(function (response) {
                showMessage('error', formatErrors(response));
            });
    });

    $(document).on('click', '.delete-button', function () {
        const id = $(this).data('id');
        const button = $(this);

        if (! button.data('confirming')) {
            $('.delete-button').data('confirming', false).text('Hapus');
            button.data('confirming', true).text('Yakin?');
            return;
        }

        $.ajax({
            url: `${apiUrl}/${id}`,
            method: 'DELETE',
        })
            .done(function (response) {
                showMessage('success', response.message);
                loadBerita();
            })
            .fail(function (response) {
                showMessage('error', formatErrors(response));
            });
    });

    loadBerita();
});
</script>
@endsection
