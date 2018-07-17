@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	<vue-manage-post :post-data="{{ isset($page) ? $page : 'null' }}"
	                 {{--medium-script-url="{{ url(mix('js/medium-editor.js')) }}"--}}
	                 file-upload-url="{{ route('admin.posts.content.images', '%post%') }}"
	                 post-rest-url="{{ route('admin.posts.index') }}"
	                 post-edit-url="{{ route('admin.pages.edit', '%post%') }}"
	                 data-post-type="page"></vue-manage-post>

@endsection

@push('append-styles')
	{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>--}}
	{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/medium-editor.min.css">--}}
	{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/themes/flat.css"/>--}}
	{{--<link rel="stylesheet"--}}
	      {{--href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.5.0/css/medium-editor-insert-plugin.css"/>--}}
@endpush