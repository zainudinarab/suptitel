<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Subtitle Control</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">

    <h2>Subtitle Control</h2>

    @if ($live && $live->item)
        <div class="alert alert-success">
            Live :
            <strong>{{ $live->group?->nama }}</strong>
            -
            {{ $live->item?->judul }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            Tambah Grup
        </div>

        <div class="card-body">

            <form method="POST" action="/subtitle/group">
                @csrf

                <div class="mb-3">
                    <label>Nama Grup</label>

                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>

                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>

                <button class="btn btn-primary">
                    Simpan
                </button>

            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Daftar Grup
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Jumlah Item</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($groups as $group)
                    <tr>

                        <td>{{ $group->id }}</td>

                        <td>{{ $group->nama }}</td>

                        <td>{{ $group->items_count }}</td>

                        <td>

                            <a href="/subtitle/group/{{ $group->id }}" class="btn btn-primary btn-sm">

                                Buka

                            </a>

                        </td>

                    </tr>
                @endforeach

            </table>

        </div>
    </div>

</body>

</html>
