<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel User</title>
    <style>
        /* Gaya tabel dengan warna pink dan putih */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        .table-custom thead {
            background-color: #ff69b4; /* Warna pink untuk header */
            color: white; /* Warna teks putih untuk kontras */
        }

        .table-custom th,
        .table-custom td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .table-custom tbody tr:nth-child(odd) {
            background-color: #fff; /* Warna putih untuk baris ganjil */
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #ffe6f0; /* Warna pink muda untuk baris genap */
        }

        .table-custom tbody tr:hover {
            background-color: #ffb6c1; /* Warna pink lebih terang saat hover */
        }
    </style>
</head>
<body>
    @extends('layout.template')

    @section('content')
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIU</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Sheryn Alya Azzahra Jaymet</td>
                    <td>515601</td>
                    <td>PGWEBL</td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Cindi Maulidia Diego</td>
                    <td>232359</td>
                    <td>PGWEBL</td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Aurora Anindya Bieber</td>
                    <td>23699</td>
                    <td>PGWEBL</td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>Thalita Nescafe</td>
                    <td>812638</td>
                    <td>PGWEBL</td>
                </tr>
                <!-- Tambahkan baris lain jika diperlukan -->
            </tbody>
        </table>
    @endsection
</body>
</html>
