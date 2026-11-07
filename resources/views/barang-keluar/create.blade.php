<div class="modal fade" id="modal_tambah_barangKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Keluar</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form id="form_barangKeluar">
                <div class="modal-body">
                    <div class="row">

                        {{-- KIRI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Keluar</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal_keluar"
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
                                <label>Penerima</label>
                                <select class="form-control" name="penerima" id="penerima">
                                    <option value=""></option>
                                    @foreach ($penerima as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-penerima"></div>
                            </div>

                            <div class="form-group">
                                <label>Jumlah Keluar</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="jumlah" id="jumlah" min="1">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light" id="satuan_text">-</span>
                                    </div>
                                </div>
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-jumlah"></div>
                            </div>

                            <div class="form-group">
                                <label>Keperluan <small class="text-muted">(opsional, mis. nomor pesanan MTO)</small></label>
                                <input type="text" class="form-control" name="keperluan" id="keperluan" maxlength="150">
                                <div class="alert alert-danger mt-2 d-none alert-field" id="alert-keperluan"></div>
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
                    <button type="button" class="btn btn-primary" id="store_barangKeluar">Tambah</button>
                </div>
            </form>

        </div>
    </div>
</div>