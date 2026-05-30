<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <title>{{ $group->nama }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan Google Font Amiri -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .card {
            background-color: #1e1e1e;
            border: 1px solid #333;
            color: #fff;
        }

        .table {
            color: #e0e0e0;
        }

        .table-active-live {
            background-color: #2e7d32 !important;
            color: #fff !important;
        }

        .preview-box {
            background: #000;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #ffd700;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-family: 'Amiri', serif;
        }

        .word-active {
            color: #ffd700;
            font-weight: bold;
            text-shadow: 0 0 10px #ffd700;
        }

        .word-pending {
            opacity: 0.5;
        }

        .preview-box span {
            display: inline;
        }

        .btn-control {
            min-width: 120px;
            padding: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $group->nama }}</h2>
        <a href="/subtitle" class="btn btn-outline-light">Kembali</a>
    </div>

    <!-- Live Preview Area -->
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark fw-bold">LIVE PREVIEW</div>
        <div class="card-body text-center">
            <div class="preview-box" dir="rtl">
                @if ($live && $live->item)
                    @foreach ($live->words as $index => $word)
                        <span
                            class="{{ $index == $live->current_word ? 'word-active' : 'word-pending' }}">{{ $word }}</span>
                        {{-- Spasi antar kata --}}
                        @if (!$loop->last)
                            &nbsp;
                        @endif
                    @endforeach
                @else
                    <span class="text-muted">Tidak ada subtitle aktif</span>
                @endif
            </div>

            <div class="row g-2 justify-content-center">
                <div class="col-auto">
                    <div class="btn-group">
                        <button onclick="control('prev-subtitle')" class="btn btn-outline-light btn-control">◀
                            AYAT</button>
                        <button onclick="control('next-subtitle')" class="btn btn-outline-light btn-control">AYAT
                            ▶</button>
                    </div>
                    <div class="text-muted mt-1 small">Shortcut: ↑ ↓</div>
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <button onclick="control('prev-word')" class="btn btn-outline-warning btn-control">◀
                            KATA</button>
                        <button onclick="control('next-word')" class="btn btn-warning btn-control">KATA ▶</button>
                    </div>
                    <div class="text-muted mt-1 small">Shortcut: ← → atau Spasi</div>
                </div>
            </div>

            <div class="mt-3">
                <span class="badge bg-secondary">Mode: {{ $live && $live->item ? 'Aktif' : 'Standby' }}</span>
                <span class="badge bg-info ms-2">Total Kata: {{ $live && $live->item ? count($live->words) : 0 }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Tambah Teks</div>
                <div class="card-body">
                    <form method="POST" action="/subtitle/item">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <div class="mb-3">
                            <label>Judul (Opsional)</label>
                            <input type="text" name="judul"
                                class="form-control bg-dark text-white border-secondary"
                                placeholder="Contoh: Ayat {{ $group->items->count() + 1 }}">
                        </div>
                        <div class="mb-3">
                            <label>Urutan</label>
                            <input type="number" name="urutan"
                                class="form-control bg-dark text-white border-secondary"
                                value="{{ $group->items->count() + 1 }}">
                        </div>
                        <div class="mb-3">
                            <label>Isi</label>
                            <textarea name="isi" rows="3" class="form-control bg-dark text-white border-secondary" dir="rtl"
                                required></textarea>
                        </div>
                        <button class="btn btn-success w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <table class="table table-dark table-hover">
                <tr>
                    <th width="50">No</th>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th width="150">Aksi</th>
                </tr>
                @foreach ($group->items as $item)
                    <tr class="{{ $live && $live->subtitle_item_id == $item->id ? 'table-active-live' : '' }}">
                        <td>{{ $item->urutan }}</td>
                        <td>{{ $item->judul ?? '-' }}</td>
                        <td dir="rtl">{{ Str::limit($item->isi, 50) }}</td>
                        <td class="d-flex gap-1">
                            <form method="POST" action="/subtitle/activate/{{ $item->id }}">
                                @csrf
                                <button
                                    class="btn btn-sm {{ $live && $live->subtitle_item_id == $item->id ? 'btn-light' : 'btn-outline-primary' }}">
                                    {{ $live && $live->subtitle_item_id == $item->id ? 'Aktif' : 'Pilih' }}
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-warning"
                                onclick="openEditModal({{ json_encode($item) }})">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Edit Subtitle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Judul</label>
                            <input type="text" name="judul" id="edit_judul"
                                class="form-control bg-dark text-white border-secondary">
                        </div>
                        <div class="mb-3">
                            <label>Urutan</label>
                            <input type="number" name="urutan" id="edit_urutan"
                                class="form-control bg-dark text-white border-secondary">
                        </div>
                        <div class="mb-3">
                            <label>Isi</label>
                            <textarea name="isi" id="edit_isi" rows="5" class="form-control bg-dark text-white border-secondary"
                                dir="rtl" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        function openEditModal(item) {
            document.getElementById('editForm').action = `/subtitle/item/${item.id}/update`;
            document.getElementById('edit_judul').value = item.judul || '';
            document.getElementById('edit_urutan').value = item.urutan;
            document.getElementById('edit_isi').value = item.isi;
            editModal.show();
        }

        function control(action) {
            fetch(`/subtitle/${action}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                // Reload halaman agar preview dan highlight terupdate
                // Di masa depan bisa diganti dengan Livewire/AJAX murni agar lebih mulus
                location.reload();
            });
        }

        document.addEventListener('keydown', function(e) {
            if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return;

            if (e.key === 'ArrowRight' || e.code === 'Space') {
                e.preventDefault();
                control('next-word');
            }
            if (e.key === 'ArrowLeft') control('prev-word');
            if (e.key === 'ArrowUp') control('prev-subtitle');
            if (e.key === 'ArrowDown') control('next-subtitle');
            if (e.key === 'Enter') control('next-subtitle');
        });
    </script>
</body>

</html>
