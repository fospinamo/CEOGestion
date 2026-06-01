@extends('layouts.app')

@section('title', 'Nuevo Equipo')
@section('page-title', 'Registrar Nuevo Equipo')
@section('page-description', 'Agregar equipo a inventario')

@section('content')
    @include('parametros.equipos.form', [
        'equipo' => null,
        'marcas' => $marcas ?? [],
        'clientes' => $clientes ?? [],
        'sedes' => $sedes ?? [],
        'areas' => $areas ?? [],
        'empresas' => $empresas ?? [],
        'tipos' => $tipos ?? [],
        'contratos' => $contratos ?? []
    ])
@endsection
