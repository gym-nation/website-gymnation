<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function userReports()
    {
        $token = session('token');
        $userReportsData = $this->getAllDataUser($token);
        return view('adminUser', ['dataUser' => $userReportsData]);

    }

    public function getAllDataUser($token)
    {
        $baseurl=config('api.url');
        $client = new Client();

        try {
            $response = $client->get("{$baseurl}/user/getAllUser", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            return redirect()->route('admin.user')->withErrors(['data' => 'Data tidak ditemukan.' . $th->getMessage()]);
        }
    }

    public function deleteUser(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $idUser = $request->input('id_user');
        if (!$idUser) {
            return response()->json(['success' => false, 'error' => 'User ID is required'], 400);
        }

        try {
            $client = new Client();
            $response = $client->delete("{$baseurl}/user/deleteUser/{$idUser}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'user berhasil dihapus']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal menghapus user: ' . $e->getMessage()]);
        }
    }

    public function addUser(Request $request)
    {
        $baseurl=config('api.url');
        $token = session('token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $client = new Client();
            $response = $client->post("{$baseurl}/user/addUser", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'user berhasil ditambahkan']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal menambahkan user: ' . $e->getMessage()]);
        }
    }

    public function createAccount(Request $request)
    {
        try {
            $baseurl=config('api.url');
            $client = new Client();
            $response = $client->post("{$baseurl}/user/addPelanggan", [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(['success' => true, 'message' => 'berhasil membuat akun']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'gagal membuat akun: ' . $e->getMessage()]);
        }
    }

}
