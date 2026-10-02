@include('admin.edges._form', [
    'jalur' => null,
    'formAction' => route('admin.edges.store'),
    'method' => 'POST',
    'title' => 'Tambah Edge',
])