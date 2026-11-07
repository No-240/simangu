@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <div class="section-header">
        <h1>Data Barang</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barang">
                <i class="fa fa-plus"></i> Tambah Barang
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
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
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

    @include('barang.create', ['kategori' => $kategori, 'satuanList' => $satuanList])
    @include('barang.detail')
@endsection

@push('scripts')
<script>
$(function () {
    var csrf        = $('meta[name="csrf-token"]').attr('content');
    var modal       = $('#modal_barang');
    var modalDetail = $('#modal_detail_barang');

    modal.appendTo('body');
    modalDetail.appendTo('body');

    var table = $('#table_id').DataTable({
        columnDefs: [{ orderable: false, targets: 6 }]
    });

    $('#barang_kategori').select2({ dropdownParent: modal, width: '100%', tags: true, placeholder: 'Pilih atau ketik kategori' });
    $('#barang_satuan').select2({ dropdownParent: modal, width: '100%' });

    // ================= LOAD TABEL =================
    function loadTable() {
        $.get('/barang/get-data', function (res) {
            table.clear();
            $.each(res.data, function (i, item) {
                table.row.add([
                    i + 1,
                    item.kode,
                    item.nama,
                    item.kategori,
                    item.satuan,
                    item.stok,
                    '<button class="btn btn-success btn-sm detail-btn" data-id="' + item.id + '"><i class="fa fa-eye"></i></button> ' +
                    '<button class="btn btn-warning btn-sm edit-btn" data-id="' + item.id + '"><i class="fa fa-edit"></i></button> ' +
                    '<button class="btn btn-danger btn-sm delete-btn" data-id="' + item.id + '"><i class="fa fa-trash"></i></button>'
                ]);
            });
            table.draw(false);
        });
    }
    loadTable();

    // ================= HELPER =================
    function setSelect(sel, val) {
        if (val && $(sel).find('option[value="' + val + '"]').length === 0) {
            $(sel).append(new Option(val, val, true, true));
        }
        $(sel).val(val || null).trigger('change');
    }

    function resetForm() {
        $('#form_barang')[0].reset();
        $('#barang_id_edit').val('');
        setSelect('#barang_kategori', null);
        setSelect('#barang_satuan', null);
        $('.alert-field').addClass('d-none').text('');
    }

    function pesanError(xhr) {
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) return Object.values(xhr.responseJSON.errors)[0][0];
            if (xhr.responseJSON.message) return xhr.responseJSON.message;
        }
        return 'Terjadi kesalahan pada server.';
    }

    // ================= TAMBAH =================
    $('#button_tambah_barang').on('click', function () {
        resetForm();
        $('#modal_barang_title').text('Tambah Barang');
        modal.modal('show');
    });

    // ================= EDIT =================
    $(document).on('click', '.edit-btn', function () {
        $.get('/barang/' + $(this).data('id') + '/detail', function (b) {
            resetForm();
            $('#modal_barang_title').text('Edit Barang');
            $('#barang_id_edit').val(b.id);
            $('#barang_kode').val(b.kode);
            $('#barang_nama').val(b.nama);
            setSelect('#barang_kategori', b.kategori);
            setSelect('#barang_satuan', b.satuan);
            $('#barang_lokasi').val(b.lokasi);
            $('#barang_stok_minimum').val(b.stok_minimum);
            modal.modal('show');
        });
    });

    // ================= SIMPAN (TAMBAH / EDIT) =================
    $('#store_barang').on('click', function () {
        var btn = $(this);
        var id  = $('#barang_id_edit').val();

        $('.alert-field').addClass('d-none').text('');
        btn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: id ? '/barang/' + id : '/barang',
            type: id ? 'PUT' : 'POST',
            data: $('#form_barang').serialize(),
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
                btn.prop('disabled', false).text('Simpan');
            }
        });
    });

    // ================= DETAIL =================
    $(document).on('click', '.detail-btn', function () {
        $.get('/barang/' + $(this).data('id') + '/detail', function (b) {
            $('#detail_kode').text(b.kode);
            $('#detail_nama').text(b.nama);
            $('#detail_kategori').text(b.kategori || '-');
            $('#detail_satuan').text(b.satuan);
            $('#detail_lokasi').text(b.lokasi || '-');
            $('#detail_stok_minimum').text(b.stok_minimum);
            $('#detail_stok').text(b.stok);
            modalDetail.modal('show');
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
                url: '/barang/' + id,
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
});
</script>
@endpush