# BAB I. PENDAHULUAN

## 1.1 Latar Belakang

Kesehatan merupakan salah satu kebutuhan dasar manusia yang tidak dapat ditunda, karena
kualitas kesehatan secara langsung memengaruhi produktivitas dan kualitas hidup seseorang.
Undang-Undang Nomor 17 Tahun 2023 tentang Kesehatan menegaskan bahwa setiap orang berhak
memperoleh akses terhadap pelayanan kesehatan yang aman, bermutu, dan terjangkau. Negara
hadir untuk menjamin ketersediaan fasilitas pelayanan kesehatan (faskes) dan kemudahan
masyarakat mengaksesnya. Namun demikian, ketersediaan faskes saja tidak cukup; kemudahan
akses, termasuk kemudahan menjangkau lokasi faskes, menjadi faktor penentu apakah
pelayanan kesehatan tersebut benar-benar dapat dimanfaatkan oleh masyarakat.

Jarak dan waktu tempuh sangat menentukan perilaku seseorang dalam memanfaatkan pelayanan
kesehatan. Pada kondisi kegawatdaruratan, setiap menit yang hilang akibat salah memilih
faskes atau terlambat mengetahui keberadaan faskes terdekat dapat berdampak pada
keselamatan pasien. Sementara itu, pada kondisi biasa, masyarakat cenderung memilih faskes
yang paling dekat dari lokasinya, baik untuk berkonsultasi, berobat, maupun membeli obat.
Meskipun demikian, banyak masyarakat yang masih kesulitan menentukan faskes mana yang
benar-benar terdekat dari posisinya. Penilaian "dekat atau jauh" yang hanya berdasarkan
perkiraan atau informasi dari orang lain sering kali keliru, terutama karena jarak yang
ditempuh tidak selalu berupa garis lurus, melainkan mengikuti jaringan jalan yang ada.
Jarak sebenarnya lebih panjang dan berbelok-belok mengikuti kondisi jalan, sehingga
perhitungan jarak garis lurus (Euclidean) tidak cukup representatif untuk menentukan
faskes terdekat.

Perkembangan teknologi informasi dan komunikasi, khususnya Sistem Informasi Geografis
(SIG) dan layanan berbasis lokasi (Location-Based Service), membuka peluang untuk menjawab
permasalahan tersebut. Melalui pemanfaatan peta digital dan penentuan posisi berbasis GPS,
masyarakat dapat memperoleh informasi spasial tentang lokasi faskes beserta rute yang harus
ditempuh. Namun, untuk memberikan rekomendasi faskes terdekat yang akurat, sistem tidak
cukup hanya menampilkan lokasi faskes pada peta. Diperlukan sebuah algoritma yang mampu
menghitung lintasan terpendek dari posisi pengguna menuju setiap faskes melalui jaringan
jalan yang tersedia, kemudian mengurutkannya berdasarkan jarak tempuh sungguhan.

Salah satu algoritma yang umum digunakan untuk menyelesaikan permasalahan lintasan
terpendek adalah algoritma Dijkstra. Algoritma yang ditemukan oleh Edsger W. Dijkstra pada
tahun 1959 ini bekerja pada struktur data graf dengan bobot sisi (edge) non-negatif dan
mampu menentukan jarak terpendek dari satu titik awal menuju seluruh titik lainnya secara
efisien. Karakteristik jaringan jalan yang selalu memiliki bobot jarak bernilai positif
membuat algoritma Dijkstra sangat tepat untuk diterapkan. Keunggulan algoritma ini adalah
hasilnya yang dijamin optimal serta relatif mudah diimplementasikan, sehingga cocok untuk
kasus pencarian rute terpendek pada jaringan jalan suatu wilayah skala kecamatan.

Kecamatan Taman merupakan salah satu kecamatan di Kota Madiun, Provinsi Jawa Timur, yang
memiliki tingkat mobilitas penduduk cukup tinggi. Wilayah ini memiliki beragam fasilitas
pelayanan kesehatan, meliputi puskesmas, rumah sakit umum, klinik, hingga apotek, yang
tersebar di berbagai kelurahan seperti Banjarejo, Demangan, dan Pandean. Banyaknya faskes
dengan kategori berbeda tersebut justru menimbulkan kebingungan bagi masyarakat ketika
harus menentukan faskes mana yang terdekat dan sesuai dengan kebutuhan pelayanan, misalnya
puskesmas untuk pengobatan dasar, klinik untuk pelayanan dokter umum, maupun apotek untuk
pembelian obat. Tanpa alat bantu yang tepat, masyarakat harus mencari-cari sendiri dan
berisiko menempuh faskes yang lebih jauh padahal terdapat faskes yang lebih dekat.

Berdasarkan uraian di atas, diperlukan sebuah aplikasi yang dapat merekomendasikan fasilitas
kesehatan terdekat dari lokasi pengguna secara akurat menggunakan algoritma Dijkstra.
Pengguna cukup menentukan/memilih lokasi awal melalui peta digital, kemudian sistem
menghitung jarak terpendek pengguna menuju setiap faskes melalui jaringan jalan dan
menampilkan faskes-faskes yang direkomendasikan beserta jarak tempuh serta rute lintasannya.
Dengan demikian, masyarakat dapat memperoleh informasi yang objektif, cepat, dan mudah
dipahami dalam menentukan faskes tujuan. Penelitian ini diharapkan mampu menghasilkan
aplikasi "Penentuan Jarak Terdekat untuk Rekomendasi Faskes Menggunakan Algoritma Dijkstra
di Wilayah Madiun Taman" yang bermanfaat bagi masyarakat, sekaligus menjadi alternatif
solusi pemanfaatan ilmu komputer dalam bidang kesehatan.