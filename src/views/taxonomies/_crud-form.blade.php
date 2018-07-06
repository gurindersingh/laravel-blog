
<div class="row">
	<div class="col-lg-12">
		<form action="">
			
			<div class="form-row">
				<div class="col-lg-12">
					<hr>
					<h3 class="fsz-sm tt-u ls-12 mb-3">Taxonomy To Create</h3>
					
					<label for="category" class="toggle label mr-3">
						<input type="radio" name="taxonomy" value="category" id="category">
						<span>Category</span>
					</label>
					<label for="tag" class="toggle label mr-3">
						<input type="radio" name="taxonomy" value="tag" id="tag">
						<span>Tag</span>
					</label>
				</div>
			</div>
		
			<div class="form-group">
				<label for="name" class="label">Taxonomy Name</label>
				<input type="text" id="name" name="name" placeholder="Name..." class="form-control">
			</div>
			
			<div class="form-group">
				<label for="description" class="label">Description</label>
				<textarea name="description" id="description" rows="5" placeholder="Description..." class="form-control"></textarea>
			</div>
			
			<div class="form-group">
				<label for="parent_id" class="label">Parent</label>
				<select name="parent_id" id="parent_id" class="form-control">
					<option value="1">Category</option>
					<option value="1">Category</option>
					<option value="1">Category</option>
				</select>
			</div>
		
		</form>
	</div>
</div>