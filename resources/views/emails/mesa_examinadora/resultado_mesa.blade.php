@extends('layouts.emails.inicio')

@section('contenido')
    @if ($anexo2->estado->nombre == 'Aprobado')
        <p>
            Felicidades!! Finalizaste la carrera <b><i>Tecnicatura Universitaria en Programación</i></b>. Gracias por el esfuerzo que le has
            dedicado a la carrera todos estos años y te deseamos todos los éxitos para esta nueva etapa. 
        </p>
        <p>
            En el sitio Seminarios TUP se te habilitó una opción sobre tu proyecto para poder subir el código fuente de tu sistema si lo deseas, no es obligatorio. 
            La información sobre tu proyecto pasará a estar pública para cualquier persona que ingrese al sitio y quiera ver los trabajos finalizados.
        </p>
    @else
        <p>
            Lamentablemente no has aprobado el ultimo examen final de Seminario TUP. Podrás solicitar una nueva mesa examinadora mas adelante.
        </p>
    @endif
    <p>La devolución realizada sobre el trabajo es la siguiente : </p>
    <p>
        <b>Devolución: </b> {{$anexo2->devolucion}}. <br>
    </p>
@endsection
