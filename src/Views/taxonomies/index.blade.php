@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	
	<section class="my-5">
		<div class="container">
			
			<div class="row">
				<div class="col-lg-12">
					<h1 class="tt-u ls-16 fsz-lg ta-c">Manage Taxonomies</h1>
					<hr>
				</div>
			</div>
			
			<div class="row">
				
				<div class="col-lg-12">
					<vue-manage-taxonomies :data-categories="{{ $categories }}"
					                       :data-tags="{{ $tags }}"
					                       taxonomy-rest-url="{{ route('admin.taxonomies.index') }}"></vue-manage-taxonomies>
				</div>
			
			</div>
		</div>
	</section>


@endsection