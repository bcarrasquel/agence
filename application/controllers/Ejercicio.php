<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ejercicio extends CI_Controller {

	public function __construct() {
		header('Access-Control-Allow-Origin: *');
		parent::__construct();
        $this->load->model('Consultores_model');
    }

	public function index(){
		$result = $this->Consultores_model->getAllUsers();
		$this->load->view('inicio/header');
		$this->load->view('inicio/index', array('result' => $result));
	}

	public function resultRelatorio(){
		$result = $this->Consultores_model->getFactura($this->input->post());
		if (count($result) == 0){
            echo json_encode(array('estatus' => 500));
        }else{
			$usu = $this->Consultores_model->orderUsuarios($result);
			$fecha = $this->Consultores_model->orderFecha($usu);
			$totales = $this->Consultores_model->totales($fecha);
			$salario = $this->Consultores_model->getSalarios($this->input->post('valores'));
			$salario = $this->Consultores_model->armadoSalarios($salario, $totales);
			$vista = $this->load->view('resultados/relatorio', array("result" => $totales, "salario" => $salario), true);
			echo json_encode(array('html' => $vista, 'estatus' => 200));
        }
	}

	public function resultPizza(){
		$result = $this->Consultores_model->getFactura($this->input->post());
		if (count($result) == 0){
            echo json_encode(array('estatus' => 500));
        }else{
			$usu = $this->Consultores_model->orderUsuarios($result);
			$result = $this->Consultores_model->generateData($usu);
			$vista = $this->load->view('resultados/graficas', '', true);
			echo json_encode(array('html' => $vista, 'data' => $result, 'estatus' => 200));
		}
	}

	public function resultGrafico(){
		$result = $this->Consultores_model->getFactura($this->input->post());
		if (count($result) == 0){
            echo json_encode(array('estatus' => 500));
        }else{
			$usu = $this->Consultores_model->orderUsuarios($result);
			$result = $this->Consultores_model->generateData($usu, true);
			$fecha = $this->Consultores_model->orderFecha($usu);
			$salario = $this->Consultores_model->getSalarios($this->input->post('valores'));
			$promedio = $this->Consultores_model->barraPromedio($salario, $this->input->post());
			$vista = $this->load->view('resultados/graficas', '', true);
			echo json_encode(array('html' => $vista, 'data' => $result, 'promedio' => $promedio, 'estatus' => 200));
		}
	}
}
