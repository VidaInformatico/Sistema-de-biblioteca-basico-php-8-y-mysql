<?php
class Configuracion extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
    }
    public function index()
    {
		$id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Configuracion");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
        $data = $this->model->selectConfiguracion();
        $this->views->getView($this, "index", $data);
    }
    public function actualizar()
    {
		$id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Configuracion");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
        $id = strClean($_POST['id']);
        $nombre = strClean($_POST['nombre']);
        $telefono = strClean($_POST['telefono']);
        $direccion = strClean($_POST['direccion']);
        $correo = strClean($_POST['correo']);
        $img = $_FILES['imagen'];
        $tmpName = $img['tmp_name'];
        if (empty($id) || empty($nombre) || empty($telefono) || empty($direccion) || empty($correo)) {
            $msg = array('msg' => 'Todo los campos son requeridos', 'icono' => 'warning');
        } else {
            $name = "logo.png";
            $destino = 'Assets/img/logo.png';
            $data = $this->model->actualizarConfig($nombre, $telefono, $direccion, $correo, $name, $id);
            if ($data == "modificado") {
                $msg = array('msg' => 'Datos de la empresa modificado', 'icono' => 'success');
                if (!empty($img['name'])) {
                    $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
                    $formatos_permitidos =  array('png', 'jpeg', 'jpg');
                    $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
                    if (!in_array($extension, $formatos_permitidos)) {
                        $msg = array('msg' => 'Archivo no permitido', 'icono' => 'warning');
                    }else{
                        move_uploaded_file($tmpName, $destino);
                    }
                }
            }
        }
        
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function admin()
    {
        $data['libros'] = $this->model->selectDatos('libro');
        $data['materias'] = $this->model->selectDatos('materia');
        $data['estudiantes'] = $this->model->selectDatos('estudiante');
        $data['autor'] = $this->model->selectDatos('autor');
        $data['editorial'] = $this->model->selectDatos('editorial');
        $data['prestamos'] = $this->model->selectDatos('prestamo');
        $data['usuarios'] = $this->model->selectDatos('usuarios');
        $this->views->getView($this, "home", $data);
    }
    public function grafico()
    {
        $data = $this->model->getReportes();
        echo json_encode($data);
        die();
    }
    public function error()
    {
        $this->views->getView($this, "error");
    }
    public function vacio()
    {
        $this->views->getView($this, "vacio");
    }
    public function verificar()
    {
        $date = date('Y-m-d');
        $data = $this->model->getVerificarPrestamos($date);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function libros()
    {
        $datos = $this->model->selectConfiguracion();
        $date = date('Y-m-d');
        $prestamo = $this->model->getVerificarPrestamos($date);
        if (empty($prestamo)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Prestamos");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url . "Assets/img/logo.png", 180, 10, 30, 30, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Detalle de Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(14, 5, utf8_decode('N°'), 1, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('Estudiantes'), 1, 0, 'L');
        $pdf->Cell(87, 5, 'Libros', 1, 0, 'L');
        $pdf->Cell(30, 5, 'Fecha Prestamo', 1, 0, 'L');
        $pdf->Cell(15, 5, 'Cant.', 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        foreach ($prestamo as $row) {
            $pdf->Cell(14, 5, $contador, 1, 0, 'L');
            $pdf->Cell(50, 5, $row['nombre'], 1, 0, 'L');
            $pdf->Cell(87, 5, utf8_decode($row['titulo']), 1, 0, 'L');
            $pdf->Cell(30, 5, $row['fecha_prestamo'], 1, 0, 'L');
            $pdf->Cell(15, 5, $row['cantidad'], 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
}
