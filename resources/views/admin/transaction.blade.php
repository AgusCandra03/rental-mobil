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
                            <select name="status" class="form-control">
                                <option value="3">Semua Status</option>
                                <option value="2">Telah Dikembalikan</option>
                                <option value="1">Disewa</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <table id="datatable" class="table table-bordered">
                    <thead>
                    <tr>
                      <th style="width: 15px">No.</th>
                      <th>Plat Mobil</th>
                      <th>Mobil</th>
                      <th>Tanggal Mulai</th>
                      <th>Tanggal Selesai</th>
                      <th>Lama Sewa</th>
                      <th>Biaya Sewa</th>
                      <th>Status</th>
                      <th style="width: 5500px">Action</th>
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
                        <h4 class="modal-title">Sewa Mobil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            @csrf

                            <input type="hidden" name="_method" value="PUT" v-if="editStatus">

                            <div class="form-group">
                                <label for="merk">Mobil</label>
                                <select name="id_mobil" class="form-control">
                                    @foreach ($cars as $car)
                                        @if ($car->ketersediaan == 2)
                                            <option :value="{{ $car->id }}" :selected="data.id_mobil == {{ $car->id }}">{{ $car->merk }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tgl_mulai">Tanggal Pinjam</label>
                                <input type="date" name="tgl_mulai" class="form-control" id="tgl_mulai" :value="data.tgl_mulai">
                            </div>
                            <div class="form-group">
                                <label for="tgl_selesai">Tanggal Kembali</label>
                                <input type="date" name="tgl_selesai" class="form-control" id="tgl_selesai" :value="data.tgl_selesai">
                            </div>
                            <div class="form-group" v-if="editStatus">
                                <div>
                                    <label class="col-md-3">Status</label>
                                    <input id="radio1" type="radio" name="status" :checked="data.status==1" value="1">
                                    <label for="radio1">Disewa</label>
                                </div>
                                <div>
                                    <label class="col-md-3"></label>
                                    <input id="radio2" type="radio" name="status" :checked="data.status==2" value="2">
                                    <label for="radio2">Telah Dikembalikan</label>
                                </div>
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
        var actionUrl = '{{ url('transactions') }}';
        var apiUrl = '{{ url('api/transactions') }}';

        var columns = [
            {data: 'DT_RowIndex', class: 'text-center', orderable: false},
            {data: 'plat_mobil', class: 'text-center', orderable: false},
            {data: 'cars.merk', class: 'text-center', orderable: false},
            {data: 'tgl_mulai', class: 'text-center', orderable: true},
            {data: 'tgl_selesai', class: 'text-center', orderable: true},
            {data: 'lama_pinjam', class: 'text-center ', orderable: false},
            {data: 'biaya', class: 'text-center ', orderable: false},
            {data: 'status_sewa', class: 'text-center ', orderable: true},
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
        $('select[name=status]').on('change', function(){
            status = $('select[name=status]').val();
            if(status==3){
                controller.table.ajax.url(apiUrl).load();
            } else{
                controller.table.ajax.url(apiUrl+'?status='+status).load();
            }
        });
    </script>
@endsection