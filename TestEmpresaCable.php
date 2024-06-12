<?php 


include_once 'Cliente.php' ; 
include_once 'Canal.php';
include_once 'Plan.php'; 
include_once 'Contrato.php'; 
include_once 'ContratoWeb.php'; 
include_once 'ContratoOficina.php'; 
include_once 'EmpresaCable.php'; 

//TEST EMPRESA CABLE 

//a) Se crea 1 instancia de la clase Empresa_Cable
$objEmpresa = new EmpresaCable([],[]);

//b) Se crean 3 instancias de la clase Canal.
$objCanal1 = new Canal("noticias",200,true);
$objCanal2 = new Canal("infantil",100,false);
$objCanal3 = new Canal("deportivo",230,true);

//c) Se crean 2 instancias de la clase Planes, cada una de ellas con su código propio que hacen 
//referencia a los canales creados anteriormente (uno de los códigos de plan debe ser 111).
$objPlan1 = new Plan(111,[$objCanal1,$objCanal3],1200);
$objPlan2 = new Plan(123,[$objCanal2],200);

//d) Crear una instancia de la clase Cliente
$objCliente = new Cliente("Perez",1234,'Al dia');

//e)Se crean 3 instancias de Contratos, 1 correspondiente a un contrato realizado en la empresa y 2 
//realizados via web

$objContrato1 = new ContratoOficina("06-10-2024", "06-10-2025",$objPlan1,0,false,$objCliente) ; 
$objContrato2 = new ContratoWeb("05-05-2024","05-05-2024",$objPlan2,500,true,$objCliente) ; 
$objContrato3 = new ContratoWeb("10-06-2024","10-07-2024",$objPlan1,400,false,$objCliente);


echo "\nf) Invocar con cada instancia del inciso anterior al método calcularImporte y visualizar el resultado.\n";
$importe1 = $objContrato1->calcularImporte(); 
echo "Importe del contrato 1: " . $importe1. "\n" ; 
echo "\n";
$importe2 = $objContrato2->calcularImporte() ; 
echo "Importe del contrato 2: " . $importe2. "\n" ; 
echo "\n";
$importe3 = $objContrato3->calcularImporte(); 
echo "Importe del contrato 1: " . $importe3. "\n" ; 
echo "\n";


echo "\ng) Invocar al método incorporaPlan con uno de los planes creados en c).\n";
$listo = $objEmpresa->incorporarPlan($objPlan1);
if (!$listo){
    echo "Plan incorporado"; 
} else {
    echo "El plan no pudo ser incorporado";
}

echo "\n";
echo "\nh) Invocar nuevamente al método incorporaPlan de la empresa con el plan creado en c)\n" ; 
$listo = $objEmpresa->incorporarPlan($objPlan1);
if (!$listo){
    echo "Plan incorporado"; 
} else {
    echo "El plan no pudo ser incorporado";
}
//si solo tengo un plan me lanza error debido al count. No alcance a corregir eso por el tiempo 
//$objEmpresa->setColPlanes([$objPlan1,$objPlan2]);
echo "\n";
echo "\ni) Invocar al método incorporarContrato con los siguientes parámetros: uno de los planes creado en c), 
el cliente creado en e), la fecha de hoy para indicar el inicio del contrato, la fecha de hoy más 30 días 
para indicar el vencimiento del mismo y false como último parámetro.\n";
$fechaActual = date("d-m-Y") ;
$incorporar = $objEmpresa->incorporarContrato($objPlan1,$objCliente, $fechaActual, "12-07-2024", false);
if ($incorporar!=null){
    echo "Contratdo incorporado"; 
} else {
    echo "No se pudo incorporar elc ontrato";
}
echo "\n";
echo "\nj) Invocar al método incorporarContrato con los siguientes parámetros: uno de los planes creado en c), 
el cliente creado en e), la fecha de hoy para indicar el inicio del contrato, la fecha de hoy más 30 días 
para indicar el vencimiento del mismo y true como último parámetro\n"; 
$fechaActual = date("d-m-Y") ;
$incorporar2 = $objEmpresa->incorporarContrato($objPlan1,$objCliente, $fechaActual, "12-07-2024", true);
if ($incorporar2!=null){
    echo "Contratdo incorporado"; 
} else {
    echo "No se pudo incorporar elc ontrato";
}
echo "\n";
echo "\nk) Invocar al método pagarContrato que recibe como parámetro uno de los contratos creados en d) y 
que haya sido contratado en la empresa.\n ";
$aux = $objEmpresa->pagarContrato($objContrato1);
echo "El monto a pagar es: " . $aux . "\n";
echo "\n";

echo "\nl) Invocar al método pagarContrato que recibe como parámetro uno de los contratos creados en d) y 
que haya sido contratado vía web\n ";
$aux2 = $objEmpresa->pagarContrato($objContrato2);
echo "El monto a pagar es: " . $aux2 . "\n";
echo "\n";

echo "\nm) invoca al método retornarImporteContratos con el código 111\n";
$importe = $objEmpresa->retornarImporteContratos(111); 
echo "El retorno de importe contrads es de: " . $importe. "\n";
?>