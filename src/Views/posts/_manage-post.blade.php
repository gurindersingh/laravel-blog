@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	<vue-manage-post medium-script-url="{{ url(mix('js/medium-editor.js')) }}"
	                 :post-data="{{ isset($post) ? $post : 'null' }}"
	                 :categories="{{ $categories }}"
	                 tag-rest-url="{{ route('admin.tags.index') }}"
	                 file-upload-url="{{ route('admin.posts.content.images', '%post%') }}"
	                 post-edit-url="{{ route('admin.posts.edit', '%post%') }}"
	                 post-rest-url="{{ route('admin.posts.index') }}"
	                 data-post-type="post"></vue-manage-post>

@endsection

@push('append-styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/medium-editor.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/themes/flat.css"/>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.5.0/css/medium-editor-insert-plugin.css"/>
@endpush