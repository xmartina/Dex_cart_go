@extends('admin.layouts.master')

@section('content')
  {!! Form::open(['route' => 'admin.stock.product.store', 'files' => true, 'id' => 'form-ajax-upload', 'data-toggle' => 'validator']) !!}

  @include('admin.product.inventory._form')

  {!! Form::close() !!}
@endsection

@section('page-script')
  @include('plugins.dropzone-upload')
  @include('plugins.dynamic-inputs')
  @include('scripts.variants')
@endsection
