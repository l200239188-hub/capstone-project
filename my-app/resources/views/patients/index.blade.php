<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien - Klinik Mediva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Sistem Monitoring Pasien - Klinik Mediva</h4>
                <a href="{{ route('patients.create') }}" class="btn btn-light btn-sm fw-bold">+ Pasien Baru</a>
            </div>
            <div class="card-body">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('patients.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama pasien atau NIK..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari Data</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>No. HP</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($patients->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center text-danger fw-bold">Data pasien tidak ditemukan.</td>
                            </tr>
                            @else
                                @foreach($patients as $index => $patient)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $patient->nik }}</td>
                                    <td>{{ $patient->nama_lengkap }}</td>
                                    <td>{{ $patient->no_hp }}</td>
                                    <td class="text-center">{{ $patient->tanggal_lahir }}</td>
                                    <td>{{ $patient->alamat }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pasien ini?');">
                                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>