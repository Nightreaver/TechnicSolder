@layout('layouts/modpack')
@section('content')
<h1>Modpack Management</h1>
<hr>
<h2>{{ $build->modpack->name }} Build {{ $build->version }}</h2>
<hr>
<table id="mod-list" class="table">
{{ Table::headers('Mod Name', 'Version', '') }}
@foreach ($build->modversions as $ver)
	<tr>
		<td>{{ HTML::link('mod/view/'.$ver->mod->id, $ver->mod->pretty_name) }} ({{ $ver->mod->name }})</td>
		<td>
			<form method="post" action="{{ URL::to('modpack/build/modify') }}" style="margin-bottom: 0" class="mod-version">
				<input type="hidden" class="build-id" name="build_id" value="{{ $build->id }}">
				<input type="hidden" class="pivot-id" name="pivot_id" value="{{ $ver->pivot->id }}">
				<input type="hidden" name="action" value="version">
				<select name="version">
					@foreach ($ver->mod->versions as $version)
					<option value="{{ $version->id }}"{{ $selected = ($ver->version == $version->version ? 'selected' : '') }}>{{ $version->version }}</option>
					@endforeach
				</select>
				<button type="submit" class="btn btn-primary btn-small">Change</button>
			</form>
		</td>
		<td>
			<form method="post" action="{{ URL::to('modpack/build/modify') }}" style="margin-bottom: 0" class="mod-delete">
				<input type="hidden" name="build_id" value="{{ $build->id }}">
				<input type="hidden" name="pivot_id" value="{{ $ver->pivot->id }}">
				<input type="hidden" name="action" value="delete">
				<button type="submit" class="btn btn-danger btn-small">Remove</button>
			</form>
		</td>
	</tr>
@endforeach
<form method="post" action="{{ URL::to('modpack/build/modify') }}" class="mod-add">
<input type="hidden" name="build" value="{{ $build->id }}">
<input type="hidden" name="action" value="add">
<tr id="mod-list-add">
	<td>
		<i class="icon-plus"></i> 
		<select name="mod-name" id="mod">
			<option value="">Select One</option>
			@foreach (Mod::all() as $mod)
			<option value="{{ $mod->name }}">{{ $mod->pretty_name }}</option>
			@endforeach
		</select>
	</td>
	<td>
		<select name="mod-version" id="mod-version">
			<option value="">Select a Mod</option>
		</select>
	</td>
	<td>
		<button type="submit" class="btn btn-success btn-small">Add Mod</button>
	</td>
</tr>
</form>
{{ Table::close() }}
<script type="text/javascript">

$(".mod-version").submit(function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "{{ URL::to('modpack/modify/version') }}",
		data: $(this).serialize(),
		success: function (data) {
			alert(data.success);
		}
	});
});

$(".mod-delete").submit(function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "{{ URL::to('modpack/modify/delete') }}",
		data: $(this).serialize(),
		success: function (data) {
			//
		}
	});
	$(this).parent().parent().fadeOut();
});

$(".mod-add").submit(function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "{{ URL::to('modpack/modify/add') }}",
		data: $(this).serialize(),
		success: function (data) {
			$("#mod-list-add").before('<tr><td>' + data.pretty_name + '</td><td>' + data.version + '</td><td></td></tr>');
		}
	});
});

$("#mod").change(function() {
	$.ajax({
		type: "GET",
		url: "{{ URL::to('api/mod/') }}" + $(this).val(),
		success: function (data) {
			$("#mod-version").empty();
			$(data.versions).each(function(e, m) {
				$("#mod-version").append('<option value="' + m + '">' + m + '</option>');
			});
		}
	});
});

</script>
@endsection