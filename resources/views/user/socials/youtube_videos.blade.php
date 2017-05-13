@extends('layouts.app')
@section('title', __('user.profile.socials.youtube.videos.see'))

@section('content')
  <div class="ui container page-content">
    <div class="ui info message">
      @lang('user.profile.socials.youtube.videos.refresh.infos')
      <button type="button" class="ui primary button" data-placement="left center" data-toggle="popup" data-content="{{ __('user.profile.socials.youtube.videos.remuneration.infos', ['date' => date('d/m/Y', strtotime('-7 days'))]) }}" data-placement="top" style="margin-top:-8px;float:right">@lang('user.profile.socials.youtube.videos.remuneration.infos.btn')</button>
    </div>

    @if (count($videos) === 0)
      <div class="ui error message">
        @lang('user.profile.socials.youtube.videos.empty')
      </div>
    @endif

    @foreach ($videos as $video)
      <div class="ui divider"></div>
      <div class="row">
        <div class="ui grid">
          <div class="five wide column">
            <a target="_blank" href="https://youtube.com/watch?v={{ $video->video_id }}"><img src="{{ $video->thumbnail_link }}" class="ui image rounded right aligned"></a>
          </div>
          <div class="eleven wide column">
            <p>
              <strong style="font-size:23px;">{{ $video->title }}</strong>
              @if ($video->eligible && !$video->payed)
                <a data-toggle="popup" data-placement="left center" data-content="{{ __('user.profile.socials.youtube.videos.remuneration.warning') }}" href="{{ url('/user/socials/youtube/videos/' . $video->id . '/remuneration') }}" style="float:right;color: #B03060;">
                  <em><u>@lang('user.profile.socials.youtube.videos.remuneration.btn')</u></em>
                </a>
              @endif
            </p>
            <div class="ui divider"></div>
            <div class="ui mini statistics">
              <div class="statistic">
                <div class="value">
                  <i class="eye icon"></i>
                  {{ $video->views_count }}
                </div>
                <div class="label">
                  @lang('user.profile.socials.youtube.videos.views')
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  <i class="thumbs up icon"></i>
                  {{ $video->likes_count }}
                </div>
                <div class="label">
                  @lang('user.profile.socials.youtube.videos.likes')
                </div>
              </div>
            </div>
            <br>
            <p><em>@lang('user.profile.socials.youtube.videos.date', ['date' => \Carbon\Carbon::parse($video->publication_date)->diffForHumans()])</em></p>
            @if ($video->eligible && !$video->payed)
              <span style="color:#2C9600" data-toggle="popup" data-placement="top center" data-content="{{ __('user.profile.socials.youtube.remuneration.alert.eligible', ['remuneration' => $video->remuneration]) }}"><i class="check icon"></i> @lang('user.profile.socials.youtube.remuneration.alert.title.eligible')</span>
            @elseif (!$video->payed)
              <span style="color:#B03060" data-toggle="popup" data-placement="top center" data-content="{{ __('user.profile.socials.youtube.remuneration.alert.non_eligible') }}"><i class="remove icon"></i> @lang('user.profile.socials.youtube.remuneration.alert.title.non_eligible')</span>
            @else
              <span style="color:#FE9A76" data-toggle="popup" data-placement="top center" data-content="{{ __('user.profile.socials.youtube.remuneration.alert.already') }}"><i class="remove icon"></i> @lang('user.profile.socials.youtube.remuneration.alert.title.already')</span>
            @endif
          </div>
        </div>
      </div>
    @endforeach

  </div>
@endsection
@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('[data-toggle="popup"]').each(function (k, el) {
        $(el).popup({
          html: $(el).attr('data-content'),
          position: $(el).attr('data-placement')
        })
      })
    })
  </script>
@endsection
