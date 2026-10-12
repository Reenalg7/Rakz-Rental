@extends('layouts.admin')
@section('page_title', 'Edit Vehicle ' . $vehicle->registration_no)
@section('content') @include('admin.vehicles._form', ['categories' => $categories, 'vehicle' => $vehicle]) @endsection
