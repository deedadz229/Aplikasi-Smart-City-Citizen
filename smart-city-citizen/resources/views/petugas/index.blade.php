@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Modul Petugas</p>
            <h1>Kelola data petugas lapangan kota.</h1>
            <p class="lead">Tambah, perbarui, cari, dan hapus data petugas langsung dari halaman ini tanpa reload.</p>
        </div>

        <div class="button-row">
            <a class="button button-secondary" href="{{ url('/') }}">Kembali ke Dashboard</a>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan data petugas">
        <article class="panel stat-card">
            <span>Total Petugas</span>
            <strong id="total-petugas">0</strong>
        </article>
        <article class="panel stat-card">
            <span>Data Ditampilkan</span>
            <strong id="shown-petugas">0</strong>
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
            <p class="eyebrow">Form Petugas</p>
            <h2 id="form-title">Tambah Data Petugas</h2>

            <form id="petugas-form">
                <input type="hidden" id="petugas-id">

                <div class="form-grid">
                    <div>
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" maxlength="150" placeholder="Nama lengkap petugas" required>
                    </div>

                    <div>
                        <label for="nip">NIP</label>
                        <input type="text" id="nip" name="nip" maxlength="30" placeholder="Nomor Induk Petugas" required>
                    </div>

                    <div>
                        <label for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan" maxlength="100" placeholder="Contoh: Kepala Keamanan" required>
                    </div>

                    <div>
                        <label for="no_hp">No. HP</label>
                        <input type="text" id="no_hp" name="no_hp" maxlength="20" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary" id="submit-button">Tambah Petugas</button>
                    <button type="button" class="btn-secondary" id="reset-button">Batal Edit</button>
                </div>
            </form>
        </div>

        <div class="panel panel-pad">
            <div class="toolbar">
                <div>
                    <p class="eyebrow">Daftar Petugas</p>
                    <h2>Database petugas</h2>
                </div>

                <div class="search-wrap">
                    <input type="search" id="search-petugas" placeholder="Nama, NIP, jabatan, no. HP">
                </div>
            </div>

            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="petugas-table-body">
                        <tr>
                            <td colspan="5">Memuat data petugas...</td>
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
            const apiUrl = '/api/petugas';
            let petugasItems = [];

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
                $('#form-title').text(isEdit ? 'Edit Data Petugas' : 'Tambah Data Petugas');
                $('#submit-button').text(isEdit ? 'Simpan Perubahan' : 'Tambah Petugas');
            }

            function resetForm() {
                $('#petugas-id').val('');
                $('#petugas-form')[0].reset();
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
                const keyword = $('#search-petugas').val().toLowerCase().trim();

                if (! keyword) {
                    return petugasItems;
                }

                return petugasItems.filter(function (item) {
                    return [
                        item.nama,
                        item.nip,
                        item.jabatan,
                        item.no_hp,
                    ].join(' ').toLowerCase().includes(keyword);
                });
            }

            function updateSummary(items) {
                $('#total-petugas').text(petugasItems.length);
                $('#shown-petugas').text(items.length);
                $('#load-status').text('Siap');
            }

            function renderRows() {
                const items = filteredItems();
                const rows = items.map(function (item) {
                    return `
                        <tr>
                            <td data-label="Nama"><strong>${escapeHtml(item.nama)}</strong></td>
                            <td data-label="NIP">${escapeHtml(item.nip)}</td>
                            <td data-label="Jabatan"><span class="badge" style="background:#eef3f8; color:#0f766e;">${escapeHtml(item.jabatan)}</span></td>
                            <td data-label="No. HP">${escapeHtml(item.no_hp)}</td>
                            <td data-label="Aksi">
                                <div class="table-actions">
                                    <button type="button" class="btn-secondary edit-button" data-id="${item.id}">Edit</button>
                                    <button type="button" class="btn-danger delete-button" data-id="${item.id}">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#petugas-table-body').html(rows.length ? rows.join('') : '<tr><td colspan="5">Belum ada data petugas yang cocok.</td></tr>');
                updateSummary(items);
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function loadPetugas() {
                $('#load-status').text('Memuat');

                $.getJSON(apiUrl)
                    .done(function (response) {
                        petugasItems = response.data || [];
                        renderRows();
                    })
                    .fail(function (response) {
                        $('#load-status').text('Error');
                        showMessage('error', formatErrors(response));
                    });
            }

            $('#petugas-form').on('submit', function (event) {
                event.preventDefault();

                const id = $('#petugas-id').val();
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${apiUrl}/${id}` : apiUrl;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nama: $('#nama').val(),
                        nip: $('#nip').val(),
                        jabatan: $('#jabatan').val(),
                        no_hp: $('#no_hp').val(),
                    },
                })
                    .done(function (response) {
                        resetForm();
                        showMessage('success', response.message);
                        loadPetugas();
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            $('#reset-button').on('click', resetForm);

            $('#search-petugas').on('input', renderRows);

            $('#petugas-table-body').on('click', '.edit-button', function () {
                const id = $(this).data('id');

                $.getJSON(`${apiUrl}/${id}`)
                    .done(function (response) {
                        const petugas = response.data;

                        $('#petugas-id').val(petugas.id);
                        $('#nama').val(petugas.nama);
                        $('#nip').val(petugas.nip);
                        $('#jabatan').val(petugas.jabatan);
                        $('#no_hp').val(petugas.no_hp);
                        setFormMode('edit');
                        clearMessage();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            $('#petugas-table-body').on('click', '.delete-button', function () {
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
                        loadPetugas();
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            loadPetugas();
        });
    </script>
@endsection
