@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')
    <div id="controller">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <a href="#" @click="addData()" class="btn btn-primary"><i class='bx bx-plus-circle'></i> Tambah Mobil</a>
                        </div>
                        <div class="col-md-2">
                            <select name="ketersediaan" class="form-control">
                                <option value="3">Ketersediaan</option>
                                <option value="2">Tersedia</option>
                                <option value="1">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <table id="datatable" class="table table-bordered">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Merk</th>
                      <th>Model</th>
                      <th>Nomor Plat</th>
                      <th>Tarif Sewa per hari</th>
                      <th>Ketersediaan</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>
    
              <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form :action="actionUrl" method="POST" @submit="submitForm($event, data.id)">
                    <div class="modal-header">
                        <h4 class="modal-title">Mobil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            @csrf

                            <input type="hidden" name="_method" value="PUT" v-if="editStatus">

                            <div class="form-group">
                                <label for="merk">Merk Mobil</label>
                                <input type="text" name="merk" class="form-control" id="merk" placeholder="Masukkan merk mobil" :value="data.merk">
                            </div>
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" name="model" class="form-control" id="model" placeholder="Masukkan model" :value="data.model">
                            </div>
                            <div class="form-group">
                                <label for="plat">No. Plat</label>
                                <input type="text" name="plat" class="form-control" id="plat" placeholder="Masukkan No. Plat" :value="data.plat">
                            </div>
                            <div class="form-group">
                                <label for="sewa">Sewa per hari</label>
                                <input type="number" name="sewa" class="form-control" id="sewa" placeholder="Masukkan sewa per hari" :value="data.sewa">
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        var actionUrl = '{{ url('cars') }}';
        var apiUrl = '{{ url('api/cars') }}';

        var columns = [
            {data: 'DT_RowIndex', class: 'text-center', orderable: true},
            {data: 'merk', class: 'text-center', orderable: true},
            {data: 'model', class: 'text-center', orderable: true},
            {data: 'plat', class: 'text-center', orderable: false},
            {data: 'harga', class: 'text-center', orderable: true},
            {data: 'status', class: 'text-center ', orderable: true},
            {render: function (index, row, data, meta) {
            return `
                <a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">Edit</a>
                <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">Delete</a>
            `;
            }, orderable: false, width: '200px', class: 'text-center'},
        ];
    </script>
    <script src="{{ asset('js/data.js')}}"></script>
    <script>
        $('select[name=ketersediaan]').on('change', function(){
            ketersediaan = $('select[name=ketersediaan]').val();
            if(ketersediaan==3){
                controller.table.ajax.url(apiUrl).load();
            } else{
                controller.table.ajax.url(apiUrl+'?ketersediaan='+ketersediaan).load();
            }
        });
    </script>
@endsection