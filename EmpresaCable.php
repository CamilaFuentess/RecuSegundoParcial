<?php

class EmpresaCable{

    //atributos 
    private $colPlanes ; 
    private $colContratos; 

    //constructor 
    public function __construct($coleccionPlanes,$coleccionContratos )
    {
        $this->colPlanes = $coleccionPlanes; 
        $this->colContratos = $coleccionContratos;
    }

    //get 
    public function getColPlanes(){
        return $this->colPlanes ; 
    }
    public function getColContratos (){
        return $this->colContratos; 
    }

    //set 
    public function setColPlanes ($coleccionPlanes ){
        $this->colPlanes = $coleccionPlanes;
    }
    public function setColContratos ($coleccionContratos ){
        $this-> colContratos = $coleccionContratos;
    }

    //muestra las colecciones 
    public function mostrarColPlanes(){
        $coleccionPlanes = $this->getColPlanes() ; 
        $lista = "" ; 
        foreach ($coleccionPlanes as $unPlan){
            $lista = $lista . $unPlan . "\n" ; 
        }
        return $lista;
    }
    public function mostrarColContratos(){
        $coleccionContratos = $this->getColContratos() ; 
        $lista = "";
        foreach($coleccionContratos as $unContrato){
            $lista = $lista . $unContrato . "\n" ; 
        }
        return $lista;
    }
    //ToString
    public function __toString()
    {
        return "Planes:\n" . $this->mostrarColPlanes() . "\n" .
        "Contratos: \n"  . $this->mostrarColContratos() . "\n";
    }

    /**que incorpora a la colección de planes un nuevo plan siempre y cuando no haya
     * un plan con los mismos canales y los mismos MG (en caso de que el plan  incluyera) */
    public function incorporarPlan($objPlan){

        $coleccionPlanes = $this->getColPlanes(); 
        $i = 0;
        $planEncontrado = false ; 
        $canalesPlan = $objPlan->getColCanales(); //obtengo los canales del plan a incoroporar 
        $mgPlan = $objPlan->getIncluyeMG(); 
        //uso while para parar si encuentro un plan parecido 
        while ($i<count($coleccionPlanes) && !$planEncontrado){

            $planActual = $coleccionPlanes[$i]; //obtengo el plan ACtual 
            $colCanales = $planActual->getColCanales() ; //canales del plan actual 
            $mgPlanAtual = $coleccionPlanes[$i]->getIncluyeMG();

            //comparo que no tengan los mismos mg 
            if ($mgPlanAtual!=$mgPlan && $objPlan->getCodigo() != $planActual->getCodigo()){
                //comparo los canales dentro del plan actual con los de a incorporar 
                //foreach para recorrer y comparar todos los canales de mi arreglo 
                foreach ($canalesPlan as $unCanal){
                    foreach ($colCanales as $unCanalActual){
                        //si un canal de mi plan a incorporar es igual a uno del plan actaul 
                        if ($unCanal == $unCanalActual){
                            $planEncontrado = true; 
                        }
                    }
                }
            }
            $i++;
        }
        if (!$planEncontrado){
            $coleccionPlanes[] = $objPlan; 
            $this->setColPlanes($objPlan);
        }
        return $planEncontrado ; //para luego usarlo en el test 
    }

    /**método que recibe por parámetro el plan, una referencia al cliente, la fecha de
     * inicio y de vencimiento del mismo y si se trata de un contrato realizado en la
     * empresa o via web (si el valor del parámetro es True se trata de un contrato
     *  realizado via web) */
    public function incorporarContrato($objPlan,$objCliente,$fechaDesde,$fechaVenc, $esViaWeb){
        
        $i = 0 ; 
        $colPlanes = $this->getColPlanes(); 
        $encontrado = false; 
        //usado para verificar si existe el plan 
        while ($i<count($colPlanes) && !$encontrado){
            $codigoActual = $colPlanes[$i];
            if ($objPlan->getCodigo() == $codigoActual ){
                $encontrado = true; 
            }
            $i++;
        }
        if ($encontrado && $esViaWeb ){
            //el estado no lo agrego debido a que a penas se crea un contrato esta al dia 
            //el atributo se renueva puesto como null ya que no dice nada
            $nuevoContrato = new ContratoWeb($fechaDesde,$fechaVenc,$objPlan,0,null,$objCliente);
        } elseif ($encontrado ) {
            $nuevoContrato = new ContratoOficina($fechaDesde,$fechaVenc,$objPlan,0,null,$objCliente);
        } else {
            $nuevoContrato = null;
        }
        
        //al no tener costo de entrada de parametro lo calculo y lo seteo 
        $precioContrato = $nuevoContrato->calcularImporte(); 
        $nuevoContrato->setCosto($precioContrato);
         
        $coleccionContratos = $this->getColContratos(); 
        $coleccionContratos[] = $nuevoContrato ; 
        $this->setColContratos($coleccionContratos);
        return $nuevoContrato; //usado para el test
    }
    

    // método que recibe por parámetro el código de un plan y retorna la suma de los
    //importes de los contratos realizados usando ese plan
    public function retornarImporteContratos($codigoPlan){
        $contratos = $this->getColContratos() ; 
        $calculoImporte = 0;
        foreach($contratos as $unContrato){
            $objPlan = $unContrato->getObjPlan(); 
            if ($objPlan->getCodigo() == $codigoPlan){
                $calculoImporte += $unContrato->calcularImporte() ; 
            }
        }
        return $calculoImporte;
    }

    //recibe por parametro un contrato y actualiza el estado 
    public function pagarContrato($objContrato){ 

        $objContrato->actualizarEstadoContrato() ; 
        $estadoActual = $objContrato->getEstado() ; 
        $diasVencidos = $objContrato->diasContratoVencido(); 
        $costo = $objContrato->calcularImporte() ; 
        $fechaVencimiento = $objContrato->getFechaVencimiento(); 

        if ($estadoActual == "Al dia"){

            //actuakizp datos 
            $fechaVencimiento->date_modify('+1 month'); 
            $nuevaFecha = $fechaVencimiento->date_format('d-m-Y');
            $objContrato->setFechaVencimiento($nuevaFecha);
            $nuevoCosto = $costo; 
        } elseif ($estadoActual == "moroso"){
            //calculo la multa y la aplico al importe final del costo del contrato 
            $multa = $costo * 0.10 * $diasVencidos  ; //el costo del importe final por 0.10 haciendo referencia al 10% y multiplicandolo por los dias que esta vencdo el contrado 
            $nuevoCosto = $costo + $multa ; 
            $objContrato->setCosto($nuevoCosto);
            //actualizo los datos de estado y renuveo 
            $objContrato->actualizarEstadoContrato() ;
            $fechaVencimiento->date_modify('+1 month'); 
            $nuevaFecha = $fechaVencimiento->date_format('d-m-Y');
            $objContrato->setFechaVencimiento($nuevaFecha);

        } elseif ($estadoActual == "suspendido"){
            $multa = $costo * 0.10 * $diasVencidos; 
            $nuevoCosto = $costo * $multa;
            $objContrato->setCosto($nuevoCosto);
            //no actualizo datos 
            
        }
        return $nuevoCosto;
    }

}


?>