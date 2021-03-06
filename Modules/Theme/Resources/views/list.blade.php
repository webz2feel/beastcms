@extends('Base::layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4><i class="icon-magic-wand"></i> {{ trans('Theme::theme.theme') }}</h4>
                </div>
                <div class="widget-body">
                    <div class="row pad">
                        @foreach(ThemeManager::getThemes() as $key =>  $theme)
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="thumbnail">
                                    <div class="img-thumbnail-wrap" style="background-image: url('{{ url(config('Theme.general.themeDir')) }}/{{ $key }}/screenshot.png')"></div>
                                    <div class="caption">
                                        <div class="col-12" style="background: #eee; padding: 15px;">
                                            <div style="word-break: break-all">
                                                <h4>{{ $theme['name'] }}</h4>
                                                <p>{{ trans('Theme::theme.author') }}: {{ Arr::get($theme, 'author') }}</p>
                                                <p>{{ trans('Theme::theme.version') }}: {{ Arr::get($theme, 'version') }}</p>
                                                <p>{{ trans('Theme::theme.description') }}: {{ Arr::get($theme, 'description') }}</p>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div>
                                                @if (setting('theme') == $key)
                                                    <a href="#" class="btn btn-info" disabled="disabled"><i class="fa fa-check"></i> {{ trans('Theme::theme.activated') }}</a>
                                                @else
                                                    @if (Auth::user()->hasPermission('theme.activate'))
                                                        <a href="#" class="btn btn-primary btn-trigger-active-theme" data-theme="{{ $key }}">{{ trans('Theme::theme.active') }}</a>
                                                    @endif
                                                    @if (Auth::user()->hasPermission('theme.remove'))
                                                        <a href="#" class="btn btn-danger btn-trigger-remove-theme" data-theme="{{ $key }}">{{ trans('Theme::theme.remove') }}</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::modalAction('remove-theme-modal', trans('Theme::theme.remove_theme'), 'danger', trans('Theme::theme.remove_theme_confirm_message'), 'confirm-remove-theme-button', trans('Theme::theme.remove_theme_confirm_yes')) !!}
@stop
