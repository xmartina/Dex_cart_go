@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.category_sub_group') . ' : ' }}{{ $categorySubGroup->name . ' | ' }}{{ trans('app.categories') }}</h3>
    </div> <!-- /.box-header -->
    <div class="box-body responsive-table">
      <table class="table table-hover">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Category::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.catalog.category.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.catalog.category.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @else
              <th>&nbsp;</th>
            @endcan
            <th>{{ trans('app.cover_image') }}</th>
            <th>{{ trans('app.feature_image') }}</th>
            <th>{{ trans('app.category_name') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
            <tr>
              @can('massDelete', \App\Models\Category::class)
                <td>
                  @unless ($category->products_count)
                    <input id="{{ $category->id }}" type="checkbox" class="massCheck">
                  @endunless
                </td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($category->coverImage)->path, 'cover_thumb') }}" class="img-sm" alt="{{ trans('app.cover_image') }}">
              </td>
              <td>
                <img src="{{ get_storage_file_url(optional($category->featureImage)->path, 'cover_thumb') }}" class="img-sm" alt="{{ trans('app.feature_image') }}">
              </td>
              <td>
                {{ $category->name }}
              </td>
              <td class="row-options">
                @can('update', $category)
                  <a href="javascript:void(0)" data-link="{{ route('admin.catalog.category.edit', $category->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                  <a href="{{ route('admin.catalog.category.translate.form', ['category' => $category, 'language' => app()->getLocale()]) }}"><em data-toggle="tooltip" data-placement="top" title="{{ trans('app.manage_translations') }}" class="fa fa-language"></em></a>
                @endcan

                @can('delete', $category)
                  {!! Form::open(['route' => ['admin.catalog.category.trash', $category->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Category::class)
          {!! Form::open(['route' => ['admin.catalog.category.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
      <table class="table table-hover table-option">
        <thead>
          <tr>
            <th>{{ trans('app.category_name') }}</th>
            <th>{{ trans('app.parent') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <h5>{{ $trash->name }}</h5>
                @if ($trash->description)
                  <span class="excerpt-td small">{!! Str::limit($trash->description, 150) !!}</span>
                @endif
              </td>
              <td>
                @if ($trash->subGroup->group->deleted_at)
                  <i class="fa fa-trash-o small"></i>
                @endif
                {!! $trash->subGroup->group->name !!}
                &nbsp;<i class="fa fa-angle-double-right small"></i>&nbsp;
                @if ($trash->subGroup->deleted_at)
                  <i class="fa fa-trash-o small"></i>
                @endif
                {!! $trash->subGroup->name !!}
              </td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.catalog.category.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.catalog.category.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
