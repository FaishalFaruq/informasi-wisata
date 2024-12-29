<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Lokasi file JSON
$file = 'wisata.json';

if (file_exists($file)) {
    $jsonData = file_get_contents($file);
    $data = json_decode($jsonData, true);
    
    if (isset($data['Tempatwisata']) && is_array($data['Tempatwisata'])) {
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Informasi Tempat Wisata</title>";
        echo "<link rel='stylesheet' href='style.css'>";
        echo "<style>
                /* Tombol fixed */
                .fixed-buttons {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }
                .fixed-buttons button {
                    background-color: #007bff;
                    color: white;
                    border: none;
                    padding: 10px 15px;
                    font-size: 16px;
                    cursor: pointer;
                    border-radius: 5px;
                }
                .fixed-buttons button:hover {
                    background-color: #007bff;
                }
              </style>";
        echo "</head>";
        echo "<body>";
        echo "<h1>Informasi Tempat Wisata</h1>";
        
        // Kategorikan data tempat wisata
        $kategoriWisata = [];
        foreach ($data['Tempatwisata'] as $wisata) {
            if (isset($wisata['kategori']) && is_string($wisata['kategori'])) {
                $kategoriWisata[$wisata['kategori']][] = $wisata;
            }
        }

        /// Tampilkan data berdasarkan kategori
        foreach ($kategoriWisata as $kategori => $wisatas) {
            echo "<h2>" . htmlspecialchars($kategori) . "</h2>";
            echo "<div class='horizontal-scroll'>";
            
            foreach ($wisatas as $wisata) {
                echo "<div class='item'>";
                
                $defaultImage = "https://cdn4.iconfinder.com/data/icons/ui-beast-3/32/ui-49-4096.png";
                $image = isset($wisata['gambar']) && !empty($wisata['gambar']) ? htmlspecialchars($wisata['gambar']) : $defaultImage;
                
                echo "<img src='" . $image . "' alt='" . htmlspecialchars($wisata['nama']) . "' onerror='this.onerror=null;this.src=\"$defaultImage\";'>";
                echo "<h3>" . htmlspecialchars($wisata['nama']) . "</h3>";
                echo "<p><strong>Jadwal buka:</strong> " . htmlspecialchars($wisata['jadwal_buka']) . "</p>";
                echo "<p><strong>Harga:</strong> " . htmlspecialchars($wisata['harga']) . "</p>";
                echo "<p><strong>Alamat:</strong> " . htmlspecialchars($wisata['alamat']) . "</p>";
                echo "<a href='" . htmlspecialchars($wisata['link_gmaps']) . "' target='_blank'>Lihat di Google Maps</a>";
                echo "</div>";
            }

            echo "</div>";
        }

        echo "<div class='fixed-buttons'>
                <form action='login_admin.php' method='post'>
                    <button type='submit'>Daftar Karyawan</button>
                </form>
                <form action='logout.php' method='post'>
                    <button type='submit'>Logout</button>
                </form>
              </div>";

        echo "</body>";
        echo "</html>";
    } else {
        echo "<p>Data tempat wisata tidak valid atau kosong.</p>";
    }
} else {
    echo "<p>File JSON tidak ditemukan.</p>";
}
?>
