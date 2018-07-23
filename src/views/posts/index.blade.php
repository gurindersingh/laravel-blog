@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	<div class="container mt-5">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="h4 tt-u ls-16 ta-c">Post</h1>
				<hr>
			</div>
		</div>
		
		
		<div class="row mt-5 mb-10 pb-10">
			
			<div class="col-lg-12 mb-4">
				<div class="btn-group" role="group" aria-label="Basic example">
					<a href="{{ route('admin.dashbaord') }}" class="btn py-1 px-2 label btn-outline-primary">Dashboard</a>
					<a href="{{ route('admin.posts.create') }}" class="btn py-1 px-2 label btn-outline-primary">Add Post</a>
				</div>
			</div>
			
			<div class="col-lg-12">
				<div class="table-responsive bgc-white">
					<table class="table table-bordered mb-0">
						<thead>
						<tr>
							<th scope="col" class="label">#ID</th>
							<th scope="col" class="label">Title</th>
							<th scope="col" class="label">Created</th>
							<th scope="col" class="label">Published</th>
							<th scope="col" class="label">Author</th>
						</tr>
						</thead>
						<tbody>
						@foreach($posts->items() as $post)
							<tr class="va-m">
								<th scope="row">{{ $post->id }}</th>
								<td>
									<div class="d-f ai-fs">
										<img src="{{ $media->image_social_url }}"
										class="img-thumbnail mr-2" style="width: 60px">
										
										<a href="{{ route('admin.posts.edit', $post->id) }}">
											<h3 class="h5 fxg-1">{{ $post->title }}</h3>
										</a>
									</div>
								</td>
								<td>{{ $post->created_at->diffForHumans() }}</td>
								<td>
									@if($post->published_at)
										<span class="badge badge-pill badge-success">published</span>
									@else
										--
									@endif
								</td>
								<td>{{ $post->author->name }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="col-lg-12">
				{{ $posts->links() }}
			</div>
		
		</div>
	
	</div>

@endsection