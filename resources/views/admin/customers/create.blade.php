@extends('layouts.admin')
@section('page_title', 'Add Customer')
@section('page_subtitle', 'Create a customer account on their behalf')
@section('content')
<a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-400 mb-4 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i>Back</a>
@include('admin.customers._form')
@endsection
