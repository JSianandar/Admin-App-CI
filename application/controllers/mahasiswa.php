<?php

class Mahasiswa extends CI_Controller{
    public function index(){
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data()->result();
        $this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('mahasiswa', $data);
		$this->load->view('templates/footer');
    }

    public function tambah(){
        $this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('mahasiswa');
		$this->load->view('templates/footer');
    }

    public function tambah_aksi(){
        $nama = $this->input->post('nama');
        $nim = $this->input->post('nim');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $jurusan = $this->input->post('jurusan');
        $alamat = $this->input->post('alamat');
        $email = $this->input->post('email');
        $no_telp = $this->input->post('no_telp');
        $foto = $_FILES['foto'];
        if ($foto=''){
    
        } else {
            $config['upload_path'] = './assets/foto';
            $config['allowed_types'] = 'jpg|png|gif';

            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('foto')){
                echo "Upload Gagal"; die();
            }
            else{
                $foto=$this->upload->data('file_name');
            }
        }




        $data = array(
            'nama'=> $nama,
            'nim'=> $nim,
            'tgl_lahir'=> $tgl_lahir,
            'jurusan'=> $jurusan,
            'alamat'=> $alamat,
            'email'=> $email,
            'no_telp'=> $no_telp,
            'foto'=> $foto
        );

        $this->m_mahasiswa->input_data($data, 'tb_mahasiswa');
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Data Berhasil Ditambahkan</div>');
        redirect('mahasiswa/index');
    }

    public function hapus ($id){
        $where = array ('id' => $id);
        $this->m_mahasiswa->hapus_data($where, 'tb_mahasiswa');
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Data Berhasil Dihapuskan</div>');
        redirect('mahasiswa/index');
    }

    public function edit ($id){
        $where = array('id' => $id);
        $data['mahasiswa'] = $this->m_mahasiswa->edit_data($where, 'tb_mahasiswa')->result();
        $this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('edit', $data);
		$this->load->view('templates/footer');

    }

    public function update (){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $nim = $this->input->post('nim');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $jurusan = $this->input->post('jurusan');
        $alamat = $this->input->post('alamat');
        $email = $this->input->post('email');
        $no_telp = $this->input->post('no_telp');

        $data = array (
            'nama' => $nama,
            'nim' => $nim,
            'tgl_lahir' => $tgl_lahir,
            'jurusan' => $jurusan,
            'alamat'=> $alamat,
            'email'=> $email,
            'no_telp'=> $no_telp
        );

        $where = array (
            'id' => $id
        );

        $this->m_mahasiswa->update_data($where, $data, "tb_mahasiswa");
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Data Berhasil Diedit</div>');
        redirect("mahasiswa/index");
    }

    public function detail($id){
        $this->load->model('m_mahasiswa');
        $detail = $this->m_mahasiswa->detail_data($id);
        $data['detail'] = $detail;

        $this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('detail', $data);
		$this->load->view('templates/footer');
    }

    public function print(){
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data('tb_mahasiswa')->result();
        $this->load->view('print_mahasiswa', $data);
    }

    public function pdf(){
        $this->load->library('dompdf_gen');

        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data('tb_mahasiswa')->result();
        $this->load->view('laporan_pdf', $data);

        $paper_size = 'A4';
        $orientation = 'landscape';
        $html= $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream('laporan_mahasiswa.pdf', array('Attachement' => 0));
    }

    public function excel(){
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data('tb_mahasiswa')->result();

        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $object = new PHPExcel();
        $object->getProperties()->setCreator("J-Senpai");
        $object->getProperties()->setLastModifiedBy("J-Senpai");
        $object->getProperties()->setTitle("Daftar Mahasiswa");

        $object->setActiveSheetIndex(0);
        $object->getActiveSheet()->setCellValue('A1', 'No');
        $object->getActiveSheet()->setCellValue('B1', 'Nama Mahasiswa');
        $object->getActiveSheet()->setCellValue('C1', 'NIM');
        $object->getActiveSheet()->setCellValue('D1', 'Tanggal Lahir');
        $object->getActiveSheet()->setCellValue('E1', 'Jurusan');
        $object->getActiveSheet()->setCellValue('F1', 'Alamat');
        $object->getActiveSheet()->setCellValue('G1', 'Email');
        $object->getActiveSheet()->setCellValue('H1', 'Nomor Telepon');

        $baris = 2;
        $no = 1;

        foreach ($data['mahasiswa'] as $mhs){
            $object->getActiveSheet()->setCellValue('A', $baris, $no++);
            $object->getActiveSheet()->setCellValue('B', $baris, $mhs->nama);
            $object->getActiveSheet()->setCellValue('C', $baris, $mhs->nim);
            $object->getActiveSheet()->setCellValue('D', $baris, $mhs->tgl_lahir);
            $object->getActiveSheet()->setCellValue('E', $baris, $mhs->jurusan);
            $object->getActiveSheet()->setCellValue('F', $baris, $mhs->alamat);
            $object->getActiveSheet()->setCellValue('G', $baris, $mhs->email);
            $object->getActiveSheet()->setCellValue('H', $baris, $mhs->no_telp);

            $baris++;
        }
        
        $filename="Data_Mahasiswa".'.xlsx';

        $object->getActiveSheet()->setTitle("Data Mahasiswa");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename=" '.$filename.'"');
        header('Cache Control: max-age=0');
        
        $writer=PHPExcel_IOFactory::createWriter($object, 'Excel2007');

        $writer->save('php://output');

        exit;
    }

    public function search(){
        $keyword = $this->input->post('keyword');
        $data['mahasiswa']=$this->m_mahasiswa->get_keyword($keyword);
        $this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('mahasiswa', $data);
		$this->load->view('templates/footer'); 
    }
}

?>