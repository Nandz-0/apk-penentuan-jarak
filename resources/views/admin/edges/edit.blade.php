@include('admin.edges._form', [
    'jalur' => $jalur,
    'formAction' => route('admin.edges.update', $jalur),
    'method' => 'PUT',
    'title' => 'Ubah Edge',
])