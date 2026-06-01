@extends('layouts.app')

@section('title', 'Editar Equipo')
@section('page-title', 'Editar Equipo')
@section('page-description', 'Actualizar información del equipo TI')

@section('content')
    @include('parametros.equipos.form', [
        'equipo' => $equipo ?? null,
        'marcas' => $marcas ?? [],
        'clientes' => $clientes ?? [],
        'sedes' => $sedes ?? [],
        'areas' => $areas ?? [],
        'empresas' => $empresas ?? [],
        'tipos' => $tipos ?? [],
        'contratos' => $contratos ?? []
    ])
@endsection
