@include('admin.faskes._form', [
    'faskes' => null,
    'formAction' => route('admin.faskes.store'),
    'method' => 'POST',
    'title' => 'Tambah Faskes',
])