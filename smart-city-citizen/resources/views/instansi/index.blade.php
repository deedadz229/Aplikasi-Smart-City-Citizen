@extends('layouts.app')

@section('title', 'Data Instansi')

@section('content')
    <section class="page-header">
        <div>
            <p class="eyebrow">Modul Instansi</p>
            <h1>Kelola Data Instansi & Dinas Pelayanan Kota.</h1>
            <p class="lead">Tambah, perbarui, cari, dan hapus data dinas/lembaga langsung dari halaman ini tanpa reload.</p>
        </div>

        <div class="button-row">
            <a class="button button-secondary" href="{{ url('/') }}">Kembali ke Dashboard</a>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan data instansi">
        <article class="panel stat-card">
            <span>Total Instansi</span>
            <strong id="total-instansi">0</strong>
        </article>
        <article class="panel stat-card">
            <span>Instansi Aktif</span>
            <strong id="active-instansi">0</strong>
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
            <p class="eyebrow">Form Instansi</p>
            <h2 id="form-title">Tambah Data Instansi</h2>

            <form id="instansi-form">
                <input type="hidden" id="instansi-id">

                <div class="form-grid">
                    <div>
                        <label for="nama_instansi">Nama Instansi</label>
                        <input type="text" id="nama_instansi" name="nama_instansi" maxlength="150" placeholder="Nama instansi/dinas" required>
                    </div>

                    <div>
                        <label for="kategori">Kategori</label>
                        <input type="text" id="kategori" name="kategori" maxlength="100" placeholder="Contoh: Kesehatan, Pendidikan" required>
                    </div>

                    <div>
                        <label for="no_telp">No. Telp</label>
                        <input type="text" id="no_telp" name="no_telp" maxlength="20" placeholder="021-xxxxxxx" required>
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" maxlength="150" placeholder="instansi@smartcity.go.id" required>
                    </div>

                    <div>
                        <label for="pimpinan">Nama Pimpinan</label>
                        <input type="text" id="pimpinan" name="pimpinan" maxlength="150" placeholder="Nama kepala instansi" required>
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-aktif">Non-aktif</option>
                        </select>
                    </div>

                    <div class="full">
                        <label for="alamat">Alamat Kantor</label>
                        <textarea id="alamat" name="alamat" maxlength="1000" placeholder="Alamat kantor lengkap" required></textarea>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary" id="submit-button">Tambah Instansi</button>
                    <button type="button" class="btn-secondary" id="reset-button">Batal Edit</button>
                </div>
            </form>
        </div>

        <div class="panel panel-pad">
            <div class="toolbar">
                <div>
                    <p class="eyebrow">Daftar Instansi</p>
                    <h2>Database Instansi</h2>
                </div>

                <div class="search-wrap">
                    <input type="search" id="search-instansi" placeholder="Nama, kategori, pimpinan, email">
                </div>
            </div>

            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Instansi</th>
                            <th>Kategori</th>
                            <th>No. Telp / Email</th>
                            <th>Pimpinan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="instansi-table-body">
                        <tr>
                            <td colspan="6">Memuat data instansi...</td>
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
            const apiUrl = '/api/instansi';
            let instansiItems = [];

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
                $('#form-title').text(isEdit ? 'Edit Data Instansi' : 'Tambah Data Instansi');
                $('#submit-button').text(isEdit ? 'Simpan Perubahan' : 'Tambah Instansi');
            }

            function resetForm() {
                $('#instansi-id').val('');
                $('#instansi-form')[0].reset();
                $('#status').val('Aktif');
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
                const keyword = $('#search-instansi').val().toLowerCase().trim();

                if (! keyword) {
                    return instansiItems;
                }

                return instansiItems.filter(function (item) {
                    return [
                        item.nama_instansi,
                        item.kategori,
                        item.no_telp,
                        item.email,
                        item.pimpinan,
                        item.alamat,
                    ].join(' ').toLowerCase().includes(keyword);
                });
            }

            function updateSummary(items) {
                $('#total-instansi').text(instansiItems.length);
                const activeCount = instansiItems.filter(i => i.status === 'Aktif').length;
                $('#active-instansi').text(activeCount);
                $('#load-status').text('Siap');
            }

            function renderRows() {
                const items = filteredItems();
                const rows = items.map(function (item) {
                    const statusBadge = item.status === 'Aktif'
                        ? `<span class="badge badge-success">Aktif</span>`
                        : `<span class="badge badge-danger">Non-aktif</span>`;

                    return `
                        <tr>
                            <td data-label="Nama Instansi"><strong>${escapeHtml(item.nama_instansi)}</strong></td>
                            <td data-label="Kategori"><span class="badge">${escapeHtml(item.kategori)}</span></td>
                            <td data-label="Kontak">
                                <span class="contact-line">Telp. ${escapeHtml(item.no_telp)}</span>
                                <span class="contact-subline">${escapeHtml(item.email)}</span>
                            </td>
                            <td data-label="Pimpinan">${escapeHtml(item.pimpinan)}</td>
                            <td data-label="Status">${statusBadge}</td>
                            <td data-label="Aksi">
                                <div class="table-actions">
                                    <button type="button" class="btn-secondary edit-button" data-id="${item.id}">Edit</button>
                                    <button type="button" class="btn-danger delete-button" data-id="${item.id}">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#instansi-table-body').html(rows.length ? rows.join('') : '<tr><td colspan="6">Belum ada data instansi yang cocok.</td></tr>');
                updateSummary(items);
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function loadInstansi() {
                $('#load-status').text('Memuat');

                $.getJSON(apiUrl)
                    .done(function (response) {
                        instansiItems = response.data || [];
                        renderRows();
                    })
                    .fail(function (response) {
                        $('#load-status').text('Error');
                        showMessage('error', formatErrors(response));
                    });
            }

            $('#instansi-form').on('submit', function (event) {
                event.preventDefault();

                const id = $('#instansi-id').val();
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${apiUrl}/${id}` : apiUrl;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nama_instansi: $('#nama_instansi').val(),
                        kategori: $('#kategori').val(),
                        no_telp: $('#no_telp').val(),
                        email: $('#email').val(),
                        pimpinan: $('#pimpinan').val(),
                        status: $('#status').val(),
                        alamat: $('#alamat').val(),
                    },
                })
                    .done(function (response) {
                        resetForm();
                        showMessage('success', response.message);
                        loadInstansi();
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            $('#reset-button').on('click', resetForm);

            $('#search-instansi').on('input', renderRows);

            $('#instansi-table-body').on('click', '.edit-button', function () {
                const id = $(this).data('id');

                $.getJSON(`${apiUrl}/${id}`)
                    .done(function (response) {
                        const instansi = response.data;

                        $('#instansi-id').val(instansi.id);
                        $('#nama_instansi').val(instansi.nama_instansi);
                        $('#kategori').val(instansi.kategori);
                        $('#no_telp').val(instansi.no_telp);
                        $('#email').val(instansi.email);
                        $('#pimpinan').val(instansi.pimpinan);
                        $('#status').val(instansi.status);
                        $('#alamat').val(instansi.alamat);
                        setFormMode('edit');
                        clearMessage();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            $('#instansi-table-body').on('click', '.delete-button', function () {
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
                        loadInstansi();
                    })
                    .fail(function (response) {
                        showMessage('error', formatErrors(response));
                    });
            });

            loadInstansi();
        });
    </script>
@endsection
