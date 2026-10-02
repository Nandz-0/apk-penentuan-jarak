@include('admin.faskes._form', [
    'faskes' => $faskes,
    'formAction' => route('admin.faskes.update', $faskes),
    'method' => 'PUT',
    'title' => 'Ubah Faskes',
])