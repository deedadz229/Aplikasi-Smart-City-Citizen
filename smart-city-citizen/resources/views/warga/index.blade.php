@extends('layouts.app')

@section('title', 'Data Warga')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Modul Warga</p>
            <h1>Data warga terpusat untuk layanan kota.</h1>
            <p class="lead">Tambah, perbarui, cari, dan hapus data warga langsung dari halaman ini tanpa reload.</p>
        </div>

        <div class="button-row">
            <a class="button button-secondary" href="{{ url('/') }}">Kembali ke Dashboard</a>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan data warga">
        <article class="panel stat-card">
            <span>Total Data</span>
            <strong id="total-warga">0</strong>
        </article>
        <article class="panel stat-card">
            <span>Data Ditampilkan</span>
            <strong id="shown-warga">0</strong>
        </article>
        <article class="panel stat-card">
            <span>Mode Form</span>
            <strong id="form-mode">Tambah</strong>
        </article>
        <article class="panel stat-card">
            <span>Status</span>
            <strong id="load-status">Siap</strong>
        </article>
    </section>

    <div id="message" class="message"></div>

    <section class="content-grid">
        <div class="panel panel-pad">
            <p class="eyebrow">Form Warga</p>
            <h2 id="form-title">Tambah Data Warga</h2>

            <form id="warga-form">
                <input type="hidden" id="warga-id">

                <div class="form-grid">
                    <div>
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" maxlength="150" placeholder="Nama lengkap warga" required>
                    </div>

                    <div>
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" maxlength="16" placeholder="16 digit NIK" required>
                    </div>

                    <div>
                        <label for="no_hp">No. HP</label>
                        <input type="text" id="no_hp" name="no_hp" maxlength="20" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="full">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" maxlength="1000" placeholder="Alamat lengkap warga" required></textarea>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary" id="submit-button">Tambah Warga</button>
                    <button type="button" class="btn-secondary" id="reset-button">Batal Edit</button>
                </div>
            </form>
        </div>

        <div class="panel panel-pad">
            <div class="toolbar">
                <div>
                    <p class="eyebrow">Daftar Warga</p>
                    <h2>Database warga</h2>
                </div>

                <div class="search-wrap">
                    <input type="search" id="search-warga" placeholder="Nama, NIK, no. HP, alamat">
                </div>
            </div>

            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="warga-table-body">
                        <tr>
                            <td colspan="5">Memuat data warga...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            const apiUrl = '/api/warga';
            let wargaItems = [];

            function showMessage(type, text) {
                $('#message')
                    .removeClass('success error')
                    .addClass(type)
                    .text(text);
            }

            function clearMessage() {
                $('#message').removeClass('success error').text('');
            }

            function setFormMode(mode) {
                const isEdit = mode === 'edit';

                $('#form-mode').text(isEdit ? 'Edit' : 'Tambah');
                $('#form-title').text(isEdit ? 'Edit Data Warga' : 'Tambah Data Warga');
                $('#submit-button').text(isEdit ? 'Simpan Perubahan' : 'Tambah Warga');
            }

            function resetForm() {
                $('#warga-id').val('');
                $('#warga-form')[0].reset();
                setFormMode('create');
                clearMessage();
            }

            function formatErrors(response) {
                if (response.responseJSON && response.responseJSON.errors) {
                    return Object.values(response.responseJSON.errors).flat().join(' ');
                }

                if (response.responseJSON && response.responseJSON.message) {
                    return response.responseJSON.message;
                }

                return 'Terjadi kesalahan. Silakan coba lagi.';
            }

            function filteredItems() {
                const keyword = $('#search-warga').val().toLowerCase().trim();

                if (! keyword) {
                    return wargaItems;
                }

                return wargaItems.filter(function (item) {
                    return [
                        item.nama,
                        item.nik,
                        item.no_hp,
                        item.alamat,
                    ].join(' ').toLowerCase().includes(keyword);
                });
            }

            function updateSummary(items) {
                $('#total-warga').text(wargaItems.length);
                $('#shown-warga').text(items.length);
                $('#load-status').text('Siap');
            }

            function renderRows() {
                const items = filteredItems();
                const rows = items.map(function (item) {
                    return `
                        <tr>
                            <td data-label="Nama"><strong>${escapeHtml(item.nama)}</strong></td>
                            <td data-label="NIK">${escapeHtml(item.nik)}</td>
                            <td data-label="No. HP">${escapeHtml(item.no_hp)}</td>
                            <td data-label="Alamat">${escapeHtml(item.alamat)}</td>
                            <td data-label="Aksi">
                                <div class="table-actions">
                                    <button type="button" class="btn-secondary edit-button" data-id="${item.id}">Edit</button>
                                    <button type="button" class="btn-danger delete-button" data-id="${item.id}">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#warga-table-body').html(rows.length ? rows.join('') : '<tr><td colspan="5">Belum ada data warga yang cocok.</td></tr>');
                updateSummary(items);
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function loadWarga() {
                $('#load-status').text('Memuat');

                $.getJSON(apiUrl)
                    .done(function (response) {
                        wargaItems = response.data || [];
                        renderRows();
                    })
                    .fail(function (response) {
                        $('#load-status').text('Error');
                        showMessage('error', formatErrors(response));
                    });
            }

            $('#warga-form').on('submit', function (event) {
                event.preventDefault();

                const id = $('#warga-id').val();
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${apiUrl}/${id}` : apiUrl;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nama: $('#nama').val(),
                        nik: $('#nik').val(),
                        alamat: $('#alamat').val(),
                        no_hp: $('#no_hp').val(),
                    },
                })
                    .done(function (response) {
                        resetForm();
                        showMessage('success', response.message);
                        loadWarga();
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            $('#reset-button').on('click', resetForm);

            $('#search-warga').on('input', renderRows);

            $('#warga-table-body').on('click', '.edit-button', function () {
                const id = $(this).data('id');

                $.getJSON(`${apiUrl}/${id}`)
                    .done(function (response) {
                        const warga = response.data;

                        $('#warga-id').val(warga.id);
                        $('#nama').val(warga.nama);
                        $('#nik').val(warga.nik);
                        $('#alamat').val(warga.alamat);
                        $('#no_hp').val(warga.no_hp);
                        setFormMode('edit');
                        clearMessage();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            $('#warga-table-body').on('click', '.delete-button', function () {
                const id = $(this).data('id');
                const button = $(this);

                if (! button.data('confirming')) {
                    $('.delete-button').data('confirming', false).text('Hapus');
                    button.data('confirming', true).text('Yakin Hapus?');
                    return;
                }

                $.ajax({
                    url: `${apiUrl}/${id}`,
                    method: 'DELETE',
                })
                    .done(function (response) {
                        showMessage('success', response.message);
                        loadWarga();
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            loadWarga();
        });
    </script>
@endsection
