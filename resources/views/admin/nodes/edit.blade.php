@include('admin.nodes._form', [
    'titik' => $titik,
    'formAction' => route('admin.nodes.update', $titik),
    'method' => 'PUT',
    'title' => 'Ubah Titik / Node',
])