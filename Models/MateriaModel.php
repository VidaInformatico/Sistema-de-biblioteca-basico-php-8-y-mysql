<?php
class MateriaModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getMaterias()
    {
        $sql = "SELECT * FROM materia";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function insertarMateria($materia)
    {
        $verificar = "SELECT * FROM materia WHERE materia = '$materia'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO materia(materia) VALUES (?)";
            $datos = array($materia);
            $data = $this->save($query, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }
    public function editMateria($id)
    {
        $sql = "SELECT * FROM materia WHERE id = $id";
        $res = $this->select($sql);
        return $res;
    }
    public function actualizarMateria($materia, $id)
    {
        $query = "UPDATE materia SET materia = ? WHERE id = ?";
        $datos = array($materia, $id);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function estadoMateria($estado, $id)
    {
        $query = "UPDATE materia SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($query, $datos);
        return $data;
    }
    public function verificarPermisos($id_user, $permiso)
    {
        $tiene = false;
        $sql = "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'";
        $existe = $this->select($sql);
        if ($existe != null || $existe != "") {
            $tiene = true;
        }
        return $tiene;
    }
    public function buscarMateria($valor)
    {
        $sql = "SELECT id, materia AS text FROM materia WHERE materia LIKE '%" . $valor . "%'  AND estado = 1 LIMIT 10";
        $data = $this->selectAll($sql);
        return $data;
    }
}
