@extends('layouts.admin')
@section('page_title', 'Add Vehicle')
@section('content') @include('admin.vehicles._form', ['categories' => $categories]) @endsection
