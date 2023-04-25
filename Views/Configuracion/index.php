<?php include "Views/Templates/header.php"; ?>
<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> Datos de la Empresa</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="tile">
            <div class="tile-body">
                <form id="frmConfig">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="nombre"><i class="fa fa-address-card" aria-hidden="true"></i> Nombre</label>
                                <input id="id" type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                <input id="nombre" class="form-control" type="text" name="nombre" value="<?php echo $data['nombre']; ?>" required placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="telefono"><i class="fa fa-phone-square" aria-hidden="true"></i> Teléfono</label>
                                <input id="telefono" class="form-control" type="text" name="telefono" value="<?php echo $data['telefono']; ?>" required placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="direccion"><i class="fa fa-home" aria-hidden="true"></i> Dirección</label>
                                <input id="direccion" class="form-control" type="text" name="direccion" value="<?php echo $data['direccion']; ?>" required placeholder="Dirección">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="correo"><i class="fa fa-envelope" aria-hidden="true"></i> Correo Electrónico</label>
                                <input id="correo" class="form-control" type="text" name="correo" value="<?php echo $data['correo']; ?>" required placeholder="Correo electrónico">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-picture-o" aria-hidden="true"></i> Logo</label>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <input type="hidden" id="foto_actual">
                                        <label for="imagen" id="icon-image" class="btn btn-primary"><i class="fa fa-cloud-upload"></i></label>
                                        <span id="icon-cerrar"></span>
                                        <input id="imagen" class="d-none" type="file" name="imagen" onchange="preview(event)">
                                        <img class="img-thumbnail" id="img-preview" src="<?php echo base_url; ?>Assets/img/<?php echo $data['foto']; ?>" width="200">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" onclick="frmConfig(event)">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>