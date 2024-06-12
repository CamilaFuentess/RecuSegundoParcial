<?php 

class ContratoWeb extends Contrato {
    //atributos 
    private $porDescuento; 

    //constructor 
    public function __construct($fechaInicio, $fechaVencimiento, $objPlan,$costo,$seRennueva,$objCliente )
    {
        parent::__construct($fechaInicio, $fechaVencimiento, $objPlan,$costo,$seRennueva,$objCliente) ;
        $this->porDescuento = 10;
    }

    //get 
    public function getPorDescuento (){
        return $this->porDescuento; 
    }

    //set 
    public function setPorDescuento ($descuento){
        $this->porDescuento = $descuento;
    }

    //ToString
    public function __toString()
    {
        $cadenaPadre = parent::__toString();
        return $cadenaPadre .
        "Descuento del: "  . $this->getPorDescuento() . "%\n" ; 
    }

    public function calcularImporte()
    {
        //segun entendi hereda el importe  del plan mas los importes parciales de cada uno de los canales de su clase padre y le aplica el descuento
        $importeBase = parent::calcularImporte() ; 
        $descuento = $this->getPorDescuento() ; 
        $reducir = $importeBase * ($descuento/100);
        $importeFinal = $importeBase - $reducir ;
        $this->setCosto($importeFinal);
        return $importeFinal;
    }


}