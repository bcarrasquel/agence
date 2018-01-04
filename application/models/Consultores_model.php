<?php
class Consultores_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAllUsers()
    {
        $this->db->select("*");
        $this->db->from('cao_usuario');
        $this->db->join('permissao_sistema', 'cao_usuario.co_usuario = permissao_sistema.co_usuario');
        $this->db->where('permissao_sistema.co_sistema', 1);
        $this->db->where('permissao_sistema.in_ativo', 'S');
        $this->db->where_in('permissao_sistema.co_tipo_usuario', array(0, 1, 2));
        return $this->db->get()->result_array();
    }

    public function orderUsuarios($data){
        $ordenado = array();
        foreach ($data as $key => $value) {
            $ordenado[$value["no_usuario"]][$key] = $value;
        }
        return $ordenado;
    }

    public function getFactura($valores){
        $fechaIni = $this->changeDate($valores["valores"]["fechaInicio"]);
        $fechaFin = $this->changeDate($valores["valores"]["fechaFin"], false);
        $this->db->select(array('cao_usuario.no_usuario', 'cao_os.*', 'cao_fatura.*'));
        $this->db->from("cao_os");
        $this->db->join('cao_fatura', 'cao_os.co_os = cao_fatura.co_os', 'left');
        $this->db->join('cao_usuario', 'cao_os.co_usuario = cao_usuario.co_usuario');
        $this->db->where('cao_fatura.data_emissao >=', $fechaIni);
        $this->db->where('cao_fatura.data_emissao <=', $fechaFin);
        $this->db->where_in('cao_os.co_usuario', $valores["valores"]["nombres"]);
        $this->db->order_by('data_emissao');
        return $this->db->get()->result_array();
    }

    public function getSalarios($usuarios){
        $this->db->select(array('cao_salario.brut_salario', 'cao_usuario.no_usuario'));
        $this->db->from("cao_salario");
        $this->db->join('cao_usuario', 'cao_salario.co_usuario = cao_usuario.co_usuario');
        $this->db->where_in('cao_salario.co_usuario', $usuarios["nombres"]);
        return $this->db->get()->result_array();
    }

    private function changeDate($date, $ini=true){
        $fecha = explode("-", $date);
        $date = new DateTime($fecha[1]."-".$fecha[0]);
        if ($ini) {
            return $date->format('Y-m-d');
        }
        return $date->format('Y-m-t');
    }

    private function meses($mes){
        $meses = array('Enero', 'Febrero','Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
                       'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        return $meses[$mes -1];
    }

    public function orderFecha($usu){
        $armado = array();
        foreach ($usu as $llave => $valor) {
            $ordenado = array();
            foreach ($valor as $key => $value) {
                $fecha = date_parse_from_format("Y-m-d",$value["data_emissao"]);
                $mes = $this->meses($fecha["month"]);
                $ordenado[$mes.' de '.$fecha["year"]][$key] = $value;
            }
            $armado[$llave] = $ordenado;
        }
        return $armado;
    }

    public function totales($todo){
        $armado = array();
        foreach ($todo as $llave => $valor) {
            $receita = 0;
            $comissao = 0;
            foreach ($valor as $key => $value) {
                $resultadoFinal = $this->calcularTabla($value);
                $armado[$llave][$key] = array("mes" => $key,
                                              "receita" => $resultadoFinal[0],
                                              "comissao" => $resultadoFinal[1]);
            }

        }
        return $armado;
    }

    private function calcularTabla($valores){
        $receita = 0;
        $comissao = 0;
        foreach ($valores as $key => $value) {
            $receita += $value["valor"] - (($value["valor"] * $value["total_imp_inc"]) / 100);
            $comissao += (($value["valor"] - (($value["valor"] * $value["total_imp_inc"]) / 100)) * $value["comissao_cn"]) / 100;
        }
        return array($receita, $comissao);
    }

    public function armadoSalarios($salarios, $totales){
        $result = array();
        foreach ($totales as $key => $value) {
            $result[$key] = 0;
        }
        foreach ($salarios as $key => $value) {
            $result[$value["no_usuario"]] = $value["brut_salario"];
        }
        return $result;
    }

    public function generateData($data, $otro=false){
        $result = array();
        $total = 0;
        $promedio = 0;
        foreach ($data as $llave => $valor) {
            $promedio = 0;
            foreach ($valor as $key => $value) {
                $promedio += $value["valor"] - (($value["valor"] * $value["total_imp_inc"]) / 100);
            }
            $result[$llave] = $promedio;
            $total += $promedio;
        }
        if ($otro){
            return $result;
        }
        return $this->generateAverage($result, $total);
    }

    private function generateAverage($result, $total){
        foreach ($result as $key => $value) {
            $result[$key] = ($result[$key] * 100) / $total;
        }
        return $result;

    }

    public function barraPromedio($data, $post){
        $fechaIni = $this->changeDate($post["valores"]["fechaInicio"]);
        $fechaFin = $this->changeDate($post["valores"]["fechaFin"], false);
        $i = 0;
        while ($fechaIni <= $fechaFin) {
            $fechaIni = date('Y-m-d', strtotime("+1 months", strtotime($fechaIni)));
            $i += 1;
        }
        $promedio = 0;
        foreach ($data as $key => $value) {
            $promedio += $value["brut_salario"] * $i;

        }
        return $promedio / count($data);;
    }
}
?>