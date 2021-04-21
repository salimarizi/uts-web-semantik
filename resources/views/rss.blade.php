@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">RSS</div>

                <div class="card-body">
                    <form class="col-md-12" method="get">
                      <div class="row">
                        <input type="text" name="search" id="txtSearch" class="form-control col-md-4" placeholder="Cari Judul Berita disini...">
                        <button type="button" id="btnSearch" class="btn btn-primary">Cari</button>
                      </div>
                    </form>
                    <br>

                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Sumber</th>
                          <th>Judul</th>
                          <th>Tanggal Publis</th>
                          <th>Link</th>
                        </tr>
                      </thead>
                      <tbody id="tbNews">

                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
  <script type="text/javascript">
    $( document ).ready(function() {
      getData('')
    });

    $('#btnSearch').on('click', () => {
      getData($('#txtSearch').val())
    })

    getData = (search) => {
      $("#tbNews").html('')
      $.ajax({
        type: "GET",
        url: "{{ url('rssData') }}",
        data: {search: search},
        success: (data) => {
          if (data.news.length > 0) {
              for (var i = 0; i < data.news.length; i++) {
                  $("#tbNews")
                    .append($('<tr>')
                    .append($('<td>').html(data.news[i].sumber))
                    .append($('<td>').html(data.news[i].title))
                    .append($('<td>').html(data.news[i].published_date))
                    .append($('<td>').html(
                      '<a target="_blank" href="'+ data.news[i].link +'">'+ data.news[i].link +'</a>'
                    ))
                )
              }
          } else {
              $("#tbNews")
                .append($('<tr>')
                .append(
                  $('<td colspan="4">').html("Tidak ada data ditemukan dengan kata kunci " + search))
                )
          }
        },
        error: (error) => {
          alert('Terjadi kesalahan dari sumber RSS yang diminta, mohon refresh halaman kembali')
        }
      })
    }
  </script>
@endsection
