@extends('layouts.app.master')

@section('header-class', ' dark')

@section('content')
	
	<div class="container mt-5">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="h4 tt-u ls-16 ta-c">Pages</h1>
				<hr>
			</div>
		</div>
		
		<div class="row mt-5 mb-10 pb-10">
			
			<div class="col-lg-12 mb-4">
				<div class="d-f jc-sb">
					<div class="">
						<div class="btn-group" role="group" aria-label="Basic example">
							<a href="{{ route('admin.dashbaord') }}" class="btn py-1 px-2 label btn-outline-primary hv:c-white fc:c-white">Dashboard</a>
							<a href="{{ route('admin.pages.create') }}" class="btn py-1 px-2 label btn-outline-primary hv:c-white fc:c-white">Add Page</a>
							
							@if($searchingFor)
								<a href="{{ route('admin.pages.index') }}" class="btn py-1 px-2 label btn-outline-primary hv:c-white fc:c-white">All pages</a>
							@endif
							
						</div>
					</div>
					<form action="{{ route('admin.pages.search') }}" method="POST">
						{{ csrf_field() }}
						<div class="d-f">
							<input name="search" type="search" class="form-control form-control-sm" placeholder="Search...">
							<button class="btn btn-primary btn-sm ml-2 c-white tt-u">Search</button>
						</div>
					</form>
				</div>
			</div>
			
			<div class="col-lg-12">
				@if($searchingFor)
					<p class="mb-1">{!! $searchingFor !!}</p>
				@endif
				<div class="table-responsive bgc-white">
					<table class="table table-bordered mb-0">
						<thead>
						<tr>
							<th scope="col" class="label">#ID</th>
							<th scope="col" class="label">Title</th>
							<th scope="col" class="label">Created</th>
							<th scope="col" class="label">Published</th>
						</tr>
						</thead>
						<tbody>
						@foreach($pages->items() as $page)
							<tr>
								<th scope="row" class="align-middle">{{ $page->id }}</th>
								<td>
									<div class="d-f ai-c">
										
										@if($page->featuredImage)
											<img src="{{ $page->featuredImage->image_social_url }}"
											     class="img-thumbnail mr-2" style="width: 60px">
										@endif
										
										<a href="{{ route('admin.pages.edit', $page->id) }}">
											<h3 class="h5 fxg-1">{{ $page->title }}</h3>
										</a>
									</div>
								</td>
								<td>{{ $page->created_at->diffForHumans() }}</td>
								<td>
									@if($page->published_at)
										<span class="badge badge-pill badge-success">published</span>
									@else
										--
									@endif
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="col-lg-12 mt-5">
				{{ $pages->links() }}
			</div>
		
		</div>
	
	</div>

@endsection