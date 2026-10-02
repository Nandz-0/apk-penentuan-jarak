@include('admin.kategori-faskes._form', [
    'kategori' => $kategoriFaskes,
    'formAction' => route('admin.kategori-faskes.update', $kategoriFaskes),
    'method' => 'PUT',
    'title' => 'Ubah Kategori Faskes',
])