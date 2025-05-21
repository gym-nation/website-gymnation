<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class dataGymController extends Controller
{
    public function mainDataGYM()
    {
        $dataFasilitas = $this->getDataFasilitas();
        $makananSehat = $this->getDataMakanan();
        $dataKelas = $this->getDataKelas();
        $dataPotonganHarga = $this->getPotonganHarga();

        return view('adminDataGYM', [
            'dataFasilitas' => $dataFasilitas,
            'dataMakanan' => $makananSehat,
            'dataKelas' => $dataKelas,
            'dataPotonganHarga' => $dataPotonganHarga
        ]);
    }

    public function homeAdmin()
    {
        $statusGYM = $this->getStatusGYM();
        return view('adminHome', [
            'statusGYM' => $statusGYM,
        ]);
    }

    // FASILITAS
    public function getDataFasilitas()
    {
        $baseurl=config('api.url');
        $client = new Client();

        try {
            $response = $client->get("{$baseurl}/fasilitas/getAllFasilitas");
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            return redirect()->route('home')->withErrors(['data' => 'Data tidak ditemukan.' . $th->getMessage()]);
        }
    }

    public function deleteFasilitas(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id_fasilitas = $request->input('id_fasilitas');

        if (!$id_fasilitas) {
            return response()->json(['success' => false, 'error' => 'fasilitas ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->delete("{$baseurl}/fasilitas/deleteFasilitas/{$id_fasilitas}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'fasilitas berhasil dihapus']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal menghapus fasilitas: ' . $e->getMessage()]);
        }
    }

    public function addFasilitas(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $client = new Client();
            $response = $client->post("{$baseurl}/fasilitas/tambahFasilitas", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'multipart' => [
                    [
                        'name' => 'title',
                        'contents' => $request->input('title'),
                    ],
                    [
                        'name' => 'fasilitas_img',
                        'contents' => fopen($request->file('fasilitas_img')->getPathname(), 'r'),
                        'filename' => $request->file('fasilitas_img')->getClientOriginalName(),
                    ],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['success']) {
                return response()->json(['success' => true, 'message' => 'fasilitas berhasil ditambahkan']);
            } else {
                return response()->json(['success' => false, 'error' => 'Gagal menambahkan fasilitas']);
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // MAKANAN
    public function getDataMakanan()
    {
        $baseurl=config('api.url');
        $client = new Client();

        try {
            $response = $client->get("{$baseurl}/makanan/getAllMakanan");
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            return redirect()->route('home')->withErrors(['data' => 'Data tidak ditemukan.' . $th->getMessage()]);
        }
    }

    public function deleteMakanan(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id_makanan = $request->input('id_makanan');

        if (!$id_makanan) {
            return response()->json(['success' => false, 'error' => 'fasilitas ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->delete("{$baseurl}/makanan/deleteMakanan/{$id_makanan}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'fasilitas berhasil dihapus']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal menghapus fasilitas: ' . $e->getMessage()]);
        }
    }

    public function addMakanan(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $client = new Client();
            $response = $client->post("{$baseurl}/makanan/tambahMakanan", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'multipart' => [
                    [
                        'name' => 'title',
                        'contents' => $request->input('title'),
                    ],
                    [
                        'name' => 'deskripsi',
                        'contents' => $request->input('deskripsi'),
                    ],
                    [
                        'name' => 'makanansehat_img',
                        'contents' => fopen($request->file('makanansehat_img')->getPathname(), 'r'),
                        'filename' => $request->file('makanansehat_img')->getClientOriginalName(),
                    ],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['success']) {
                return response()->json(['success' => true, 'message' => 'makanan berhasil ditambahkan']);
            } else {
                return response()->json(['success' => false, 'error' => 'Gagal menambahkan makanan']);
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // KELAS
    public function getDataKelas()
    {
        $baseurl=config('api.url');
        $client = new Client();

        try {
            $response = $client->get("{$baseurl}/kelas/getAllKelas");
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            return redirect()->route('home')->withErrors(['data' => 'Data tidak ditemukan.' . $th->getMessage()]);
        }
    }

    public function deleteKelas(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id_kelas = $request->input('id_kelas');

        if (!$id_kelas) {
            return response()->json(['success' => false, 'error' => 'fasilitas ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->delete("{$baseurl}/kelas/deleteKelas/{$id_kelas}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'kelas berhasil dihapus']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal menghapus kelas: ' . $e->getMessage()]);
        }
    }

    public function editAnggotaKelas(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id_kelas = $request->input('id_kelas');
        Log::info('ID Kelas:', [$id_kelas]);

        if (!$id_kelas) {
            return response()->json(['success' => false, 'error' => 'fasilitas ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->patch("{$baseurl}/kelas/ubahJumlahAnggota/{$id_kelas}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'anggota kelas berhasil diubah']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'anggota kelas gagal diubah: ' . $e->getMessage()]);
        }
    }

    public function editHariKelas(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id_kelas = $request->input('id_kelas');

        if (!$id_kelas) {
            return response()->json(['success' => false, 'error' => 'fasilitas ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->patch("{$baseurl}/kelas/ubahHariKelas/{$id_kelas}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'hari kelas berhasil diubah']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal mengubbah hari kelas: ' . $e->getMessage()]);
        }
    }

    public function addKelas(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $client = new Client();
            $response = $client->post("{$baseurl}/kelas/tambahKelas", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['success']) {
                return response()->json(['success' => true, 'message' => 'kelas berhasil ditambahkan']);
            } else {
                return response()->json(['success' => false, 'error' => 'Gagal menambahkan kelas']);
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // POTONGAN HARGA
    public function getPotonganHarga()
    {
        $baseurl=config('api.url');
        $client = new Client();

        try {
            $response = $client->get("{$baseurl}/potonganHarga/getAllPotonganHarga");
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            return redirect()->route('home')->withErrors(['data' => 'Data tidak ditemukan.' . $th->getMessage()]);
        }
    }

    public function deletePotonganHarga(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id_potongan_harga = $request->input('id_potongan_harga');

        if (!$id_potongan_harga) {
            return response()->json(['success' => false, 'error' => 'fasilitas ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->delete("{$baseurl}/potonganHarga/deletePotonganHarga/{$id_potongan_harga}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'potongan harga berhasil dihapus']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal menghapus potongan harga: ' . $e->getMessage()]);
        }
    }

    public function addPotonganHarga(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $client = new Client();
            $response = $client->post("{$baseurl}/potonganHarga/tambahPotonganHarga", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['success']) {
                return response()->json(['success' => true, 'message' => 'potongan harga berhasil ditambahkan']);
            } else {
                return response()->json(['success' => false, 'error' => 'Gagal menambahkan potongan harga']);
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // STATUS GYM
    public function getStatusGYM()
    {
        $baseurl=config('api.url');
        $client = new Client();

        try {
            $response = $client->get("{$baseurl}/status/getStatusGYM");
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            return redirect()->route('home')->withErrors(['data' => 'Data tidak ditemukan.' . $th->getMessage()]);
        }
    }

    public function editStatusGYM(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $client = new Client();
            $response = $client->patch("{$baseurl}/status/updateStatus", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'status gym berhasil diubah']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal mengubah status gym: ' . $e->getMessage()]);
        }
    }
}
