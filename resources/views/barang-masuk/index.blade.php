@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
    <div class="section-header">
        <h1>Barang Masuk</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangMasuk">
                <i class="fa fa-plus"></i> Barang Masuk
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Masuk</th>
                                    <th>Supplier</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('barang-masuk.create')
@endsection

@push('scripts')
<script>
$(function () {
    var csrf  = $('meta[name="csrf-token"]').attr('content');
    var modal = $('#modal_tambah_barangMasuk').appendTo('body');
    var table = $('#table_id').DataTable({
        columnDefs: [{ orderable: false, targets: 6 }]
    });


    // ================= INIT SELECT2 DI DALAM MODAL =================
    $('#barang_id').select2({ dropdownParent: modal, width: '100%' });
    $('#supplier').select2({
        dropdownParent: modal,
        width: '100%',
        tags: true,
        placeholder: 'Pilih atau ketik supplier'
    });

    // ================= STOK SAAT INI =================
    $('#barang_id').on('change', function () {
        var id = $(this).val();
        if (!id) {
            $('#stok').val('');
            $('#satuan_text').text('-');
            return;
        }
        $.get('/barang-masuk/get-barang-detail', { barang_id: id }, function (res) {
            $('#stok').val(res.stok);
            $('#satuan_text').text(res.satuan);
        });
    });

    // ================= BUKA MODAL =================
    function bukaModal() {
        resetForm();
        $('#tanggal_masuk').val(new Date().toISOString().slice(0, 10));
        modal.modal('show');
    }

    $('#button_tambah_barangMasuk').on('click', bukaModal);

    // Dipanggil dari dashboard: /barang-masuk?tambah=1
    if (window.location.search.indexOf('tambah=1') !== -1) {
        bukaModal();
    }

    // ================= RESET FORM =================
    function resetForm() {
        $('#form_barangMasuk')[0].reset();
        $('#barang_id').val('').trigger('change');
        $('#supplier').val('').trigger('change');
        $('#stok').val('');
        $('#satuan_text').text('-');
        $('.alert-field').addClass('d-none').text('');
    }

    // ================= LOAD TABEL =================
    function loadTable() {
        $.get('/barang-masuk/get-data', function (res) {
            table.clear();
            $.each(res.data, function (i, item) {
                table.row.add([
                    i + 1,
                    item.kode_transaksi,
                    item.tanggal,
                    item.nama_barang,
                    item.jumlah,
                    item.supplier,
                    '<button class="btn btn-danger btn-sm delete-btn" data-id="' + item.id + '">Hapus</button>'
                ]);
            });
            table.draw(false);
        });
    }
    loadTable();

    // ================= SIMPAN =================
    $('#store_barangMasuk').on('click', function () {
        var btn = $(this);
        $('.alert-field').addClass('d-none').text('');
        btn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: '/barang-masuk',
            type: 'POST',
            data: $('#form_barangMasuk').serialize(),
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function (res) {
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
                modal.modal('hide');
                loadTable();
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (field, msgs) {
                        $('#alert-' + field).removeClass('d-none').text(msgs[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: pesanError(xhr) });
                }
            },
            complete: function () {
                btn.prop('disabled', false).text('Tambah');
            }
        });
    });

    // ================= HAPUS =================
    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: 'Data akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS!',
            cancelButtonText: 'BATAL'
        }).then(function (r) {
            if (!r.isConfirmed) return;

            $.ajax({
                url: '/barang-masuk/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf },
                success: function (res) {
                    Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
                    loadTable();
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: pesanError(xhr) });
                }
            });
        });
    });

    function pesanError(xhr) {
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                return Object.values(xhr.responseJSON.errors)[0][0];
            }
            if (xhr.responseJSON.message) {
                return xhr.responseJSON.message;
            }
        }
        return 'Terjadi kesalahan pada server.';
    }
});
</script>
@endpush