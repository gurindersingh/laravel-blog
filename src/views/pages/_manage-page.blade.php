@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	<vue-manage-post :post-data="{{ isset($page) ? $page : 'null' }}"
	                 file-upload-url="{{ route('admin.posts.content.images', '%post%') }}"
	                 post-rest-url="{{ route('admin.posts.index') }}"
	                 post-edit-url="{{ route('admin.pages.edit', '%post%') }}"
	                 data-post-type="page"></vue-manage-post>

@endsection