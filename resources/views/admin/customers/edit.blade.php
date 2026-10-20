@extends('layouts.admin')
@section('page_title', 'Edit Customer ' . $customer->name)
@section('content')
<a href="{{ route('admin.customers.show', $customer) }}" class="text-sm text-gray-400 mb-4 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i>Back</a>
@include('admin.customers._form', ['customer' => $customer])
@endsection
