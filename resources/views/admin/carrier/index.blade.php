@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.carriers') }}</h3>
      <div class="box-tools pull-right">
        @if (is_incevio_package_loaded('shippo'))
          <form action="{{ route('shippo.allcarriers') }}" method="POST" id="shippo-carriers-form"> @csrf</form>
          <a href="#" onclick="document.getElementById('shippo-carriers-form').submit();" class="btn btn-new btn-flat">Get Shippo Carriers</a>
        @endif
        @can('create', \App\Models\Carrier::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.shipping.carrier.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_carrier') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body responsive-table">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Carrier::class)
              <th class="massActionWrapper">
                <!-- Check all button -->
                <div class="btn-group ">
                  <button type="button" class="btn btn-xs btn-default checkbox-toggle">
                    <i class="fa fa-square-o" data-toggle="tooltip" data-placement="top" title="{{ trans('app.select_all') }}"></i>
                  </button>
                  <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">{{ trans('app.toggle_dropdown') }}</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.shipping.carrier.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.shipping.carrier.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.active') }}</th>
            <th>{{ trans('app.shipping_carrier_source') }}</th>
            <th>{{ trans('app.shipping_zones') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($carriers as $carrier)
            <tr>
              @can('massDelete', \App\Models\Carrier::class)
                <td><input id="{{ $carrier->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_logo_url($carrier, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
              </td>
              <td>
                {{ $carrier->name }}
              </td>
              <td>
                <span class="badge {{ $carrier->active ? 'bg-green' : 'bg-grey' }}">
                  {{ $carrier->active ? trans('app.active') : trans('app.inactive') }}
                </span>
              </td>
              <td>
                {{ $carrier->source ? ucwords($carrier->source) : trans('app.vendor') }}
              </td>
              <td>
                @foreach ($carrier->shippingZones->unique('name') as $zone)
                  <label class="label label-outline">{{ $zone->name }}</label>
                @endforeach
              </td>
              <td class="row-options">
                @can('view', $carrier)
                  <a href="javascript:void(0)" data-link="{{ route('admin.shipping.carrier.show', $carrier->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
                @endcan
                @can('update', $carrier)
                  <a href="javascript:void(0)" data-link="{{ route('admin.shipping.carrier.edit', $carrier->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan
                @can('delete', $carrier)
                  {!! Form::open(['route' => ['admin.shipping.carrier.trash', $carrier->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                  {!! Form::close() !!}
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->

  <div class="box collapsed-box">
    <div class="box-header with-border">
      <h3 class="box-title">
        @can('massDelete', \App\Models\Carrier::class)
          {!! Form::open(['route' => ['admin.shipping.carrier.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
          {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm btn btn-default btn-flat ajax-silent', 'title' => trans('help.empty_trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'right']) !!}
          {!! Form::close() !!}
        @else
          <i class="fa fa-trash-o"></i>
        @endcan
        {{ trans('app.trash') }}
      </h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body responsive-table">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <img src="{{ get_storage_file_url(optional($trash->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
              </td>
              <td>{{ $trash->name }}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.shipping.carrier.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.shipping.carrier.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.delete_permanently'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                  {!! Form::close() !!}
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
