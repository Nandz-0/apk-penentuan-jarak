@include('admin.kategori-faskes._form', [
    'kategori' => null,
    'formAction' => route('admin.kategori-faskes.store'),
    'method' => 'POST',
    'title' => 'Tambah Kategori Faskes',
])