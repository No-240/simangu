<div class="modal fade" id="modal_tambah_barangMasuk" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form id="form_barangMasuk">
                <div class="modal-body">
                    <div class="row">

                        {{-- KIRI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal_masuk"
                                       max="{{ now()->toDateString() }}">
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-tanggal"></div>
                            </div>

                            <div class="form-group">
                                <label>Kode Transaksi</label>
                                <input type="text" class="form-control" id="kode_transaksi"
                                       value="{{ $kodeTransaksi }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Stok Saat Ini</label>
                                <input type="number" class="form-control" id="stok" disabled>
                            </div>
                        </div>

                        {{-- KANAN --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Barang</label>
                                <select class="form-control" name="barang_id" id="barang_id">
                                    <option value="">Pilih Barang</option>
                                    @foreach ($barangs as $b)
                                        <option value="{{ $b->id }}">{{ $b->kode }} - {{ $b->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-barang_id"></div>
                            </div>

                            <div class="form-group">
                                <label>Supplier</label>
                                <select class="form-control" name="supplier" id="supplier">
                                    <option value=""></option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-supplier"></div>
                            </div>

                            <div class="form-group">
                                <label>Jumlah Masuk</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="jumlah" id="jumlah" min="1">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light" id="satuan_text">-</span>
                                    </div>
                                </div>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-jumlah"></div>
                            </div>

                            <div class="form-group">
                                <label>Keterangan <small class="text-muted">(opsional)</small></label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="2"></textarea>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-keterangan"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" id="store_barangMasuk">Tambah</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- SALIN di sini isi <style>...</style> dari bagian bawah barang-masuk/create.blade.php referensi
     (aturan tinggi Select2 dan form-control agar sama dengan referensi) --}}