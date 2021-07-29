@extends('layouts.app')
@section('content')
<div class="container">

<form action="{{ url('/empleado') }}" method="post" enctype="multipart/form-data">
@csrf <!--clave de seguridad / token-->
<!-- incluimos el formulario que esta eb form.blade.php-->
@include('empleado.form',['modo'=>'Crear'])
</form>
</div>
@endsection