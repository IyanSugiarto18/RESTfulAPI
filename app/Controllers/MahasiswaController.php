<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MahasiswaModel;

class MahasiswaController extends ResourceController
{

    use ResponseTrait;

    public function __construct()
    {
        $this->model = new MahasiswaModel();
    }

    //get semua data
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data, 200);
    }

    //get satu data berdasarkan ID
    public function show($id = null)
    {
        $data = $this->model->getWhere(['id_mahasiswa' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('Tidak ada yang berdasarkan ID ' . $id);
        }
    }

    //Post creat data
    public function create()
    {
        $model = new MahasiswaModel();
        $data = [
            'nama_mhs'  => $this->request->getPost('nama_mhs'),
            'nim_mhs'   => $this->request->getPost('nim_mhs'),
            'kelas_mhs' => $this->request->getPost('kelas_mhs')
        ];
        $data = json_decode(file_get_contents("php://input"));
        $data = $this->request->getPost();
        $model->insert($data);
        $response = [
            'status'  => 201,
            'error'   => null,
            'message' => [
                'success' => 'Data Saved'
            ]
        ];
        return $this->respondCreated($data, 201);
    }

    //PUT update data
    public function update($id = null)
    {
        $model = new MahasiswaModel();
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                'nama_mhs'  => $json->nama_mhs,
                'nim_mhs'   => $json->nim_mhs,
                'kelas_mhs' => $json->kelas_mhs
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'nama_mhs' => $input['nama_mhs'],
                'nim_mhs' => $input['nim_mhs'],
                'kelas_mhs' => $input['kelas_mhs']
            ];
        }
        //insert to database
        $model->update($id, $data);
        $response = [
            'status'  => 200,
            'error'   => null,
            'message' => [
                'success' => 'Data success Update'
            ]
        ];
        return $this->respond($response);
    }

    //DELETE hapus data
    public function delete($id = null)
    {
        $model = new MahasiswaModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data behasi di Hapus'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Tidak ada yang berdasarkan ID ' . $id);
        }
    }
}
