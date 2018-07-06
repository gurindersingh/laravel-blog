@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	<div class="container mt-5">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="h4 tt-u ls-16 ta-c">Page | Posts | Taxonomy</h1>
				<hr>
			</div>
		</div>
		
		
		<div class="row mb-10 pb-10 mt-10">
			
			<div class="col-lg-4 mb-4">
				<div class="card shadow hv:shadow-lg">
					<div class="card-body">
						<h5 class="card-title">Pages</h5>
						<p class="card-text">
							Pages - <strong>{{ $pagesCount }}</strong>
						</p>
						<div class="d-f jc-sb">
							<a href="{{ route('admin.pages.index') }}"
							   class="btn btn-sm btn-primary tt-u">View All</a>
							<a href="{{ route('admin.pages.create') }}"
							   class="btn btn-sm btn-success tt-u">Add New</a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-4 mb-4">
				<div class="card shadow hv:shadow-lg">
					<div class="card-body">
						<h5 class="card-title">Posts</h5>
						<p class="card-text">
							Posts - <strong>{{ $postsCount }}</strong>
						</p>
						<div class="d-f jc-sb">
							<a href="{{ route('admin.posts.index') }}"
							   class="btn btn-sm btn-primary tt-u">View All</a>
							<a href="{{ route('admin.posts.create') }}"
							   class="btn btn-sm btn-success tt-u">Add New</a>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="col-lg-4 mb-4">
				<div class="card shadow hv:shadow-lg">
					<div class="card-body">
						<h5 class="card-title">Taxonomy</h5>
						<p class="card-text">
							Categories - <strong>{{ $categoriesCount }}</strong> | Tags - <strong>{{ $tagsCount }}</strong>
						</p>
						<div class="d-f jc-c">
							<a href="{{ route('admin.taxonomies.index') }}" class="btn btn-sm btn-primary tt-u">Manage</a>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>

@endsection