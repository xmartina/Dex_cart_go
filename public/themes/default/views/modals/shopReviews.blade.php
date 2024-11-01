<div class="modal fade" id="shopReviewsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 5px; right: 10px; z-index: 9; color: #eee;">&times;</button>
        <div class="box-widget widget-shop">
          <div class="widget-shop-header" style="background-image:url( {{ get_cover_img_src($shop, 'shop') }} );">
            <h2 class="widget-shop-name">
              {!! $shop->getQualifiedName() !!}
            </h2>

            <p class="member-since small">
              {{ trans('theme.member_since') }}: {{ $shop->created_at->diffForHumans() }}
            </p>
          </div> <!-- /.widget-shop-header -->

          <div class="widget-shop-image">
            <img src="{{ get_storage_file_url(optional($shop->logoImage)->path, 'small') }}" alt="{{ trans('theme.logo') }}">
          </div>

          <div class="row">
            <div class="col-sm-4 border-right">
              <div class="description-block">
                <h5 class="description-header">{{ $shop->inventories_count }}</h5>
                <span class="description-text">{{ trans('theme.active_listings') }}</span>
              </div>
            </div>

            <div class="col-sm-4 border-right">
              <div class="description-block">
                <h5 class="description-header">&nbsp;</h5>

                <span class="description-text small">
                  @include('theme::layouts.ratings', ['ratings' => $shop->feedbacks->avg('rating'), 'count' => $shop->feedbacks->count()])
                </span>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="description-block">
                <h5 class="description-header">{{ $shop->total_item_sold }}</h5>
                <span class="description-text">{{ trans('theme.items_sold') }}</span>
              </div>
            </div>
          </div> <!-- /.row -->
        </div> <!-- /.widget-shop -->

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs ml-4" role="tablist">
            <li class="active">
              <a href="#description_tab" data-toggle="tab">
                {{ trans('theme.description') }}
              </a>
            </li>

            <li>
              <a href="#merchant_tab" data-toggle="tab">
                {{ trans('theme.profile') }}
              </a>
            </li>

            @if ($shop->config->return_refund)
              <li>
                <a href="#refund_policy_tab" data-toggle="tab">
                  {{ trans('theme.return_and_refund_policy') }}
                </a>
              </li>
            @endif

            <li>
              <a href="#shop_reviews_tab" data-toggle="tab">
                {{ trans('theme.latest_reviews') }}
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="description_tab">
              {!! $shop->description !!}
            </div> <!-- /.tab-pane -->

            <div class="tab-pane" id="merchant_tab">
              <div class="row">
                <div class="col-sm-3">
                  <img src="{{ get_avatar_src($shop->owner, 'logo_square') }}" class="img-rounded">
                </div>
                <div class="col-sm-9">
                  {{ $shop->owner->name }}<br />
                  {{ $shop->address->toShortString() }}<br />
                  {{ $shop->config->support_phone }}<br />
                  {{ $shop->config->support_email }}<br />
                </div>
              </div> <!-- /.row -->
            </div> <!-- /.tab-pane -->

            <div class="tab-pane" id="refund_policy_tab">
              {!! $shop->config->return_refund !!}
            </div> <!-- /.tab-pane -->

            <div class="tab-pane" id="shop_reviews_tab">
              @forelse($shop->latestFeedbacks as $feedback)
                <p>
                  <b>{{ $feedback->customer->nice_name ?? $feedback->customer->name }}</b>

                  <span class="pull-right small">
                    <b class="text-success">@lang('theme.verified_purchase')</b>
                    <span class="text-muted"> | {{ $feedback->created_at->diffForHumans() }}</span>
                  </span>
                </p>

                <p>{{ $feedback->comment }}</p>

                @include('theme::layouts.ratings', ['ratings' => $feedback->rating, 'count' => $feedback->ratings_count])

                @unless ($loop->last)
                  <hr />
                @endunless
              @empty
                <p class="lead text-center text-muted mt-3">@lang('theme.no_reviews')</p>
              @endforelse
            </div> <!-- /.tab-pane -->
          </div> <!-- /.tab-content -->
        </div> <!-- /.nav-tabs-custom -->
      </div><!-- /.modal-body -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog modal-lg -->
</div><!-- /#shopReviewsModal -->
