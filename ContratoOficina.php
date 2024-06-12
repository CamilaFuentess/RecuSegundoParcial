<?php 

class ContratoOficina extends Contrato {


    //constructor 
    public function __construct($fechaInicio, $fechaVencimiento, $objPlan,$costo,$seRennueva,$objCliente )
    {
        parent::__construct($fechaInicio, $fechaVencimiento, $objPlan,$costo,$seRennueva,$objCliente) ;
    }


    //ToString
    public function __toString()
    {
        $cadenaPadre = parent::__toString();
        return $cadenaPadre . "\n" ; 
    }

    //al ser por oficina no se le realiza ninugn cambio 
    public function calcularImporte()
    {
        $importe = parent::calcularImporte() ; 
        return $importe;
    }
    
}