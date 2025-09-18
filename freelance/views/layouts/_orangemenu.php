<div class="minimenu">
    <div class="card">
<a href="index.php?r=empresa%2Fcreate" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'empresa/create') ? 'active' : '' ?>">            <p class="mb-0">Empresa <i class="font-24 bx bx-store-alt"></i></p>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=consecutivo" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'consecutivo') ? 'active' : '' ?>">
            <div class="d-flex align-items-center justify-content-center">
                <div>
                    <p class="mb-0">Consecutivos <i class="font-24 bx bx-right-indent"></i></p>
                </div>
            </div>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=iva" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'iva') ? 'active' : '' ?>">
            <p class="mb-0">IVA <i class="font-24 bx bx-calculator"></i></p>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=concepto-facturacion" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'concepto-facturacion') ? 'active' : '' ?>">
            <p class="mb-0">Conceptos facturación <i class="font-24 bx bx-task"></i></p>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=concepto-liquidacion" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'concepto-liquidacion') ? 'active' : '' ?>">
            <p class="mb-0">Conceptos liquidación <i class="font-24 bx bx-terminal"></i></p>
        </a>
    </div>
    <div class="card">
       <a href="index.php?r=banco" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'banco') ? 'active' : '' ?>">
    <p class="mb-0">Banco <i class="font-24 bx bx-building-house <?= (isset($_GET['r']) && $_GET['r'] === 'banco') ? 'text-orange' : '' ?>"></i></p>
</a>
    </div>
    <div class="card">
        <a href="index.php?r=forma-de-pago" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'forma-de-pago') ? 'active' : '' ?>">
            <p class="mb-0">Formas de pago <i class="font-24 bx bx-dollar"></i></p>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=tipo-doc-identidad" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'tipo-doc-identidad') ? 'active' : '' ?>">
            <p class="mb-0">Tipos ID <i class="font-24 bx bx-id-card"></i></p>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=categoria" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'categoria') ? 'active' : '' ?>">
            <p class="mb-0">Categorías profesionales <i class="font-24 bx bx-grid-alt"></i></p>
        </a>
    </div>
    <div class="card">
        <a href="index.php?r=provincia" class="card-body <?= (isset($_GET['r']) && $_GET['r'] === 'provincia') ? 'active' : '' ?>">
            <p class="mb-0">País y provincia <i class="font-24 bx bx-flag"></i></p>
        </a>
    </div>
	
</div>