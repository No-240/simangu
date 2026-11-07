<div class="modal fade" id="modal_barang" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal_barang_title">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form id="form_barang">
                <input type="hidden" id="barang_id_edit" value="">

                <div class="modal-body">
                    <div class="row">

                        {{-- KIRI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" class="form-control" name="kode" id="barang_kode" placeholder="Contoh: PLT-304-2">
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-kode"></div>
                            </div>

                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama" id="barang_nama" placeholder="Masukkan nama barang">
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-nama"></div>
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="form-control" name="kategori" id="barang_kategori">
                                    <option value=""></option>
                                    @foreach ($kategori as $k)
                                        <option value="{{ $k }}">{{ $k }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-kategori"></div>
                            </div>
                        </div>

                        {{-- KANAN --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan</label>
                                <select class="form-control" name="satuan" id="barang_satuan">
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($satuanList as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-satuan"></div>
                            </div>

                            <div class="form-group">
                                <label>Stok Minimum</label>
                                <input type="number" class="form-control" name="stok_minimum" id="barang_stok_minimum" min="0" placeholder="Misal: 10">
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-stok_minimum"></div>
                            </div>

                            <div class="form-group">
                                <label>Lokasi / Rak <small class="text-muted">(opsional)</small></label>
                                <input type="text" class="form-control" name="lokasi" id="barang_lokasi" placeholder="Contoh: Rak A1">
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-lokasi"></div>
                            </div>

                            <p class="text-muted small mb-0">Stok awal diisi lewat menu Barang Masuk.</p>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" id="store_barang">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>