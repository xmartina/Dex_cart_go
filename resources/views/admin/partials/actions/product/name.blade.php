{{ $product->name }}

@unless ($product->active)
  <span class="label label-default indent10">{{ trans('app.inactive') }}</span>
@endunless

@if (is_incevio_package_loaded('inspector') && $product->inspection_status && $product->inInspection())
  <br />
  {!! trans('packages.inspector.inspection') . ': ' . $product->getInspectionStatus() !!}
@endif
