-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2025 at 07:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsbbf`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `category_id`, `title`, `author`, `description`, `price`, `stock`, `image`) VALUES
(1, 2, 'One Piece', 'Eiichiro Oda', 'One Piece bercerita tentang bocah laki-laki bernama Monkey D. Luffy yang bercita-cita untuk menjadi Raja Bajak Laut. Baginya, menjadi sosok hebat seperti itu mengartikan bahwa dirinya telah mendapatkan kebebasan yang selama ini ia impikan.\r\n\r\nImpian besar tersebut tercetus setelah pertemuan Luffy dengan Shanks si Rambut Merah, bajak laut hebat yang memimpin kelompok Bajak Laut Rambut Merah. Shanks telah memotivasi Luffy dalam banyak hal, membuat dirinya berambisi untuk menjadi Raja Bajak Laut masa depan.\r\n\r\nAgar bisa mewujudkan mimpinya tersebut, Luffy pun memulai pelayaran seorang diri saat beranjak dewasa. Dalam perjalanannya, laki-laki yang memakan buah iblis Hito Hito no Mi, Model: Nika dan mendapatkan tubuh karet sejak kecil ini akan merekrut beberapa kru untuk bergabung dengan kelompok Bajak Laut Topi Jerami yang dipimpinnya.\r\n\r\nJalan Luffy tak akan mudah. Dia dan teman-temannya akan dihadapkan pada berbagai bahaya dan musuh tangguh yang harus mereka kalahkan.\r\n\r\nSelain itu, mereka harus menjelajahi Grand Line untuk menemukan harta karun mistis yang disebut \'One Piece\'. Benda itulah yang akan menjadi kunci bagi Luffy untuk menjadi Raja Bajak Laut setelah Gol D. Roger. Kini, perjalanan mereka telah memasuki perairan bagian kedua Grand Line, yaitu New World, di mana misteri terbesar menanti.', 40000.00, 114, '1759365338_68ddc8da322cd.jpg'),
(2, 3, 'The Chronicles of Narnia: The Lion, the Witch and the Wardrobe', 'C. S. Lewis', 'Bermula pada tahun 1940 saat Perang Dunia II, empat bersaudara—Peter, Susan, Edmund, and Lucy Pevensie—dievakuasi dari London untuk menghindari pengeboman. Mereka dikirim ke tempat Professor Digory Kirke, yang tinggal di daerah pedalaman Inggris.\r\n\r\nSaat keempat anak mengelilingi rumah tersebut, Lucy menemukan lemari yang membawanya ke dunia ajaib,Narnia. Disana ia bertemu faun bernama Mr Tumnus, yang mengundang Lucy untuk minum teh di rumahnya. Akhirnya ia mengakui rencananya untuk melaporkan Lucy kepada yang berpura-pura menjadi ratu Narnia, yang juga dikenal sebagai Penyihir Putih namun akhirnya membiarkan Lucy pergi. Saat kembali ke dunia kita, saudara-saudara Lucy tak percaya padanya tentang Narnia. Namun kakaknya, Edmund, masuk ke dalam lemari, ke Narnia, dan bertemu Penyihir Putih, yang mengaku sebagai \"Ratu Narnia\" berteman dengan Edmund dengan menawarkan Manisan Turki yang sangat disukai Edmund. Sang Penyihir menyuruh Edmund untuk membawa saudara-saudaranya ke Narnia dengan balasan manisan tersebut lagi. Lucy bertemu Edmund di Narnia dan mereka kembali ke rumah Professor Kirke. Dari cerita Lucy, Edmund menyadari bahwa wanita yang ditemuinya itu adalah Penyihir Putih, tetapi tak mengatakan pada siapapun tentang hal itu. Dia bahkan menyangkal pada Peter dan Susan bahwa dia telah masuk ke Narnia, saat Lucy menceritakan hal itu kepada mereka. Mereka berempat tetap memasuki dunia Narnia saat bersembunyi dalam lemari dan bertemu berang-berang yang bisa bicara disana. Berang-berang tersebut menceritakan sebuah ramalan bahwa akan datang dua anak Adam dan dua anak Hawa yang akan mengalahkan Penyihir Putih dan mengisi empat takhta di Cair Paravel. Berang-berang juga menceritakan tentang raja Narnia sebenarnya, singa gagah bernama Aslan yang telah menghilang sekian lama, tetapi sekarang kembali lagi.\r\n\r\nEdmund diam-diam pergi ke istana Penyihir Putih, dimana terdapat banyak patung batu, yaitu musuh Penyihir yang telah disihir menjadi batu. Dia dianggap gagal membawa ketiga saudaranya dan dikurung di Istana tersebut. Berang-berang yang menyadari hal tersebut langsung meninggalkan rumahnya dan menuntun ketiga Pevensie ke tempat Aslan. Di perjalanan, mereka terus diburu oleh suruhan Penyihir Putih. Sinterklas mengunjungi mereka dan memberi mereka hadiah. Peter mendapat pedang dan perisai, Susan mendapat terompet dan satu set alat panah, Lucy mendapat botol kecil berisi cairan penyembuh -yang dengan hanya setetes bisa memulihkan siapapun yang sekarat- dan sebuah pisau belati, dan berang-berang mendapat alat menjahit.\r\n\r\nAkhirnya mereka semua dapat bertemu Aslan dan pasukannya. Peter menggunakan pedangnya pertama kali melawan serigala, yang merupakan pasukan Penyihir, yang mencoba menyerang Susan dan Lucy. Setelah mendengar cerita dari ketiga Pevensie, Aslan kemudian memerintahkan sebagian pasukannya untuk menjemput Edmund dari istana Penyihir.\r\n\r\nPenyihir datang kepada Aslan dan mengatakan bahwa sesuai hukum \"deep magic from the dawn of time\" dia berhak memiliki Edmund karena telah berkhianat. Aslan berbicara padanya secara pribadi dan menawarkan diri untuk mengganti Edmund. Malam harinya, Aslan meninggalkan perkemahan diam-diam namun diikuti Susan dan Lucy, dan dia memberitahukan pertukarannya tersebut. Sang Penyihir mengikat Aslan di Meja Batu lalu membunuhnya dengan pisau. Lucy dan Susan menghampiri Aslan yang terbujur kaku dan terkejut ketika Aslan hidup kembali. Kemudian Aslan menjelaskan salah satu hukum dari Deep Magic tersebut adalah jika nyawa yang tak bersalah menggantikan seorang pengkhianat, maka nyawa tersebut tetap hidup.\r\n\r\nAslan mengangkut Lucy dan Susan di punggungnya kemudian pergi ke Istana Penyihir dimana dia menghidupkan semua yang telah disihir jadi batu. Peter dan Edmund memimpin perang melawan Penyihir Putih dan pasukannya namun mereka hampir kalah. Aslan tiba dengan membawa para patung-yang-dihidupkan sebagai bala bantuan. Akhirnya para penduduk Narnia mengalahkan musuh mereka dan Penyihir Putih dibunuh oleh Aslan.\r\n\r\nKeempat Pevensie akhirnya jadi raja dan ratu Narnia: King Peter the Magnificent (Raja Peter yang Agung), Queen Susan the Gentle (Ratu Susan yang Lemah Lembut), King Edmund the Just (Raja Edmund yang Adil) dan Queen Lucy the Valiant (Ratu Lucy yang Berani). Beberapa tahun kemudian, mereka yang telah dewasa memburu rusa putih. Mereka melihat lampu jalan dan berjalan lurus ke arah semak-semak. Ketika memasukinya, semak-semak tersebut berubah jadi mantel dan mereka kembali ke lemari, tempat awal mereka masuk ke Narnia, dan juga kembali menjadi anak-anak. mereka kembali ke rumah Professor.', 100000.00, 63, '1759365490_68ddc97252e93.jpg'),
(3, 4, 'Gadis Kretek', 'Ratih Kumala', 'Menceritakan tentang pencarian 3 orang anak pewaris pabrik kretek terbesar dari Kudus yang bernama Kretek Djagat Raja dengan Jeng Yah, nama yang sering disebut-disebut Soeraja yang merupakan Romo mereka ketika sekarat. Tiga putra Soeraja percaya bahwa pembicaraan terakhir ayah mereka adalah permintaan yang harus dipenuhi. Lebas, si putra bungsu, mulai menyusun informasi dari percakapan terakhir sang Romo, yang telah mulai kehilangan ingatannya. Lebas berhasil menggali petunjuk mengenai keberadaan terakhir Jeng Yah.\r\n\r\nMencari Jeng Yah seperti mengikuti jejak masa lalu yang mengungkapkan segala rahasia bisnis dan keluarga, termasuk kisah asmara Romo mereka dengan pemilik lidah Roro Mendhut, yang juga dikenal sebagai pemilik Kretek Gadis, merek kretek lokal yang terkenal di Kota M pada era itu. Gadis Kretek tidak hanya mengeksplorasi romansa Soeraja dan Jeng Yah, namun juga menghadirkan latar belakang yang kental dengan budaya Jawa seperti kota M, Kudus, dan beberapa wilayah lain di Jawa Tengah selama periode penjajahan Jepang hingga pemberontakan PKI. Ini mengajak pembaca dalam petualangan sejarah dan industri kretek di Indonesia. Novel ini terdiri dari 15 bab, dimulai dengan kesehatan yang memburuk dari tokoh legendaris, Pak Soeraja, yang sekarang dalam kondisi sekarat. Yang menemaninya bukanlah istri sejatinya, tetapi Jeng Yah, seorang nama yang telah lama terlupakan.\r\n\r\nAlur yang digunakan adalah alur maju-mundur sehingga pembaca akan memahami kisah-kisah yang melatarbelakangi kejadian pencarian Jeng Yah. Alur cerita yang maju mengeksplorasi perjalanan pencarian Jeng Yah oleh ketiga anak Soeraja. Meskipun memiliki sedikit informasi tentang keberadaan Jeng Yah, perjalanan mereka tidaklah mudah. Meskipun mereka saudara, Lebas (anak ketiga) dan Tegar (anak pertama) seringkali memiliki perbedaan pendapat yang menyebabkan pertengkaran. Karim (anak kedua) merasa kesulitan untuk menjadi penengah di antara mereka. Konflik ini cukup menghambat perjalanan mereka ke Jawa dalam pencarian Jeng Yah. Di sisi lain, alur cerita yang mundur membawa kita lebih dalam ke dalam perjalanan generasi pertama dan kedua dalam membangun bisnis rokok.\r\n\r\nHarap diingat bahwa novel ini secara keseluruhan mengisahkan tiga generasi dari dua keluarga yang berselisih di Kota M. Perselisihan ini berakar pada dua hal: kretek dan cinta. Awalnya, kisah dimulai dengan persaingan generasi pertama, yaitu Idroes Moeria dan Soejagad, yang bersaing baik dalam merebut gadis yang mereka cintai maupun dalam bisnis kretek selama masa penjajahan Belanda-Jepang. Kemudian, cerita berlanjut ke generasi kedua, melibatkan Dasiyah, Soeraja, dan Purwanti, yang hidup di masa G30S PKI. Akhirnya, cerita mencapai generasi ketiga yang mengungkapkan persaingan keluarga mereka dengan cara yang menurut pembaca lebih bijak dan elegan daripada dramatis. Mengikat tiga generasi keluarga dalam sebuah cerita yang menarik sambil mempertahankan ritme yang membuat pembaca penasaran dengan konfliknya tidaklah mudah. Namun, cerita ini berhasil diselesaikan dengan baik, dengan plot twist yang membuat pembaca akan terkejut.\r\n\r\nTokoh dan karakter dalam novel ini tidak digambarkan secara tegas sebagai protagonis atau antagonis. Setiap karakter memiliki sisi baik dan buruknya masing-masing, seolah-olah ingin menunjukkan bahwa manusia tidak pernah sepenuhnya sempurna; ada baik dan buruk dalam diri mereka. Namun, di antara semua karakter, keluarga Gadis Kretek, terutama ayah dan ibunya, Idroes Moeria dan Roemaisa, sangat menarik perhatian pembaca.', 75000.00, 79, '1759365594_68ddc9dae5605.jpg'),
(4, 2, 'Naruto', 'Masashi Kishimoto', 'Dua belas tahun sebelum dimulainya seri, Siluman Rubah Berekor-Sembilan menyerang Konohagakure. Ia menghancurkan desa dan menewaskan banyak orang. Pemimpin desa saat itu, Hokage Keempat mengorbankan hidupnya untuk menyegel Ekor-Sembilan pada seorang bayi yang baru lahir, Naruto Uzumaki. Ia menjadi yatim piatu karena peristiwa itu. Naruto dijauhi oleh penduduk desa, yang takut dan marah, melihat dia sebagai Ekor-Sembilan itu sendiri. Meskipun Hokage Ketiga melarang berbicara tentang sesuatu yang berhubungan dengan Ekor-Sembilan, anak-anak — diberi isyarat oleh orang tua mereka — mewarisi kebencian yang sama terhadap Naruto. Karena ingin dirinya diakui, Naruto bersumpah suatu hari akan menjadi Hokage terbesar bagi desa yang pernah ada.', 50000.00, 85, '1759365804_68ddcaac6dc44.jpg'),
(5, 5, 'Laut Bercerita', 'Leila S. Chudori', 'Novel ini ditulis dalam sudut pandang ‘Aku’ dari kedua karakter Biru Laut Wibisono dan Asmara Jati. Biru Laut adalah seorang Mahasiswa, yang mempunyai adik bernama Asmara Jati. Baik Laut atau Asmara Jati, keduanya menjadi tokoh utama dalam Novel tersebut.\r\n\r\nBermula pada tahun 1991, Leila mengawali novelnya dengan mengisahkan kehidupan sekelompok mahasiswa yang berkegiatan di suatu rumah di Seyegan, Yogyakarta. Mahasiswa-mahasiswa ini memiliki ketertarikan yang sama terhadap bacaan termasuk sastra. Dalam hal ini, termasuk sastra yang sempat dilarang untuk dibicarakan ketika itu, sastra karya Pramoedya Ananta Toer.\r\n\r\nDalam novel ini, alur yang digunakan tidak berurutan. Dari 1991, pembaca akan diarahkan menuju bab berikutnya yakni tahun 1998. Leila menulis berdasarkan peristiwa saat ini (ketika Biru Laut berada dalam penjara) dan masa lalu (ketika Biru laut masih menjadi mahasiswa dan buron).\r\n\r\nSebelum berada di penjara, konflik yang dihadapi Laut cukup banyak. Termasuk bagaimana ketika ia dan teman-temannya mengatur diskusi dan aksi demi membela petani Jagung di Blangguan yang tanahnya diambil secara tidak adil oleh pemerintah. Selain itu, novel ini juga bercerita bagaimana salah satu sahabat Laut berkhianat dan membocorkan informasi kepada intel. Aktivisme-aktivisme dan pembelaan ini yang kemudian diketahui oleh intel mengantarkan Laut kepada penjara.\r\n\r\nSelanjutnya, Novel ini menceritakan bagaimana keluarga Laut termasuk Asmara Jati mengupayakan untuk mencari mahasiswa-mahasiswa yang hilang—termasuk Laut—yang tidak diketahui keberadaannya hingga beberapa tahun. Asmara Jati juga sempat menulis surat imajinatif yang ia sampaikan kepada Laut:\r\n\r\n\"Mas Laut,\r\n\r\nBapak sudah menyusulmu pagi tadi.\r\n\r\nPeluklah ia karena beliau sangat rindu padamu\r\n\r\nEmpat tahun piring makanmu tidak boleh kami singkirkan, empat tahun kamarmu dan buku-bukumu berdiri tegak persis pada tempatnya tanpa sebutir debu pun yang berani melekat karena Bapak rajin merawatnya. Sesekali jika dia memangku ranselmu yang sudah butut itu dan mengelus-elusnya, seolah barang yang setia melekat di punggungmu itu adalah pengganti dirimu.', 80000.00, 70, '1759365944_68ddcb3811bf6.jpg'),
(6, 1, 'ERLANGGA FOKUS SNBT', 'Erlangga', 'Deskripsi Produk\r\n1. Memahami pokok bahasan dari Rangkuman Materi. Materi rangkuman dalam seri Erlangga Fokus SNBT disusun sesuai dengan standar materi SNBT sehingga waktu belajar akan menjadi lebih efektif dan efisien.\r\n\r\n\r\n\r\n2. Berlatih mengerjakan Soal Prediksi SNBT secara mandiri. Pentingnya berlatih mengerjakan soal karena karakter soal SNBT memiliki tipe soal yang mengukur tingkat tinggi atau Higher Order Thinking Skills (HOTS). Adapun tes literasi pada SNBT sudah mengacu pada Asesmen Kompetensi Minimum (AKM). Kondisikan suasana belajar seolah-olah sedang mengerjakan soal SNBT yang sesungguhnya. Kerjakan dalam suasana belajar yang hening. Letakkan alat komunikasi di luar jangkauan atau simpan dalam keadaan mati, sediakan minuman dan makanan ringan, siapkan alat tulis yang dibutuhkan.\r\n\r\nBuku ini juga dilengkapi dengan QR Codes berisi simulasi SNBT berbasis komputer. Dibentuk batasan waktu per subtes mata pelajaran, dan ketika waktu habis, soal akan otomatis berhenti dapat diakses. Dengan demikian, siswa SNBT berbasis komputer ini diharapkan peserta didik dapat berlatih dan membiasakan diri mengerjakan soal SNBT yang sesungguhnya.', 100000.00, 71, '1759366426_68ddcd1ac286a.jpg'),
(7, 2, 'Demon Slayer Vol. 1', 'Koyoharu Gotōge', 'Tanjiro Kamado adalah seorang anak laki-laki yang baik hati dan rajin yang tinggal bersama keluarganya di pegunungan. Setelah kematian ayahnya, ia menjadi satu-satunya pencari nafkah bagi keluarganya, menjual arang di desa-desa terdekat. Suatu hari ia pulang ke rumah dan mendapati keluarganya telah dibantai oleh iblis. Adik perempuannya, Nezuko , adalah satu-satunya yang selamat, meskipun ia sendiri telah berubah menjadi iblis. Ia menunjukkan sifat yang tidak biasa karena masih memiliki sedikit emosi dan kognisi manusia. Pasangan ini ditemukan oleh Giyu Tomioka , Hashira Air dari Korps Pembasmi Iblis, yang mengirim Tanjiro untuk dilatih oleh mantan mentornya sendiri, Sakonji Urokodaki . Tanjiro bersumpah untuk menjadi Pembasmi Iblis untuk membalaskan dendam keluarganya dan menemukan cara untuk mengembalikan Nezuko kepada umat manusia.\r\n\r\nSetelah menguasai gaya pedang \"Pernapasan Air\", Tanjiro lolos seleksi akhir Korps. Nezuko, yang telah diberi sugesti hipnotis oleh Urokodaki untuk menekan dorongan iblisnya, menemaninya. Dalam sebuah misi di Asakusa , mereka bertemu Muzan Kibutsuji , leluhur semua iblis dan orang yang bertanggung jawab atas pembunuhan keluarga mereka. Mereka juga berteman dengan Tamayo dan asistennya Yushiro , dua iblis yang telah membebaskan diri dari kendali Muzan. Tamayo setuju untuk mengembangkan obat untuk Nezuko, sebuah proses yang membutuhkan darah dari Dua Belas Kizuki yang kuat, bawahan Muzan yang paling elit.\r\n\r\nTanjiro dan Nezuko bergabung dalam misi mereka bersama sesama Pembunuh Iblis, Zenitsu Agatsuma dan Inosuke Hashibira . Bersama-sama, mereka mengalahkan beberapa anggota Dua Belas Kizuki. Dalam pertempuran sengit, Tanjiro membangkitkan teknik pedang misterius dan kuat yang dikenal sebagai \"Hinokami Kagura\". Tindakan mereka menarik perhatian para Hashira, pendekar pedang tertinggi di Korps, dan mereka dibawa ke hadapan pemimpin organisasi, Kagaya Ubuyashiki . Ia menyetujui kelanjutan kemitraan mereka, meyakini bahwa kedua bersaudara itu berperan penting dalam mengalahkan Muzan.\r\n\r\nMuzan, yang murka dengan kegagalan para Lower Rank-nya, mengeksekusi mereka dan memerintahkan satu-satunya yang selamat untuk membunuh Tanjiro. Setelah pertempuran di atas kereta yang sedang melaju, Tanjiro menang dengan bantuan Flame Hashira, Kyojuro Rengoku , yang kemudian dibunuh oleh iblis Upper Three, Akaza . Kelompok tersebut melanjutkan kampanye mereka, membantu Sound Hashira mengalahkan Upper Six dan kemudian membantu Mist dan Love Hashira melenyapkan Upper Five dan Upper Four di Desa Swordsmith. Selama peristiwa ini, Nezuko mengembangkan kekebalan terhadap sinar matahari, menjadikannya kunci tujuan Muzan untuk mengatasi kelemahan fatalnya sendiri. Tanjiro juga mengetahui bahwa Hinokami Kagura-nya berasal dari \"Sun Breathing\", gaya ilmu pedang asli yang diciptakan oleh Yoriichi Tsugikuni .\r\n\r\nKorps Pembasmi Iblis bersiap untuk konfrontasi terakhir saat Tamayo sedang meracik serum. Muzan melancarkan serangan pendahuluan, yang mendorong Kagaya untuk mengorbankan dirinya dalam serangan bunuh diri. Para Hashira menyerang Muzan, tetapi ia menjebak mereka di dalam Kastil Infinity, tempat mereka harus melawan para Upper Rank yang tersisa. Melalui pengorbanan yang besar, iblis Akaza, Doma, dan Kokushibo dikalahkan. Muzan membunuh Tamayo tetapi menjadi sangat lemah karena racun yang ditanamkannya di dalam dirinya. Terpaksa naik ke atas tanah, pertempuran sengit terjadi saat para Pembasmi Iblis yang tersisa berjuang untuk menahannya hingga matahari terbit. Meskipun sebagian besar Hashira binasa, Tanjiro memberikan pukulan terakhir. Dengan napas terakhirnya, Muzan mengubah Tanjiro menjadi iblis, tetapi Nezuko, yang sekarang sepenuhnya manusia, membantu membalikkan transformasi tersebut.\r\n\r\nSetelah kekalahan Muzan, semua iblis yang dikuasainya musnah. Korps Pembasmi Iblis dibubarkan, hanya menyisakan dua Hashira yang selamat. Tanjiro dan Nezuko kembali ke rumah mereka di pegunungan, perjalanan mereka akhirnya selesai. Pada tahun-tahun berikutnya, Tanjiro menikahi Kanao Tsuyuri, Inosuke menikahi Aoi Kanzaki, dan Zenitsu menikahi Nezuko. Dalam epilog modern, keturunan dan reinkarnasi mereka hidup di dunia yang bebas dari iblis.', 55000.00, 68, '1759453576_68df218867365.jpg'),
(8, 4, 'Laskar Pelangi', 'Andrea Hirata', 'Cerita terjadi di Desa Gantung, Belitung Timur. Dimulai ketika sekolah Muhammadiyah terancam akan dibubarkan oleh Depdikbud Sumsel jikalau tidak mencapai siswa baru sejumlah 10 anak. Ketika itu baru 9 anak yang menghadiri upacara pembukaan, akan tetapi tepat ketika Pak Harfan, sang kepala sekolah, hendak berpidato menutup sekolah, Harun dan ibunya datang untuk mendaftarkan diri di sekolah kecil itu.\r\n\r\nDari sanalah dimulai cerita mereka. Mulai dari penempatan tempat duduk, pertemuan mereka dengan Pak Harfan, perkenalan mereka yang luar biasa di mana A Kiong yang malah cengar-cengir ketika ditanyakan namanya oleh guru mereka, Bu Mus. Kejadian bodoh yang dilakukan oleh Borek, pemilihan ketua kelas yang diprotes keras oleh Kucai, kejadian ditemukannya bakat luar biasa Mahar, pengalaman cinta pertama Ikal, sampai pertaruhan nyawa Lintang yang mengayuh sepeda 80 km pulang pergi dari rumahnya ke sekolah.\r\n\r\nMereka, Laskar Pelangi –nama yang diberikan Bu Muslimah akan kesenangan mereka terhadap pelangi– pun sempat mengharumkan nama sekolah dengan berbagai cara. Misalnya pembalasan dendam Mahar yang selalu dipojokkan kawan-kawannya karena kesenangannya pada okultisme yang membuahkan kemenangan manis pada karnaval 17 Agustus, dan kegeniusan luar biasa Lintang yang menantang dan mengalahkan Drs. Zulfikar, guru sekolah kaya PN yang berijazah dan terkenal, dan memenangkan lomba cerdas cermat. Laskar Pelangi mengarungi hari-hari menyenangkan, tertawa dan menangis bersama. Kisah sepuluh kawanan ini berakhir dengan kematian ayah Lintang yang memaksa Einstein cilik itu putus sekolah dengan sangat mengharukan, dan dilanjutkan dengan kejadian 12 tahun kemudian di mana Ikal yang berjuang di luar pulau Belitong kembali ke kampungnya. Kisah indah ini diringkas dengan kocak dan mengharukan oleh Andrea Hirata, kita bahkan bisa merasakan semangat masa kecil anggota sepuluh Laskar Pelangi ini.', 75000.00, 60, '1759453752_68df2238415fb.jpg'),
(9, 4, 'Dilan 1990', 'Pidi Baiq', 'Sinopsis Novel Dilan- Cinta, walaupun sudah berlalu sekian lama, tetap saja, saat dikenang begitu manis.\r\n\r\nMilea, dia kembali ke tahun 1990 untuk menceritakan seorang laki-laki yang pernah menjadi seseorang yang sangat dicintainya, Dilan.\r\n\r\nLaki-laki yang mendekatinya (milea) bukan dengan seikat bunga atau kata-kata manis untuk menarik perhatiannya. Namun, melalui ramalan seperti tergambarkan pada penggalan cerita berikut :\r\n\r\n“Aku ramal, nanti kita bertemu di kantin.” – Dilan -hlm. 20\r\n\r\nTapi, sayang sekali ramalannya salah. Hari itu, Miela tidak ke kantin karena ia harus membicarakan urusan kelas dengan kawan-kawannya. Sebuah cara sederhana namun bikin senyum dipilih Dilan untuk kembali menarik perhatian dari Milea. Dian mengirim Piyan untuk menyampaikan suratnya yang isinya :\r\n\r\n“Milea, ramalanku, kita akan bertemu di kantin. Ternyata salah. Maaf, tapi ingin meramal lagi : besok kita akan bertemu.”  – Dilan – halaman. 22\r\n\r\nTunggu, besok yang dimaksud oleh dilan itu adalah hari minggu. Ngga mungkin, kan mereka bertemu? Namun, ternyata ramalannya kali ini benar. Dilan datang ke rumah Miela untuk menyampaikan surat undangannya yang isinya :\r\n\r\n“Bismillahirrahmanirrahim. Dengan nama Allah Yang Maha Pengasih lagiPenyayang. Dengan ini, dengan penuh perasaan, mengundang Milea Adnan untuk sekolah pada : Hari Senin, Selasa, Rabu, Kamis, Jumat, dan Sabtu.” – Dilan – hlm. 27\r\n\r\nHal-hal yang sederhana ini nyatanya dapat membuat Milea tersenyum, dan perlahan mulai menaruh perhatiannya kepada Dilan. Sampai-sampai, sebentar dia lupa, ada Beni yaitu pacarnya yang berada di Jakarta.\r\n\r\nMilea tak mau kehilangan Dilan. Baginya, Dilan seperti sesuatu yang selalu dapat membuat hari-harinya penuh warna. Tapi, dia tampak sangat jahat pada Dilan, karena dia mau untuk menerima perhatian dari Dilan, padahal dia sudah ada yang memiliki.\r\n\r\nSampai pada waktu milea memutuskan hubungannya dengan beni, pacarnya di jakarta. Ia cowok yang sangat emosian dan manja. Karena suatu hal yang ga perlu dijelaskan. Semenjak itu hubugan Dilan dan Milea semakin erat saja.', 90000.00, 64, '1759454201_68df23f968e63.jpg'),
(10, 3, 'The Midnight Library', 'Matt Haig', 'Sinopsis Novel The Midnight Library\r\n\r\nKisah ini dimulai oleh seorang wanita muda bernama Nora Seed yang tak bahagia dengan pilihan hidupnya. Nora merasa hidupnya sangat payah dan tidak memiliki alasan untuk hidup lagi. Tak satu pun yang ia lakukan berjalan dengan baik. Setiap langkah yang dia ambil berujung kesalahan, setiap keputusan menjadi bencana. Ia gagal menikah dengan kekasihnya.\r\n\r\nIa mengecewakan ayahnya karena berhenti menjadi atlet renang, dan menjauh dari mimpinya berenang di Olimpiade. Nora juga mundur sebagai vokalis The Labyrinth, band yang dibentuk oleh kakaknya Joe dan temannya Ravi, saat mereka mendapat tawaran dari sebuah label rekaman, hingga hubungan mereka pun menjadi renggang. Nora bahkan juga dipecat dari pekerjaannya sebagai penjaga toko, dan murid les pianonya mengundurkan diri di hari yang sama.\r\n\r\nMr. Banerjee, kakek tua yang bisanya meminta bantuannya untuk mengambilkan obat pun tiba-tiba memberitahunya, bahwa telah ada orang lain yang bisa mengambilkan obat untuknya. Bagi Nora, hidupnya tidak memiliki arti lagi dan ia memutuskan untuk mengakhiri hidupnya.\r\n\r\nBukannya melihat cahaya terang seperti di film-film, Nora justru terbangun di sebuah perpustakaan. Ia mengira ia pasti telah meninggal. Nora mengamati sekeliling dan menemukan begitu banyak buku dan tak ada jalan. Nora menemukan dirinya berada di perpustakaan yang dikelola oleh pustakawan sekolahnya, Ny. Elm.\r\n\r\n\r\n\r\n\r\n\r\nPerpustakaan berada di antara hidup dan mati dengan jutaan buku yang penuh dengan kisah hidupnya seandainya dia membuat beberapa keputusan secara berbeda. Dia kemudian mencoba menemukan kehidupan di mana dia paling puas. Misalnya, satu kemungkinan kehidupan di mana dia mencoba untuk bersatu kembali dengan pacarnya dan mendapati dirinya menikah dengannya, tetapi itu tidak seperti yang dia harapkan. Dia juga melihat dirinya sebagai ahli glasiologi yang melakukan penelitian di kepulauan Svalbard di Kutub Utara – kehidupan yang sangat berbeda dari kehidupan yang dia coba hindari, tetapi bukan pilihan yang lebih baik.\r\n\r\ndi Midnight Library, ia memiliki kesempatan untuk memperbaiki semuanya. segalanya akan berubah dengan menjelajah waktu dan masuk ke dalamnya agar bisa menjalani kehidupan dengan versi dirinya sendiri, serta mendapatkan bahagia yang Nora inginkan selama ini.', 120000.00, 40, '1759454686_68df25de29e4a.jpg'),
(11, 5, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Buku ini bercerita tentang perjalanan seorang tokoh bernama Minke. Minke adalah salah satu anak pribumi yang sekolah di HBS. Pada masa itu, yang dapat masuk ke sekolah HBS adalah orang-orang keturunan Eropa. Minke adalah seorang pribumi yang pandai, ia sangat pandai menulis. Tulisannya bisa membuat orang sampai terkagum-kagum dan dimuat di berbagai Koran Belanda pada saat itu. Hanya saja sebagai seorang pribumi, ia kurang disukai oleh siswa-siswi Eropa lainnya. Minke digambarkan sebagai seorang revolusioner di buku ini. Ia berani melawan ketidakadilan yang terjadi pada bangsanya. Ia juga berani memberontak terhadap kebudayaan Jawa, yang membuatnya selalu di bawah.\r\n\r\nSelain tokoh Minke, buku ini juga menggambarkan seorang \"Nyai\" yang bernama Nyai Ontosoroh. Nyai pada saat itu dianggap sebagai perempuan yang tidak memiliki norma kesusilaan karena statusnya sebagai istri simpanan. Statusnya sebagai seorang Nyai telah membuatnya sangat menderita, karena ia tidak memiliki hak asasi manusia yang sepantasnya. Tetapi, yang menariknya adalah Nyai Ontosoroh sadar akan kondisi tersebut sehingga dia berusaha keras dengan terus-menerus belajar, agar dapat diakui sebagai seorang manusia. Nyai Ontosoroh berpendapat, untuk melawan penghinaan, kebodohan, kemiskinan, dan sebagainya hanyalah dengan belajar. Minke juga menjalin asmara dan akhirnya menikah dengan Annelies, anak dari Nyai Ontosoroh dan tuan Mellema.\r\n\r\nMelalui buku ini, Pram menggambarkan bagaimana keadaan pemerintahan kolonialisme Belanda pada saat itu secara hidup. Pram, menunjukan betapa pentingnya belajar. Dengan belajar, dapat mengubah nasib. Seperti di dalam buku ini, Nyai yang tidak bersekolah, dapat menjadi seorang guru yang hebat bagi siswa HBS dan Minke. Bahkan pengetahuan si nyai itu, yang didapat dari pengalaman, dari buku-buku, dan dari kehidupan sehari-hari, ternyata lebih luas dari guru-guru sekolah HBS.', 85000.00, 71, '1759455078_68df276666de6.jpg'),
(12, 6, 'Janji', 'Tere Liye', 'Novel ini menceritakan tentang tiga orang Sekawan yang terkenal karena kenakalannya di salah satu sekolah agama Islam yang sering kita sebut Pesantren Kahar, Baso, dan Hasan namanya. Entah apa yang membuatnya bisa senakal itu. Teman-temannya bahkan gurunya sudah capek melihat kenakalan mereka.\r\nSuatu hari Tiga Sekawan ini membuat kenakalan yang sangat fatal hingga dipanggil oleh Buya. Buya ini adalah seorang guru atau pengurus pesantren yang menjadi ketua di tempatnya ini. Mereka dipanggil ke ruangan Buya untuk mengakui kesalahannya. Memang dasar Tiga Sekawan ini bukannya mengakui kesalahannya malah membela dirinya dengan mencari berbagai banyak alasan. Namun apapun itu alasannya percuma saja. Bahkan banyak santri yang sebelumnya membicarakan bahwa Buya bisa berbicara dengan hewan, pasti gampang saja Buya untuk mengetahui kenakalan dari Tiga Sekawan ini.\r\nADVERTISEMENT\r\n\r\nSebelum Buya menghukum Hasan, Baso, dan Kahar ia menceritakan tentang masa lalu ayahnya. Ayahnya merupakan seorang pendiri pesantren di tempat ini. Ia menceritakan bahwa 40 tahun yang lalu, waktu Buya ini umur 10 tahun, ada santri yang nakalnya melebihi Tiga Sekawan ini. Bahar Safar namanya. Ia sering kali melanggar peraturan pesantren dengan kabur dari pesantren, berjudi, dan minum-minuman keras. Pada suatu hari di bulan Ramadhan, ia mendapat tugas untuk membangunkan sahur. Pada santri umunya membangunkan sahur menggunakan kentungan atau beduk masjid, namun ia berbeda. Ia membangunkan sahur dengan menggunakan meriam bambu. Hingga hal tersebut membuat bangunan pesantren yang terbuat dari kayu, mudah terlalap api. Seluruh santri berlari untuk menyelamatkan diri dari kobaran api. Namun ada salah seorang santri yang kakinya pincang sehingga ia tidak mampu untuk melarikan diri dan ia terjebak di dalam bangunan itu.\r\nADVERTISEMENT\r\n\r\nTepat saat itu, Buya datang bertemu dengan Bahar. Bahar dengan perasaan tanpa bersalah ia berharap dengan kejadian tersebut akan membuatnya dikeluarkan dari pesantren ini. Bahar yang ingin keluar dari pesantren tersebut mengingatkan Buya bahwa Buya tidak akan mengeluarkan santrinya dengan kenakalan apapun. Namun pada waktu itu, Buya melanggar janjinya dengan mengeluarkan Bahar.\r\nTahun demi tahun telah berlalu. Sebelum ayah Buya wafat, ayah Buya menceritakan mimpinya kepada Buya, bahwa ayah Buya bermimpi tiga kali berturut-turut dan itu bukan mimpi yang biasa. Ia bermimpi pada saat itu ia tengah berjalan di gurun pasir yang sangat gersang. Di mimpinya tersebut, ayah Buya melihat dari kejauhan kendaraan yang sangat mewah dan ternyata itu Bahar yang memiliki kendaraan tersebut. Bahar mempersilakan Buya untuk segera menaiki kendaraannya tersebut. Semenjak saat itu, ayah Buya ingin sekali untuk mencari Bahar. Akan tetapi hingga akhir hidupnya, Bahar tidak kunjung ditemukan.\r\nADVERTISEMENT\r\n\r\nSehingga Buya memberikan hukuman kepada Tiga Sekawan ini untuk pergi mencari Bahar. Mereka disuruh untuk menemukan sosok Bahar hingga beberapa minggu. Dengan memberikan petunjuk dan uang saku dari Buya, mereka pergi meninggalkan pesantren ini dan Buya berharap perjalanan mereka akan membawa kebaikan dan pengalaman kepada Tiga Sekawan ini.\r\nPerjalanan mereka dimulai dari satu kota hingga ke kota yang lain. Semenjak Bahar setelah dikeluarkan dari pesantren hingga bertemulah seseorang yang menjadi saksi hidupnya Bahar. Banyak saksi yang menceritakan kehidupan Bahar di masyarakat yang diceritakan kepada Tiga Sekawan ini. Kehidupan Bahar yang bermula dikenal sangat nakal di pesantren sangat berbeda dengan cerita pada kehidupan di masyarakat. Ia dikenal sosok pekerja keras dan dermawan di mata orang-orang yang hidup bersamanya. Bahar yang sudah menabung dari hasil kerja kerasnya untuk menunaikan ibadah haji, ia relakan untuk mempertahankan tempat tinggal anak yatim.\r\nADVERTISEMENT\r\n\r\nKehidupan Bahar yang sangat berubah tersebut dilandasi karena pada saat sebelum dikeluarkan dari pesantren, ia diberikan lima pesan dari Buya pada waktu itu. Ia diperbolehkan meninggalkan pesantren namun harus menepati janji tersebut di masyarakat.  Sosok Buya yang memberikan lima pesan tersebut sangat yakin bahwa Bahar yang sangat nakal itu, meyakini Bahar memiliki hati yang spesial. Setelah itu Bahar pun pergi meninggalkan pesantren tersebut dan mengingat pesan-pesan Buya sampai akhir hayatnya.', 70000.00, 60, '1759455597_68df296d3a3d8.jpg'),
(13, 2, 'Kaoru Hana wa Rin to Saku', 'Saka Mikami', 'SMA Negeri Chidori dan SMA Wanita Akademi Swasta Kikyo adalah dua sekolah yang terletak bersebelahan dan memiliki reputasi yang kontras, SMA Negeri Chidori terkenal karena menerima murid-murid nakal dari kalangan bawah, sedangkan Akademi Swasta Kikyo dikenal sebagai sekolah yang bergengsi yang menerima murid-murid wanita yang terhormat dan mulia. Kedua sekolah memiliki persaingan yang sudah berlangsung lama, dengan Akademi Swasta Kikyo memandang SMA Negeri Chidori rendah dan dengan cemoohan; hal ini membuat siswa dari SMA Negeri Chidori menghindari terlibat dalam hal-hal yang berkaitan dengan Akademi Swasta Kikyo.\r\n\r\nSuatu hari, Rintarō Tsumugi, seorang siswa SMA Negeri Chidori, melayani seorang pelanggan bernama Kaoruko Waguri di toko roti milik keluarganya dan terkejut ketika Kaoruko melihat Rintarō atas kebaikannya yang kemudian menyelamatkannya dari pelecehan yang dilakukan oleh anak nakal. Kaoruko berterima kasih kepadanya dan Rintarō bertanya-tanya kapan dia akan bertemu dengannya lagi sampai dia menemukan bahwa Kaoruko bersekolah di Akademi Swasta Kikyo. Setelah itu, Rintarō dan Kaoruko bercita-cita untuk belajar lebih banyak tentang satu sama lain seiring dengan tumbuhnya persahabatan mereka.', 60000.00, 50, '1760833948_68f4319cb3d58.jpeg'),
(14, 7, 'Black Showman To Namonaki Machi No Satsujin', 'Keigo Higashino', 'Pembunuhan bisa terjadi di mana saja, termasuk di sebuah kota kecil, terpencil, dan nyaris terlupakan di tengah pandemi Covid-19. Seorang mantan guru SMP ditemukan tewas tercekik di halaman rumahnya sendiri. Polisi tidak tahu apakah ini pembunuhan terencana, pembunuhan tak disengaja, atau aksi pencurian yang berakhir dengan pembunuhan. Korban adalah guru yang disegani. Setelah pensiun pun, mantan murid-muridnya sering menghubunginya untuk meminta bantuan atau nasihat. Jadi, tentu saja para mantan muridnya, yang pulang ke kota itu demi menghadiri reuni, termasuk dalam daftar orang-orang yang dicurigai. Polisi kebingungan, dan si pembunuh lega karena identitasnya tidak akan pernah ketahuan. Namun, ia tidak menyangka bahwa putri korban akan muncul bersama pamannya—seorang mantan pesulap eksentrik—dan ikut menyelidiki apa yang sebenarnya terjadi dan mencari tahu siapa yang membunuh Kamio Eiichi.', 90000.00, 60, '1760834953_68f4358930acc.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`) VALUES
(23, 2, '2025-10-03 06:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `book_id`, `quantity`) VALUES
(38, 23, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Pendidikan'),
(2, 'Komik'),
(3, 'Fantasi'),
(4, 'Roman'),
(5, 'Historical'),
(6, 'Horor'),
(7, 'mystery');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 2, 'user', 'user@gmail.com', 'tes', '2025-10-19 11:26:00'),
(2, 2, 'user', 'user@gmail.com', 'tes', '2025-10-19 11:35:42'),
(3, 2, 'user', 'user@gmail.com', 's', '2025-10-19 12:00:44');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('transfer_bank','payment_at_delivery') NOT NULL DEFAULT 'payment_at_delivery',
  `payment_proof` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `payment_method`, `payment_proof`, `shipping_address`, `admin_note`, `created_at`) VALUES
