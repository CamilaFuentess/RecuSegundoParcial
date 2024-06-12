<?php
/*
 
Adquirir un plan implica un contrato. Los contratos tienen la fecha de inicio, la fecha de vencimiento, el plan, un estado (al día, moroso, suspendido), un costo, si se renueva o no y una referencia al cliente que adquirió el contrato.
*/
class Contrato{
    
    //ATRIBUTOS
    private $fechaInicio;   //puesto como 
    private $fechaVencimiento;
    private $objPlan;
    private $estado;  //al día, moroso, suspendido
    private $costo;
    private $seRennueva;
    private $objCliente;

 //CONSTRUCTOR
    public function __construct($fechaInicio, $fechaVencimiento, $objPlan,$costo,$seRennueva,$objCliente){
    
       $this->fechaInicio = $fechaInicio;
       $this->fechaVencimiento = $fechaVencimiento;
       $this->objPlan = $objPlan;
       $this->estado = 'AL DIA';
       $this->costo = $costo;
       $this->seRennueva = $seRennueva;
       $this->objCliente = $objCliente;
           

    }


         public function getFechaInicio(){
        return $this->fechaInicio;
    }

    public function setFechaInicio($fechaInicio){
         $this->fechaInicio= $fechaInicio;
    }

        public function getFechaVencimiento(){
        return $this->fechaVencimiento;
    }

    public function setFechaVencimiento($fechaVencimiento){
         $this->fechaVencimiento= $fechaVencimiento;
    }


            public function getObjPlan(){
        return $this->objPlan;
    }

    public function setObjPlan($objPlan){
         $this->objPlan= $objPlan;
    }

   public function getEstado(){
        return $this->estado;
    }

    public function setEstado($estado){
         $this->estado= $estado;
    }

 public function getCosto(){
        return $this->costo;
    }

    public function setCosto($costo){
         $this->costo= $costo;
    }

public function getSeRennueva(){
        return $this->seRennueva;
    }

    public function setSeRennueva($seRennueva){
         $this->seRennueva= $seRennueva;
    }


public function getObjCliente(){
        return $this->objCliente;
    }

    public function setObjCliente($objCliente){
         $this->objCliente= $objCliente;
    }

public function __toString(){
        //string $cadena
        $cadena = "Fecha inicio: ".$this->getFechaInicio()."\n";
        $cadena = "Fecha Vencimiento: ".$this->getFechaVencimiento()."\n";
        $cadena = $cadena. "Plan: ".$this->getObjPlan()."\n";
        $cadena = $cadena. "Estado: ".$this->getEstado()."\n";
        $cadena = $cadena. "Costo: ".$this->getCosto()."\n";
        $cadena = $cadena. "Se renueva: ".$this->getSeRennueva()."\n";
        $cadena = $cadena. "Cliente: ".$this->getObjCliente()."\n";

 
        return $cadena;
     }




     //. El importe final de un contrato realizado en la empresa se calcula sobre el importe
     //del plan mas los importes parciales de cada uno de los canales que lo forman
     public function calcularImporte(){
          $objPlan = $this->getObjPlan(); 
          $costoPlan = $objPlan->getImporte();  //obtengo el importe del plan 
          $colCanales = $objPlan->getColCanales();  //obtengo la col de canales del plan para sumar los importes parciales de cada uno al importe final 
          $costoCanales = 0;
          foreach($colCanales as $unCanalPlan){
               $costoCanales = $costoCanales + $unCanalPlan->getImporte();
          }
          $importeFinal = $costoPlan + $costoCanales;
          //seteo el costo final 
          $this->setCosto($importeFinal);
          return $importeFinal;
     }

     /**teniendo en cuenta la fecha actual y la fecha de fin del contrato, calcular la
      * cantidad de días que el contrato lleva vencido o 0 en caso contrario. */
     public function diasContratoVencido(){
          $fechaActual = new DateTime() ; //obtengo la fehca actual 
          $fechaFin = $this->getFechaVencimiento();  
          $tiempoFaltante = $fechaActual->diff($fechaFin);
          if ($fechaActual<$fechaFin){
               $tiempoFaltante = 0 ;
          }
          return $tiempoFaltante;
     }

     //actualiza el estado del contrato según corresponda, usar diasContratoVencido()
     public function actualizarEstadoContrato(){
          $estadoActual = null; 
          $diasFaltantes = $this->diasContratoVencido(); //tengo los dias vencidos 
          if ($diasFaltantes > 10){
               $estadoActual = "Suspendido" ; 
          } else if($diasFaltantes>0 && $diasFaltantes<10){
               $estadoActual = "Moroso";
          } else {
               $estadoActual = "Al dia";
          }
          $this->setEstado($estadoActual); 
     }

     


}    