(1, 2, 825000.00, 'paid', 'payment_at_delivery', NULL, 'ghf', NULL, '2025-10-02 00:55:49'),
(2, 2, 300000.00, 'paid', 'transfer_bank', 'proof_1759370997_2.jpg', 'Slawi, Jl. Raya No.10', 'Dikonfirmasi oleh admin', '2025-10-02 00:56:33'),
(3, 2, 225000.00, 'paid', 'transfer_bank', 'proof_1759449063_2.jpg', 'fg', NULL, '2025-10-02 02:03:28'),
(4, 2, 100000.00, 'delivered', 'transfer_bank', 'proof_1759372027_4.jpg', 'Tarub, Jl. Merdeka No.20', 'Pengiriman selesai', '2025-10-02 02:25:56'),
(5, 3, 280000.00, 'paid', 'transfer_bank', 'proof_1759372358_5.jpg', 'Pangkah, Jl. Pancasila No.15', 'Dikonfirmasi oleh admin', '2025-10-02 02:31:55'),
(6, 2, 100000.00, 'paid', 'payment_at_delivery', NULL, 'Tegal, Jl. Diponegoro No.8', NULL, '2025-10-02 04:24:44'),
(7, 2, 75000.00, 'paid', 'transfer_bank', 'proof_1759379385_7.jpg', 'Tegal, Jl. Ahmad Yani No.3', 'Dikonfirmasi oleh admin', '2025-10-02 04:29:29'),
(8, 2, 100000.00, 'paid', 'payment_at_delivery', NULL, 'Tegal, Jl. Thamrin No.12', NULL, '2025-10-02 04:33:35'),
(9, 3, 100000.00, 'pending', 'payment_at_delivery', NULL, 'Pangkah, Jl. Sudirman No.7', NULL, '2025-10-02 04:34:42'),
(10, 3, 200000.00, 'paid', 'transfer_bank', 'proof_1759379762_10.jpg', 'Pangkah, Jl. Raya No.25', 'Dikonfirmasi oleh admin', '2025-10-02 04:35:28'),
(11, 3, 375000.00, 'shipped', 'transfer_bank', 'proof_1759386207_3.jpg', 'Pangkah, Jl. Merdeka No.9', 'Sedang dikirim', '2025-10-02 06:23:05'),
(12, 2, 75000.00, 'paid', 'transfer_bank', 'proof_1759386436_2.png', 'Tegal, Jl. Gajah Mada No.4', 'Dikonfirmasi oleh admin', '2025-10-02 06:26:55'),
(13, 2, 100000.00, 'cancelled', 'payment_at_delivery', NULL, 'Tegal, Jl. Veteran No.6', 'Dibatalkan user - stok dikembalikan', '2025-10-02 06:29:53'),
(14, 2, 75000.00, 'paid', 'payment_at_delivery', NULL, 'fdh', NULL, '2025-10-02 08:02:20'),
(15, 2, 75000.00, 'delivered', 'payment_at_delivery', NULL, NULL, NULL, '2025-10-02 08:04:27'),
(16, 2, 100000.00, 'delivered', 'payment_at_delivery', NULL, NULL, NULL, '2025-10-02 23:39:07'),
(17, 2, 100000.00, 'shipped', 'transfer_bank', 'proof_1759451845_2.jpg', 'stdgtt', NULL, '2025-10-02 18:46:14'),
(18, 2, 235000.00, 'delivered', 'payment_at_delivery', NULL, 'erery', NULL, '2025-10-02 18:46:51'),
(19, 2, 175000.00, 'delivered', 'payment_at_delivery', NULL, 'fgd', NULL, '2025-10-02 19:45:27'),
(20, 2, 100000.00, 'pending', 'payment_at_delivery', NULL, NULL, NULL, '2025-10-02 20:48:40'),
(21, 3, 375000.00, 'pending', 'payment_at_delivery', NULL, NULL, NULL, '2025-10-02 22:32:02'),
(22, 2, 85000.00, 'delivered', 'payment_at_delivery', NULL, NULL, 'Otw', '2025-10-03 00:54:08'),
(23, 4, 140000.00, 'pending', 'payment_at_delivery', NULL, NULL, NULL, '2025-10-16 18:49:29'),
(24, 5, 140000.00, 'paid', 'payment_at_delivery', NULL, 'r', NULL, '2025-10-16 18:51:39');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `quantity`, `price`) VALUES
(1, 1, 6, 2, 100000.00),
(2, 1, 5, 1, 80000.00),
(3, 1, 4, 2, 50000.00),
(4, 1, 1, 3, 40000.00),
(5, 1, 2, 1, 100000.00),
(6, 1, 3, 3, 75000.00),
(7, 2, 6, 1, 100000.00),
(8, 2, 2, 2, 100000.00),
(9, 3, 3, 1, 75000.00),
(10, 3, 4, 1, 50000.00),
(11, 3, 6, 1, 100000.00),
(12, 4, 6, 1, 100000.00),
(13, 5, 6, 2, 100000.00),
(14, 5, 5, 1, 80000.00),
(15, 6, 6, 1, 100000.00),
(16, 7, 3, 1, 75000.00),
(17, 8, 6, 1, 100000.00),
(18, 9, 6, 1, 100000.00),
(19, 10, 6, 1, 100000.00),
(20, 10, 4, 2, 50000.00),
(21, 11, 6, 3, 100000.00),
(22, 11, 3, 1, 75000.00),
(23, 12, 3, 1, 75000.00),
(24, 13, 6, 1, 100000.00),
(25, 14, 3, 1, 75000.00),
(26, 15, 3, 1, 75000.00),
(27, 16, 2, 1, 100000.00),
(28, 17, 6, 1, 100000.00),
(29, 18, 3, 1, 75000.00),
(30, 18, 5, 2, 80000.00),
(31, 19, 6, 1, 100000.00),
(32, 19, 3, 1, 75000.00),
(33, 20, 2, 1, 100000.00),
(34, 21, 9, 1, 90000.00),
(35, 21, 6, 2, 100000.00),
(36, 21, 11, 1, 85000.00),
(37, 22, 11, 1, 85000.00),
(38, 23, 7, 1, 55000.00),
(39, 23, 11, 1, 85000.00),
(40, 24, 11, 1, 85000.00),
(41, 24, 7, 1, 55000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$g4uaCEf0/HNQYD9GvPvT8Oa.CRkRtDI9bJz6PyoaA9p1eqE.SKrRu', 'admin', '2025-10-02 00:28:36'),
(2, 'user', 'user@gmail.com', '$2y$10$GEr/xvc7FQerlsFOw37hJOp6WBo8bjTP.DGSVCmwE5cmxSXnbcv16', 'user', '2025-10-02 00:54:10'),
(3, 'orang', 'ser@gmail.com', '$2y$10$56Sl4q4MCJqet5sHybDkuuchay1khc/W2n9EuJ93/ILJWffl4ylXa', 'user', '2025-10-02 02:31:35'),
(4, 'lubi', 'user1@gmail.com', '$2y$10$mlrQJYQw4OxMZvmvsgDNi.mcfhXOOq5FnwPCc//wE0YspOvx6m/TK', 'user', '2025-10-16 23:48:54'),
(5, 'sewr', 'user2@gmail.com', '$2y$10$8Px9CRlN0pxz3jR81Q2ha.IPLYt0OOkJrLNuy.4occvOccnHz/p1W', 'user', '2025-10-16 23:51:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
